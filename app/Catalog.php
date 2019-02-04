<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    //
    protected $fillable = [
        'id',
        'user_id',
        'card_id',
        'count',
    ];

    public function users() {
        return $this->hasMany(User::class, 'user_id');
    }

    public function cards() {
        return $this->hasMany(Card::class, 'card_id')->withPivot('count');
    }
}