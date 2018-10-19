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
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'cards',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function cards() {
        return $this->hasMany(Card::class);
    }
}
