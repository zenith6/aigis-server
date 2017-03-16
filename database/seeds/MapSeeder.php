<?php

use Aigis\Database\ForeignKeyControllable;
use Aigis\Game\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    use ForeignKeyControllable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeyCheck();

        Eloquent::unguard();
        DB::beginTransaction();

        DB::table('maps')->delete();

        $source = new \SplFileObject(database_path('data/maps.csv'));

        $source->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD | SplFileObject::READ_CSV);

        $header = $source->fgetcsv();

        while ($values = $source->fgetcsv()) {
            $map = new Map(array_combine($header, $values));
            $map->save();
        }

        DB::commit();
        Eloquent::reguard();

        $this->enableForeignKeyCheck();
    }
}
