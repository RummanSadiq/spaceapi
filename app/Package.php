<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        "name", "cost", "duration", "description", "is_active"
    ];
}
