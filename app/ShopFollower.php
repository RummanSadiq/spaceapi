<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopFollower extends Model
{
    protected $fillable = [
        "user_id", "shop_id", "is_active"
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function shops()
    {
        return $this->belongsTo('App\Shop');
    }
}
