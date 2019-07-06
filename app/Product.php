<?php

namespace App;

use App\Category;
use App\Shop;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'sale_price',
        'category_id',
        'sale_starts_at',
        'sale_ends_at',
        'is_active'
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function views()
    {
        return $this->hasMany('App\View', 'parent_id')->where('type', 'product');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'parent_id')->where('type', 'product');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review', 'parent_id')->where('type', 'product');
    }

    public function totalViews()
    {
        return $this->views->count();
    }


    public function modifyProducts()
    {
        foreach ($this as $prod) {
            $this->modifyProduct($prod);
        }

        return $this;
    }

    public function modifyProduct()
    {
        $this['shop_name'] = Shop::find($this->shop_id)->name;
        $this['category_name'] = Category::find($this->category_id)->name;

        $this['total_views'] = $this->totalViews();
        $this->attachments;
        $this->shop;

        foreach ($this['attachments'] as $attachment) {

            $attachment['status'] = 'Done';
            $attachment['uid'] = $attachment['id'];
        }


        $reviews = $this->reviews;
        if (count($reviews) > 0) {

            $total = 0;
            $noOfReviews = 0;

            foreach ($reviews as $rev) {
                $total += $rev['rating'];
                $noOfReviews++;
                $rev->user;
            }


            $this["avg_rating"] = $total / $noOfReviews;
            $this["total_reviews"] = count($reviews);
        }
        $this["key"] = $this->id;
    }
}
