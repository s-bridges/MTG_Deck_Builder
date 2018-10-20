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
        'multiverse_id',
        'name',
        'mana_cost',
        'cmc',
        'type',
        'text',
        'power',
        'toughness',
        'colors',
        'sec_color',
        'set',
        'set_name',
        'collector_number',
        'rarity',
        'flavor',
        'user_id',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'colors',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'deck_id',
        'multiverseid',
    ];

    public function decks() {
        return $this->hasMany(Deck::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

}
