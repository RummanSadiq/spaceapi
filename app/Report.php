<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        "user_id", "parent_id", "type", "is_active"
    ];
}
