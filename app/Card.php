<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
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
        return $this->hasMany('App\Models\Deck');
    }

    public function users() {
        return $this->hasMany('App\Models\User');
    }

}
