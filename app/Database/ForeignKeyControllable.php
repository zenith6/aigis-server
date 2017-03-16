<?php
namespace Aigis\Database;

trait ForeignKeyControllable
{
    public function disableForeignKeyCheck()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }

    public function enableForeignKeyCheck()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
