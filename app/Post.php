<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    /**
     * The attributes that are importable.
     * 
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'content',
        'image_url',
        'user_id',
        'slug',
        'meta_description',
        'meta_title'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);    
    }
    
}
