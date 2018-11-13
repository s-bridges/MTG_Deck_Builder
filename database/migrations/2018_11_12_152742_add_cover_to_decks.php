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
<<<<<<< HEAD
            $table->integer('cover')->default(195297);
            $table->string('allow_share')->default('off');
            $table->boolean('deck_of_the_week')->default(0);
=======
            $table->integer('cover')->nullable();
            $table->boolean('allow_share')->default(0);
>>>>>>> parent of 8c29d91... updated admin, dotw, cube card
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
        });
    }
}
