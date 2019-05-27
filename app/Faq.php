<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id', 'question', 'answer', "is_active"
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
