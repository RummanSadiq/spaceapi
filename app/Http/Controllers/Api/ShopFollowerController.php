<?php

namespace App\Http\Controllers\Api;

use App\ShopFollower;
use App\User;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Shop;

class ShopFollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::User();

        $shops = ShopFollower::where("user_id", $user->id)->get()->values();

        return response()->json($shops);
    }

    public function myFollowers()
    {

        $user = Auth::User();

        return response()->json($user->shop->followers()->with('user')->get());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function follow($id)
    {
        $user = Auth::User();
        // $user = User::find(1);
        $con = [
            "user_id" => $user->id,
            "shop_id" => $id
        ];
        $row = ShopFollower::where($con)->first();
        if (!$row) {
            ShopFollower::create($con);

            Notification::create([
                "receiver_id" => $id,
                "receiver_type" => "shop",
                "parent_id" => $user->id,
                "parent_type" => "user",
                "description" => "is following your shop",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $this->destroy($row->id);
        }
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
        $row = ShopFollower::find($id);
        $row->delete();
    }
}
