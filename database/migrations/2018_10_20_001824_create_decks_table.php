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
        // Decks table that is used for creating decks
        Schema::create('decks', function (Blueprint $table) {
            // Unique id in the database
            $table->increments('id');
            // General deck fields
            $table->string('name');
            $table->string('description');
            // for relating user to their decks
            $table->integer('user_id');
            $table->timestamps();
        });
        // Create table for associating cards (many) to decks (many)
        Schema::create('cards_decks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_id')->unsigned();
            $table->integer('deck_id')->unsigned();
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
        Schema::drop('cards_decks');
        Schema::drop('decks');
    }
}