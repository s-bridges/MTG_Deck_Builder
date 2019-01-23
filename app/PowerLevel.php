<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PowerLevel extends Model
{

    /**
     * The attributes that are importable.
     * 
     * @var array
     */

    protected $fillable = [
        'name', 'description'
    ];
    
    public function cards()
    {
        return $this->belongsToMany(Card::class, 'cards_power_levels', 'power_level_id', 'card_id')->withPivot('ranking');    
    }

}
