<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->integer('multiverse_id');
            $table->string('card')->nullable();
            $table->string('name')->nullable();
            $table->string('mana_cost')->nullable();
            $table->string('cmc')->nullable();
            $table->string('type')->nullable();
            $table->text('text')->nullable();
            $table->string('power')->nullable();
            $table->string('toughness')->nullable();
            $table->string('colors')->nullable();
            $table->string('set')->nullable();
            $table->string('set_name')->nullable();
            $table->string('collector_number')->nullable();
            $table->string('rarity')->nullable();
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
        Schema::dropIfExists('cards');
    }
}
