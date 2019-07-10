<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Category;
use App\Shop;
use App\Attachment;
use App\View;
use App\Notification;
use App\ListItem;


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
        $products = Product::where('is_active', '1')->get();
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
        $prod->shop->address;


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


    public function myProducts()
    {
        $user = Auth::user();
        $shop = $user->shop;
        $products = $shop->products->where('is_active', '1')->reverse()->values();

        return response()->json($this->modifyProducts($products));
    }

    public function search($search)
    {
        $products = Product::where(
            'name',
            'LIKE',
            '%' . $search . '%'
        )->latest()->get();

        if (count($products) < 1) {
            return response()->json('No results found!');
        }

        return response()->json($this->modifyProducts($products));
    }

    public function searchLow($search)
    {
        $products = Product::where(
            'name',
            'LIKE',
            '%' . $search . '%'
        )->orderBy('price', 'asc')->get();

        if (count($products) < 1) {
            return response()->json('No results found!');
        }

        return response()->json($this->modifyProducts($products));
    }

    public function searchHigh($search)
    {
        $products = Product::where(
            'name',
            'LIKE',
            '%' . $search . '%'
        )->orderBy('price', 'desc')->get();

        if (count($products) < 1) {
            return response()->json('No results found!');
        }

        return response()->json($this->modifyProducts($products));
    }

    public function searchNearBy(Request $request, $search)
    {
        $products = Product::where(
            'name',
            'LIKE',
            '%' . $search . '%'
        )->get();

        if (count($products) < 1) {
            return response()->json('No results found!');
        }

        $modified = $this->modifyProducts($products);


        foreach ($modified as $prod) {
            $lat1 = $request['latitude'];
            $lon1 = $request['longitude'];
            $lat2 = $prod->shop->address->latitude;
            $lon2 = $prod->shop->address->longitude;

            if ($lat2 != null) {
                $prod['distance'] = $this->distance($lat1, $lon1, $lat2, $lon2);
            } else {
                $prod['distance'] = 0;
            }
        }


        foreach ($modified as $prod) {
            foreach ($modified as $prod2) {
                if ($prod->distance > $prod2->distance) {
                    $temp = $prod;
                    $prod = $prod2;
                    $prod2 = $temp;
                }
            }
        }


        return response()->json($modified);
    }

    function sort_objects_by_total($a, $b)
    {
        if ($a->distance == $b->distance) {
            return 0;
        }
        return ($a->distance < $b->distance) ? -1 : 1;
    }

    function distance($lat1, $lon1, $lat2, $lon2)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1.609344);
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

        $user = Auth::user();
        $shop = $user->shop;

        $prod = "";
        $disc = "";
        foreach ($request['products'] as $id) {
            $product = Product::findOrFail($id);
            $product->update([
                "sale_price" => $product->price - ($product->price * ((int) $request['percent'] / 100)),
                "sale_starts_at" => now(),
                "sale_ends_at" => $request['sale_ends_at']
            ]);
            $prod = $id;
            $disc = $request['percent'];
        }

        $notifications = array();
        $followers = $prod->shop->followers;

        foreach ($followers as $follower) {
            array_push($notifications, [
                "receiver_id" => $follower->user_id,
                "receiver_type" => "user",
                "parent_id" => $id,
                "parent_type" => "product",
                "description" => $shop->name . " products are on SALE, avail " . $disc . "% discount now.",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Notification::insert($notifications);
    }




    public function getShopProducts($shop_id)
    {
        $shop = Shop::find($shop_id);
        $products = $shop->products->where('is_active', '1')->reverse()->values();

        return response()->json($this->modifyProducts($products));
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

        $notifications = array();
        $followers = $shop->followers;

        foreach ($followers as $follower) {
            array_push($notifications, [
                "receiver_id" => $follower->user_id,
                "receiver_type" => "user",
                "parent_id" => $product->id,
                "parent_type" => "product",
                "description" => $shop->name . " added a new product.",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        Notification::insert($notifications);


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

        $this->modifyProduct($product);

        $user_id = 1;

        $item = ListItem::where('user_id', Auth::id())->where('is_active', '1')->where('product_id', $product_id)->get();

        if (count($item) > 0) {
            $product['added_to_list'] = 'true';
        } else {
            $product['added_to_list'] = 'false';
        }

        if (Auth::check()) {
            $user_id = Auth::id();
        }

        View::create([
            "user_id" => $user_id,
            "parent_id" => $product->id,
            "type" => "product"
        ]);
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

    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json($this->modifyProducts(Product::all()));
        } else {
            return response()->json(401);
        }
    }

    public function setInActive($id)
    {
        $this->setStatus($id, '0');
    }

    public function setActive($id)
    {
        $this->setStatus($id, '1');
    }

    private function setStatus($id, $status)
    {
        if (Auth::user()->is_super_admin) {

            $prod = Product::find($id)->update([
                "is_active" => $status
            ]);
            return response()->json($prod);
        } else {
            return response()->json(401);
        }
    }
}
