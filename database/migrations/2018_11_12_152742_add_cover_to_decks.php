<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverToDecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('decks', function($table)
        {
            $table->integer('cover')->nullable();
            $table->boolean('allow_share')->default(0);
            $table->boolean('deck_of_the_week')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('decks', function($table)
        {
            $table->dropColumn('cover');
            $table->dropColumn('allow_share');
            $table->dropColumn('deck_of_the_week');
        });
    }
}
