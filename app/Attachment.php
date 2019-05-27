<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        "name", "url", "parent_id", "type", "is_active"
    ];
}
