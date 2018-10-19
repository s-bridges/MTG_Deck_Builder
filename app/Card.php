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
        'cost',
        'random_number',
        'type',
        'flavor',
        'number_1',
        'number_2',
        'pri_color',
        'sec_color',
        'color',
        'set',
        'number_4',
        'rarity',
        'image_url',
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
