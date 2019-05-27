<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Category;
use App\Shop;
use App\Attachment;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        foreach ($products as $prod) {
            $prod['shop_name'] = Shop::find($prod->shop_id)->name;
            $prod['category_name'] = Category::find($prod->category_id)->name;

            $prod->attachments;

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
        return response()->json($products);
    }

    public function myProducts()
    {

        $user = Auth::user();
        $shop = $user->shop;
        $products = $shop->products->reverse()->values();
        foreach ($products as $prod) {
            $prod["key"] = $prod->id;
            $prod["category"] = Category::find($prod->category_id)->name;
        }
        return response()->json($products);
    }

    public function getFiltered(Request $request)
    {
        if ($request->has("search")) {
            $products = collect(DB::select("Select * from products where name like ?", ['%' . $request['search'] . '%']));
        } else {

            $products = Product::all();
        }

        $products = $products->reverse()->values();

        if ($request->has("price_min")) {
            $products = $products->where('price', '>=', $request['price_min'])->values();
        }
        if ($request->has("price_max")) {
            $products = $products->where('price', '<=', $request['price_max'])->values();
        }
        if ($request->has("category")) {
            $cat = Category::where('name', '=', $request['category'])->first();
            if ($cat) {
                $products = $products->where('category_id', $cat->id)->values();
            }
        }
        if ($request->has("lat") && $request->has("long")) {
            //
        }

        if ($request->has("low_price")) {
            $products = $products->sortBy('price')->values();
        }

        if ($request->has("high_price")) {
            $products = $products->sortByDesc('price')->values();
        }


        return response()->json($products);
    }




    public function setDiscount(Request $request)
    {
        foreach ($request['products'] as $id) {
            $product = Product::findOrFail($id);
            $product->update([
                "sale_price" => $product->price - ($product->price * ((int)$request['percent'] / 100)),
                "sale_starts_at" => now(),
                "sale_ends_at" => $request['sale_ends_at']
            ]);
        }
    }




    public function getShopProducts($shop_id)
    {
        $shop = Shop::find($shop_id);
        $products = $shop->products->reverse()->values();
        foreach ($products as $prod) {
            $prod["key"] = $prod->id;
            $prod["category"] = Category::find($prod->category_id)->name;

            $prod->attachments;

            foreach ($prod['attachments'] as $attachment) {

                $attachment['status'] = 'Done';
                $attachment['uid'] = $attachment['id'];
            }
        }
        return response()->json($products);
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
    public function store(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;
        $request['shop_id'] = $shop->id;

        $attachments = $request['attachments'];
        unset($request['attachments']);

        $request['category_id'] = last($request->category);

        $product = Product::create($request->all());

        foreach ($attachments as $attachment) {
            Attachment::create([
                'name' => $attachment['name'],
                'url' => $attachment['response']['url'],
                'parent_id' => $product->id,
                'type' => 'product'
            ]);
        }

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        $shop = $product->shop;
        $shop->user;
        $shop->address;
        $reviews = $product->reviews;

        $product->attachments;

        if (count($reviews) > 0) {

            $total = 0;
            $noOfReviews = 0;

            foreach ($reviews as $rev) {
                $rev->attachments;
                $total += $rev['rating'];
                $noOfReviews++;
                $rev->user;
            }


            $product["avg_rating"] = $total / $noOfReviews;
            $product["total_reviews"] = count($reviews);
        }
        $product["key"] = $product->id;

        $product["category"] = Category::find($product->category_id)->name;

        return response()->json($product);
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
        $product = Product::find($id);

        // $category = Category::select('id')->where('name', $request['category'])->first();
        // $request['category_id'] = $category->id;
        // unset($request['category']);

        if (!empty($request['attachments'])) {

            $product->attachments()->delete();
            $attachments = $request['attachments'];
            unset($request['attachments']);

            foreach ($attachments as $attachment) {

                if (isset($attachment['response'])) {
                    $url =  $attachment['response']['url'];
                } else {
                    $url =  $attachment['url'];
                }
                Attachment::create([
                    'name' => $attachment['name'],
                    'url' => $url,
                    'parent_id' => $product->id,
                    'type' => 'product'
                ]);
            }
        }

        $product->update($request->all());
        return response()->json($product, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
    }
}
