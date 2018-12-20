<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are importable.
     * 
     * @var array
     */
    protected $importable = [
        'card',
        'id',
        'multiverse_id',
        'name',
        'mana_cost',
        'cmc',
        'type',
        'text',
        'power',
        'toughness',
        'colors',
        'set',
        'set_name',
        'collector_number',
        'rarity',
        'user_id',
    ];

    public function decks()
    {
        return $this->belongsToMany(Deck::class, 'cards_decks', 'card_id', 'deck_id', 'sideboards'); //add , 'sideboards'
    }

    public function users() {
        return $this->hasMany(User::class);
    }

}
