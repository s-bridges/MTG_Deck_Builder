<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSideboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sideboards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_id')->unsigned();
            $table->integer('deck_id')->unsigned();
            $table->integer('count')->default(1);
            
            // Reference decks table
            $table->foreign('deck_id')
                ->references('id')
                ->on('decks')
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
        Schema::dropIfExists('sideboards');
    }
}
