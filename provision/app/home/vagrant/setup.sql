SET PASSWORD = '';

CREATE USER 'vagrant'@'localhost' IDENTIFIED BY 'vagrant';
GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'localhost';

CREATE DATABASE `aigis` DEFAULT CHARACTER SET utf8mb4;
