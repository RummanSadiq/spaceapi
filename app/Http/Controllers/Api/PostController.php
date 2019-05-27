<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\User;
use App\Shop;
use App\Attachment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myPosts()
    {
        $user = Auth::user();
        // $user = User::find(1);

        $shop = $user->shop;
        // $posts = $store->posts->sortByDesc('created_at');
        $posts = $shop->posts->reverse()->values();

        foreach ($posts as $post) {
            $post->attachments;
        }
        return response()->json($posts);
    }

    public function index()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $post->attachments;
            $post->shop;
        }

        return response()->json($posts);
    }


    public function getShopPosts($shop_id)
    {
        $shop = Shop::find($shop_id);
        $posts = $shop->posts->reverse()->values();

        foreach ($posts as $post) {
            $post->attachments;
        }

        return response()->json($posts);
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
        // $user = User::find(1);

        $shop = $user->shop;
        $request['shop_id'] = $shop->id;

        $attachments = $request['attachments'];
        unset($request['attachments']);

        $post = Post::create($request->all());

        foreach ($attachments as $attachment) {
            Attachment::create([
                'name' => $attachment['name'],
                'url' => $attachment['response']['url'],
                'parent_id' => $post->id,
                'type' => 'post'
            ]);
        }


        return response()->json($post, 201);
    }

    public function productPost(Request $request)
    {
        $description = "We just added a new product to our store. Buy " . $request['name'] . " at Rs. " . $request['price'] . " only." . " Contact us for more info";

        $request['description'] = $description;

        // return response()->json($request);
        return $this->store($request);
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
        $post = Post::find($id);

        if (!empty($request['attachments'])) {

            $post->attachments()->delete();
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
                    'parent_id' => $post->id,
                    'type' => 'post'
                ]);
            }
        }


        $post->update($request->all());
        return response()->json($post, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
    }
}
