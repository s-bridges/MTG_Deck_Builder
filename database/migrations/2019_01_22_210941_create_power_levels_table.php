<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowerLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('cards_power_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_id')->unsigned();
            $table->integer('power_level_id')->unsigned();
            $table->decimal('ranking', 2, 1);
            
            // Reference decks table
            $table->foreign('power_level_id')
                ->references('id')
                ->on('power_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // Reference cards table
            $table->foreign('card_id')
                ->references('id')
                ->on('cards')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards_power_levels');
        Schema::dropIfExists('power_levels');
    }
}
