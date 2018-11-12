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
            $table->integer('cover');
            $table->boolean('allow_share');
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
