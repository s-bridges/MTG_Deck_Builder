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
        return $this->belongsToMany(Deck::class, 'cards_decks', 'card_id', 'deck_id');
    }

    public function power_levels()
    {
        return $this->belongsToMany(PowerLevel::class, 'cards_power_levels', 'card_id', 'power_level_id')->withPivot('ranking');
    }

    public function users() {
        return $this->hasMany(User::class);
    }
    public function sideboard_cards() {
        return $this->belongsToMany(Card::class, 'sideboards', 'deck_id', 'card_id')->withPivot('count');
    }
    public function catalogs() {
        return $this->belongsToMany(Catalog::class, 'card_id')->withPivot('count');
    }
}
