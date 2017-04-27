<?php

use Aigis\Database\ForeignKeyControllable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissionIdToMapsTable extends Migration
{
    use ForeignKeyControllable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->disableForeignKeyCheck();

        Schema::table('maps', function (Blueprint $table) {
            $table->integer('mission_id');

            $table->foreign('mission_id')->references('id')->on('missions')->onUpdate('cascade')->onDelete('cascade');
        });

        DB::insert("
            INSERT INTO missions (
                id,
                name,
                weight,
                allow_report 
            )
            VALUES (
                1,
                'dummy',
                0,
                0
            )
        ");

        DB::update('UPDATE maps SET mission_id = 1');

        $this->enableForeignKeyCheck();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropForeign(['mission_id']);

            $table->dropColumn('mission_id');
        });
    }
}
