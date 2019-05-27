<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        "name", "default_picture", "parent_id", "is_active"
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
