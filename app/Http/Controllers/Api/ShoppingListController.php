<?php

namespace App\Http\Controllers\Api;

use App\ListItem;
use App\Product;
use App\Shop;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = ListItem::where('user_id', Auth::id())->where('is_active', '1')->pluck('product_id')->toArray();

        $products = Product::whereIn('id', $items)->get();

        return response()->json($this->modifyProducts($products));
    }

    private function modifyProducts($products)
    {
        foreach ($products as $prod) {
            $this->modifyProduct($prod);
        }

        return $products;
    }

    private function modifyProduct($prod)
    {
        $prod['shop_name'] = Shop::find($prod->shop_id)->name;
        $prod['category_name'] = Category::find($prod->category_id)->name;

        $prod['total_views'] = $prod->totalViews();
        $prod->attachments;
        $prod->shop;

        foreach ($prod['attachments'] as $attachment) {

            $attachment['status'] = 'Done';
            $attachment['uid'] = $attachment['id'];
        }


        $reviews = $prod->reviews;
        if (count($reviews) > 0) {

            $total = 0;
            $noOfReviews = 0;

            foreach ($reviews as $rev) {
                $total += $rev['rating'];
                $noOfReviews++;
                $rev->user;
            }


            $prod["avg_rating"] = $total / $noOfReviews;
            $prod["total_reviews"] = count($reviews);
        }
        $prod["key"] = $prod->id;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $item = ListItem::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $id
        ]);

        $item->update([
            'is_active' => 1
        ]);

        return response()->json($item, 200);
    }

    public function remove($id, Request $request)
    {
        $item = ListItem::where('user_id', Auth::id())->where('product_id', $id)->first();

        $item->update([
            'is_active' => 0
        ]);

        return response()->json($item);
    }

    public function toggle($id, Request $request)
    {
        $items = ListItem::where('user_id', Auth::id())->where('product_id', $id)->where('is_active', '1')->get();

        if (count($items) > 0) {
            $item = $items[0];
            $item->update([
                'is_active' => 0
            ]);
        } else {
            $item = ListItem::firstOrCreate([
                'user_id' => Auth::id(),
                'product_id' => $id
            ]);

            $item->update([
                'is_active' => 1
            ]);
        }

        return response()->json($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
