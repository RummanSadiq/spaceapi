<?php

namespace App\Http\Controllers\Api;

use App\Shop;
use App\ShopType;
use App\Address;
use App\User;
use App\Attachment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            $shop->attachments;
        }
        return response()->json($shops);
    }

    public function myShop()
    {

        $user = Auth::user();
        // $user = User::find(1);
        $shop = $user->shop;

        $shop['shop_owner'] = $user->name;
        $shop['shop_type'] = ShopType::find($shop->shop_type_id)->name;
        $shop['address'] = Address::find($shop->address_id)->place;
        $shop['city'] = Address::find($shop->address_id)->city;
        $shop['name'] = strtoupper($shop->name);

        $shop->attachments;

        foreach ($shop['attachments'] as $attachment) {

            $attachment['status'] = 'Done';
            $attachment['uid'] = $attachment['id'];
        }

        return response()->json($shop);
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

        $address = Address::create([
            'place' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'zip' => $request->input('zip'),
            'country' => $request->input('country')
        ]);

        $shoptype = ShopType::select('id')->where('name', $request['shop_type'])->first();
        $request['user_id'] = $user->id;
        $request['address_id'] = $address->id;



        unset($request['address']);
        unset($request['latitude']);
        unset($request['longitude']);
        unset($request['zip']);
        unset($request['country']);
        unset($request['city']);

        $attachments = $request['attachments'];
        unset($request['attachments']);

        $shop = Shop::create($request->all());

        foreach ($attachments as $attachment) {
            Attachment::create([
                'name' => $attachment['name'],
                'url' => $attachment['response']['url'],
                'parent_id' => $shop->id,
                'type' => 'shop'
            ]);
        }



        return response()->json($shop, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop = Shop::findOrFail($id)->with('address', 'user', 'shopType', 'attachments', 'reviews')->get()->first();

        $reviews = $shop->reviews;


        if (count($reviews) > 0) {

            $total = 0;
            $noOfReviews = 0;

            foreach ($reviews as $rev) {
                $total += $rev['rating'];
                $rev->attachments;
                $noOfReviews++;
                $rev->user;
            }


            $shop["avg_rating"] = $total / $noOfReviews;
            $shop["total_reviews"] = count($reviews);
        }
        $shop["key"] = $shop->id;

        return response()->json($shop);
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
    public function update(Request $request)
    {
        $user = Auth::user();
        // $user = User::find(2);
        $shop = $user->shop;


        $address = Address::find($shop->address_id);
        $address->update([
            'place' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'zip' => $request->input('zip'),
            'city' => $request->input('city'),
            'country' => $request->input('country')
        ]);



        unset($request['address']);
        unset($request['latitude']);
        unset($request['longitude']);
        unset($request['zip']);
        unset($request['country']);
        unset($request['city']);


        if (!empty($request['attachments'])) {

            $shop->attachments()->delete();
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
                    'parent_id' => $shop->id,
                    'type' => 'shop'
                ]);
            }
        }

        $shop->update($request->all());

        return response()->json($shop, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Shop::find($id);
        $shop->delete();
    }
}
