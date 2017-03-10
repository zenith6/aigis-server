#!/bin/sh

set -eu

# Configuration
SYNCED_FOLDER=/vagrant
USER=vagrant
GROUP=vagrant
TEMPLATE_DIR=${SYNCED_FOLDER}/provision/app

# Vagrant user
cp -R ${TEMPLATE_DIR}/home/${USER} /home
chown -R ${USER}:${GROUP} /home/${USER}

# Package manager
if ! rpm -q yum-utils --quiet;
then
    yum install -y yum-utils
fi;

if ! rpm -q epel-release --quiet;
then
    yum install -y epel-release
    rpm -Uvh --force http://mirrors.mediatemple.net/remi/enterprise/remi-release-7.rpm
    yum-config-manager --enable epel remi remi-php71
fi

# Git
if ! rpm -q git --quiet;
then
    yum install -y git
    # git config --global color.ui true
fi

# MySQL
if ! rpm -q mysql-community-server --quiet;
then
    # for resolve dependencies
    yum remove -y postfix

    rpm -Uvh https://dev.mysql.com/get/mysql57-community-release-el7-8.noarch.rpm
    yum install -y mysql-community-server
    systemctl enable mysqld
    systemctl start mysqld

    # disable validate_password plugin
    systemctl stop mysqld
    echo 'validate_password=OFF' >> /etc/my.cnf
    systemctl start mysqld

    # change password
    mysql --connect-expired-password \
        -u root \
        -p$(grep 'A temporary password is generated for root@localhost' /var/log/mysqld.log | tail -1 | awk '{print $NF}') \
        < ${TEMPLATE_DIR}/home/${USER}/setup.sql
fi

# PHP
if ! test -e /usr/bin/php;
then
    yum install -y \
        php \
        php-mbstring \
        php-mcrypt \
        php-pdo \
        php-mysqlnd \
        php-gd \
        php-xml \
        php-bcmath \
        php-pecl-zip \
        php-pecl-xdebug \
        php-pecl-zendopcache

    cp -r ${TEMPLATE_DIR}/etc/php.d /etc/php.d
fi

# Composer
if ! test -e /usr/local/bin/composer;
then
    php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer
    chmod a+x /usr/local/bin/composer
fi

# wkhtmltopdf
if ! rpm -q wkhtmltox --quiet;
then
    sudo yum install -y \
        ipa-gothic-fonts \
        ipa-pgothic-fonts \
        ipa-mincho-fonts \
        ipa-pmincho-fonts \
        icu \
        libXext \
        libXrender \
        xorg-x11-fonts-Type1 \
        xorg-x11-fonts-75dpi

    sudo yum install -y http://download.gna.org/wkhtmltopdf/0.12/0.12.2/wkhtmltox-0.12.2_linux-centos7-amd64.rpm
fi

# node.js, npm, gulp, webpack
if ! rpm -q nodejs --quiet;
then
    yum install -y nodejs
fi

# Supervisor
if ! rpm -q supervisor --quiet;
then
    yum install -y supervisor
    curl -o /etc/systemd/system/supervisord https://raw.githubusercontent.com/Supervisor/initscripts/master/centos-systemd-etcs
    chmod 600 /etc/systemd/system/supervisord
    systemctl enable supervisord
    systemctl start supervisord
fi

# mailcatcher
if ! rpm -q ruby --quiet;
then
    yum install -y ruby ruby-devel gcc gcc-c++ sqlite sqlite-devel
fi

if ! gem list mailcatcher | grep -q mailcatcher;
then
    gem install mailcatcher --no-ri --no-doc
    cp -r ${TEMPLATE_DIR}/etc/supervisord.d /etc
fi

# Apache
if ! rpm -q httpd --quiet;
then
    yum install -y
    gpasswd -a apache vagrant
    cp -r ${TEMPLATE_DIR}/etc/httpd /etc

    CONF=/etc/httpd/conf.d/welcome.conf
    if [ -f ${CONF} ];
    then
        unlink ${CONF}
    fi

    systemctl enable mysqld
    systemctl start mysqld
fi

# Postfix
if ! rpm -q postfix --quiet;
then
    yum install -y postfix
    sed -i 's/^#home_mailbox = Maildir\/$/home_mailbox = Maildir\//' /etc/postfix/main.cf
    sed -i 's/^#luser_relay = admin+\$local$/luser_relay = vagrant@localhost.localdomain/' /etc/postfix/main.cf
    sed -i 's/^#local_recipient_maps =$/local_recipient_maps =/' /etc/postfix/main.cf
    systemctl enable postfix
    systemctl start postfix
fi

# Dovecot
if ! rpm -q dovecot --quiet;
then
    yum install -y dovecot
    sed -i 's/^#mail_location = /mail_location = maildir:~\/Maildir/' /etc/dovecot/conf.d/10-mail.conf
    systemctl enable dovecot
    systemctl start dovecot
fi

# FTP
if ! rpm -q vsftpd --quiet;
then
    yum install -y vsftpd
    echo 'userlist_deny=no' >> /etc/vsftpd/vsftpd.conf
    echo ${USER} >> /etc/vsftpd/user_list
    systemctl enable vsftpd
    systemctl start vsftpd
fi
