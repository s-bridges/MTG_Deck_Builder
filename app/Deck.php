<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
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
        return $this->belongsTo('App\Models\Deck');
    }

    public function cards() {
        return $this->hasMany('App\Models\Card');
    }
}
