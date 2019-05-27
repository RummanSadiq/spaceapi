<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        "user_id", "shop_owner_id", "last_sender_id",  "last_message", "is_read", "is_active"
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
