<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        "place", "latitude", "longitude", "zip", "city", "country", "is_active"
    ];

    public function shop()
    {
        return $this->hasOne('App\Shop');
    }
}
