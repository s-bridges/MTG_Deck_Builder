<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    CONST ADMIN_TYPE = 'admin';
    CONST DEFAULT_TYPE = 'default';

    public function isAdmin(){
        return $this->type === self::ADMIN_TYPE;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function decks() {
        return $this->hasMany(Deck::class);
    }
    public function cards() {
        return $this->hasMany(Card::class);
    } 
}
