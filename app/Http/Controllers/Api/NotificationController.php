<?php

namespace App\Http\Controllers\Api;

use App\Notification;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex()
    {
        // title: "Title of notification",
        // message: "You have received a particular type of notiification that will be showed up here",
        // link: "/product/2",
        // read: true


        /**
         *"description" => $shop->name . " added a new FAQ",
         *"description" => $user->name . " has added a review for your " . $request['type']
         *"description" => $shop->name . " added a new post.",
         *"description" => $shop->name . " products are on SALE, avail " . $disc . "% discount now.",
         *"description" => $shop->name . " added a new product.",
         *"description" => "A new report has been generated for a " . $request['type'] . " by " . $user->name,
         *"description" => $user->name . " is now following your shop",
         * 
         * 
         * 
         */

        return response()->json($this->modifyResponse(Notification::where('receiver_type', 'user')->get()));
    }

    public function shopIndex()
    {
        return response()->json($this->modifyResponse(Notification::where('receiver_type', 'shop')->get()));
    }

    public function adminIndex()
    {
        return response()->json($this->modifyResponse(Notification::where('receiver_type', 'admin')->get()));
    }

    private function modifyResponse($notifications)
    {
        foreach ($notifications as $notification) {
            if ($notification['parent_type'] === "product") {

                $notification['url'] = "/product/" . $notification['parent_id'];
            } else if ($notification['parent_type'] === "post") {

                $post = Post::find($notification['parent_id']);
                if ($post) {

                    $notification['url'] = "/store/" . $post->shop->id;
                }
            } else if ($notification['parent_type'] === "user") {

                $notification['url'] = "/reviews";
            } else if ($notification['parent_type'] === "report") {

                $notification['url'] = "/reports";
            } else if ($notification['parent_type'] === "report") {

                $notification['url'] = "/reports";
            }
        }
        return $notifications;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */

    public function setRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(["is_read" => 1]);
        return response()->json($notification, 201);
    }


    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
