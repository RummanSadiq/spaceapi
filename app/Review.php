<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        "user_id", "parent_id", "rating", "description", "type", "is_active", "votes"
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'parent_id')->where('type', 'review');
    }
}
