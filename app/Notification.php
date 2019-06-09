<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'receiver_id',
        'receiver_type',
        'parent_id',
        'parent_type',
        'description',
        'url',
        'is_read',
        'is_active'
    ];
}
