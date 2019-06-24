<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\User;
use App\Shop;
use App\View;
use App\Attachment;
use App\Notification;


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
    public function index()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $post->attachments;
            $post->shop;
            $post['total_views'] = $post->totalViews();
        }

        return response()->json($posts);
    }


    public function myPosts()
    {
        $user = Auth::user();

        $shop = $user->shop;
        $posts = $shop->posts->reverse()->values();

        foreach ($posts as $post) {
            $post->attachments;
            $post['total_views'] = $post->totalViews();
        }
        return response()->json($posts);
    }


    public function getShopPosts($shop_id)
    {
        $shop = Shop::find($shop_id);
        $posts = $shop->posts->reverse()->values();

        $user_id = 1;

        if (Auth::check()) {
            $user_id = Auth::id();
        }

        foreach ($posts as $post) {
            $post->attachments;
            $post['total_views'] = $post->totalViews();

            View::create([
                "user_id" => $user_id,
                "parent_id" => $post->id,
                "type" => "post"
            ]);
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

        $notifications = array();
        $followers = $shop->followers;

        foreach ($followers as $follower) {
            array_push($notifications, [
                "receiver_id" => $follower->user_id,
                "receiver_type" => "user",
                "parent_id" => $post->id,
                "parent_type" => "post",
                "description" => $shop->name . " added a new post.",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        Notification::insert($notifications);



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


    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json(Post::all());
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

            $post = Post::find($id)->update([
                "is_active" => $status
            ]);
            return response()->json($post);
        } else {
            return response()->json(401);
        }
    }
}
