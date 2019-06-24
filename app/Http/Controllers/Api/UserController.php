<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Shop;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::User()) {
            return response()->json(Auth::User());
        } else {
            return response()->json([]);
        }
    }

    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json(User::all());
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

            $user = User::find($id)->update([
                "is_active", $status
            ]);
            return response()->json($user);
        } else {
            return response()->json(401);
        }
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }


    public function hasShop()
    {
        $shop = Auth::User()->shop;
        // $shop = User::find(1)->store;
        if ($shop) {
            $res = 1;
        } else {
            $res = -1;
        }

        return response()->json([
            'shop' => $res
        ]);
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
