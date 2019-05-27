<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'shop_type_id',
        'address_id',
        'name',
        'contact',
        'wifi',
        'try_room',
        'card_payment',
        'wheel_chair',
        'wash_room',
        'delivery',
        'return_policy',
        'open_at',
        'close_at',
        'approved_at',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'parent_id')->where('type', 'shop');
    }

    public function promotion()
    {
        return $this->hasOne('App\Promotion');
    }

    public function shopType()
    {
        return $this->belongsTo('App\ShopType');
    }

    public function shopFollowers()
    {
        return $this->hasMany('App\ShopFollower');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'parent_id')->where('type', 'shop');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function faqs()
    {
        return $this->hasMany('App\Faq');
    }
}
