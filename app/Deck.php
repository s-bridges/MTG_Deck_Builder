<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
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

    protected $fillable = [
        'cover', 'allow_share', 'name', 'description'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function cards()
    {
        return $this->belongsToMany(Card::class, 'cards_decks', 'deck_id', 'card_id', 'sideboards')->withPivot('count'); //add , 'sideboards' to Card::
    }
}
