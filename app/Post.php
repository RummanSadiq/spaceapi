<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id', 'description', "is_active"
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function views()
    {
        return $this->hasMany('App\View', 'parent_id')->where('type', 'post');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'parent_id')->where('type', 'post');
    }

    public function totalViews()
    {
        return $this->views->count();
    }
}
