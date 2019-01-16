<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sideboard extends Model
{
    //
    /**
     * The attributes that are importable.
     * 
     * @var array
     */
    protected $importable = [
        'id',
        'card_id',
        'deck_id',
        'count'
    ];

    public function cards()
    {
        return $this->belongsToMany(Card::class, 'cards_decks', 'deck_id', 'card_id')->withPivot('count');    
    }
    public function decks()
    {
        return $this->belongsToMany(Deck::class, 'cards_decks', 'card_id', 'deck_id');
    }
}
