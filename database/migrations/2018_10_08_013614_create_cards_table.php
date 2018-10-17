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
            $table->increments('id');
            $table->integer('type_id');
            $table->string('type_icon')->nullable();
            $table->string('name')->unique();
            $table->integer('deck_points')->nullable();
            $table->integer('cost')->nullable();
            $table->string('action');
            $table->string('effect');
            $table->string('flavor_text');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * When ready to import to database, table needs to be reconfig
     * name, multiverseid, layout, 
     * names, manaCost, cmc, colors, 
     * type, types, subtypes, rarity, 
     * text, flavor, artist, number, 
     * power, toughness, reserved, 
     * rulings, printings, originalText,
     * originalType, legalities, source
     * imageUrl, set, id
     */

    /**
     * ADDITIONALLY, a SET table will need to be created with this information
     * code, name, gatherer_code, old_code,
     * magic_cards_info_code, release_date, border
     * type, block, online_only, booster,
     * mkm_id, mkm_name
     * 
     * ONCE SET IT IN DB, QUERIES CAN OCCUR/ HOWEVER API PREVENTS UNLESS BY NAME | BLOCK
     */
    
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
