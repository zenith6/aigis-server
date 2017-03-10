<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drops', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('player_id');
            $table->integer('map_id');
            $table->integer('lap');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['player_id', 'map_id']);
            $table->index('updated_at');

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('map_id')->references('id')->on('maps')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('drops');
    }
}
