<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
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
        return $this->hasOne('App\Shop');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    // public function productReviews()
    // {
    //     return $this->hasMany('App\ProductReview');
    // }

    public function shopFollowers()
    {
        return $this->hasMany('App\ShopFollower');
    }

    // public function firstParticipantConversations()
    // {
    //     return $this->hasMany('App\Conversation', 'first_participant_id');
    // }

    // public function secondParticipantConversations()
    // {
    //     return $this->hasMany('App\Conversation', 'second_participant_id');
    // }

    // public function shopConversations()
    // {
    //     return $this->firstParticipantConversations->where('first_participant_type', '1')
    //         ->merge($this->secondParticipantConversations->where('second_participant_type', '1'));
    // }

    // public function customerConversations()
    // {
    //     return $this->firstParticipantConversations->where('first_participant_type', '0')
    //         ->merge($this->secondParticipantConversations->where('second_participant_type', '0'));
    // }
}
