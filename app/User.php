<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'date_of_birth', 'gender', 'phone_no', 'shopping_list_name', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->hasOne('App\Shop')->where('user_id', Auth::id());
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function views()
    {
        return $this->hasMany('App\View');
    }

    // public function productReviews()
    // {
    //     return $this->hasMany('App\ProductReview');
    // }

    public function follows()
    {
        return $this->hasMany('App\ShopFollower');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification', 'receiver_id')->where('receiver_type', 'user');
    }

    public function shopConversations()
    {
        return $this->hasMany('App\Conversation', 'shop_owner_id');
    }

    public function customerConversations()
    {
        return $this->hasMany('App\Conversation', 'user_id');
    }
}
