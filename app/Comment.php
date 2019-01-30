<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //    
    protected $fillable = [
        'id',
        'user_id',
        'post_id',
        'comment',
        'likes',
        'flagged'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);    
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
