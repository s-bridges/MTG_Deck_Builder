<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->string('name');
            $table->string('gatherer_code');
            $table->string('old_code');
            $table->string('magic_cards_info_code');
            $table->string('release_date');
            $table->string('border');
            $table->string('type');
            $table->string('block');
            $table->string('online_only');
            $table->string('booster');
            $table->string('mkm_id');
            $table->string('mkm_name');
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
        Schema::dropIfExists('set');
    }
}
