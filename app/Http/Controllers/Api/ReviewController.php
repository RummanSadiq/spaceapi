<?php

namespace App\Http\Controllers\Api;

use App\Review;
use App\User;
use App\Attachment;
use App\Product;
use App\Shop;
use App\Notification;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMyShop()
    {
        $user = Auth::user();
        $shop = $user->shop;
        $reviews = $shop->reviews;


        foreach ($reviews as $rev) {
            $rev["key"] = $rev->id;
            $rev['username'] = User::find($rev->user_id)->name;
            $rev->attachments;
        }
        return response()->json($reviews);
    }

    public function indexMyProduct()
    {
        $user = Auth::user();
        $products = $user->shop->products->pluck('id')->toArray();


        $reviews = Review::whereIn('parent_id', $products)->where('type', 'product')->get();

        foreach ($reviews as $rev) {
            $rev["key"] = $rev->id;
            $rev['username'] = User::find($rev->user_id)->name;
            $rev->attachments;
        }

        return response()->json($reviews);
    }

    public function shopReviews($id)
    {
        $shop = Shop::find($id);
        $reviews = $shop->reviews;

        foreach ($reviews as $rev) {
            $rev["key"] = $rev->id;
            $rev['username'] = User::find($rev->user_id)->name;
            $rev->attachments;
        }
        return response()->json($reviews);
    }

    public function productReviews($id)
    {
        $product = Product::find($id);
        $reviews = $product->reviews;

        foreach ($reviews as $rev) {
            $rev["key"] = $rev->id;
            $rev['username'] = User::find($rev->user_id)->name;
            $rev->attachments;
        }
        return response()->json($reviews);
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

    public function upVote(Request $request, $id)
    {
        $this->vote($id, 1);
    }

    public function downVote(Request $request, $id)
    {
        $this->vote($id, -1);
    }

    private function vote($id, $no)
    {
        $review = Review::find($id);

        $review->update([
            'votes' => (int)$review->votes + $no
        ]);

        if ($review->votes < -3) {
            $review->update([
                'is_active' => 0
            ]);
        }
    }




    public function productStore(Request $request)
    {
        $request['type'] = "product";

        $this->store($request);
    }

    public function shopStore(Request $request)
    {

        $request['type'] = "shop";

        $this->store($request);
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
        $request['user_id'] = $user->id;

        $attachments = $request['attachments'];
        unset($request['attachments']);

        $review = Review::create($request->all());

        if ($attachments) {
            foreach ($attachments as $attachment) {
                Attachment::create([
                    'name' => $attachment['name'],
                    'url' => $attachment['response']['url'],
                    'parent_id' => $review->id,
                    'type' => 'review'
                ]);
            }
        }

        $shop_id = $request['parent_id'];

        if ($request['type'] === "product") {
            $shop_id = Product::findOrFail($request['parent_id'])->shop->id;
        }

        $data = [
            "receiver_id" => (int)$shop_id,
            "receiver_type" => "shop",
            "parent_id" => $user->id,
            "parent_type" => "user",
            "description" => $user->name . " has added a review for your " . $request['type']
        ];
        Notification::create($data);

        return response()->json($review, 201);
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
        $review = Review::find($id);

        if (!empty($request['attachments'])) {

            $review->attachments()->delete();
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
                    'parent_id' => $review->id,
                    'type' => 'review'
                ]);
            }
        }

        $review->update($request->all());
        return response()->json($review, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();
    }

    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json(Review::all());
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

            $review = Review::find($id)->update([
                "is_active" => $status
            ]);
            return response()->json($review);
        } else {
            return response()->json(401);
        }
    }
}
