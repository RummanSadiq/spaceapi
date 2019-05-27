<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopType extends Model
{
    protected $fillable = ["name", "is_active"];

    public function shops()
    {
        return $this->hasMany('App\Shop');
    }
}
