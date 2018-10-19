<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card');
            $table->integer('multiverse_id');
            $table->string('name');
            $table->string('cost');
            $table->integer('random_number');
            $table->string('type');
            $table->string('flavor');
            $table->integer('number_1');
            $table->integer('number_2');
            $table->string('pri_color');
            $table->string('sec_color');
            $table->string('color');
            $table->string('set');
            $table->integer('number_4');
            $table->string('rarity');
            $table->string('image_url');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decks');
    }
}
