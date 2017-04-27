<?php

use Aigis\Database\ForeignKeyControllable;
use Aigis\Game\Mission;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
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

        DB::table('missions')->delete();

        $source = new \SplFileObject(database_path('data/missions.csv'));

        $source->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD | SplFileObject::READ_CSV);

        $header = $source->fgetcsv();

        while ($values = $source->fgetcsv()) {
            $mission = new Mission(array_combine($header, $values));
            $mission->save();
        }

        DB::commit();
        Eloquent::reguard();

        $this->enableForeignKeyCheck();
    }
}
