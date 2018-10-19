<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deck', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('multiverseid');
            $table->string('layout');
            $table->string('names');
            $table->string('cmc');
            $table->string('colors');
            $table->string('type');
            $table->string('types');
            $table->string('subtypes');
            $table->string('rarity');
            $table->string('text');
            $table->string('flavor');
            $table->string('artist');
            $table->string('number');
            $table->string('power');
            $table->string('toughness');
            $table->string('reserved');
            $table->string('rulings');
            $table->string('printings');
            $table->string('originalText');
            $table->string('originalType');
            $table->string('legalities');
            $table->string('source');
            $table->string('imageUrl');
            $table->string('set');
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
        Schema::dropIfExists('deck');
    }
}
