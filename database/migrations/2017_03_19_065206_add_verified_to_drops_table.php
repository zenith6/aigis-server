<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifiedToDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drops', function (Blueprint $table) {
            $table->boolean('verified');

            $table->index('verified');
        });

        DB::update('
            UPDATE
                drops
                JOIN maps ON maps.id = drops.map_id
            SET
                drops.verified = drops.quantity / drops.lap <= maps.max_drops
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drops', function (Blueprint $table) {
            $table->dropIndex(['verified']);

            $table->dropColumn('verified');
        });
    }
}
