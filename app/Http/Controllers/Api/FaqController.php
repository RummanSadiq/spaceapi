<?php

namespace App\Http\Controllers\Api;

use App\Faq;
use App\Shop;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $store = Store::select('id')->where('owner_id', $id)->first();
        $user = Auth::user();
        $shop = $user->shop;
        // $store_id = $store->id;

        // $faqs = Faq::where('store_id', $store_id)->get();
        $faqs = $shop->faqs;
        return response()->json($faqs);
    }

    public function getShopFaqs($id)
    {
        $shop = Shop::find($id);
        $faqs = $shop->faqs;
        return response()->json($faqs);
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
        $faq = Faq::create($request->all());

        $notifications = array();

        $followers = $shop->followers;

        foreach ($followers as $follower) {
            array_push($notifications, [
                "receiver_id" => $follower->user_id,
                "receiver_type" => "user",
                "parent_id" => $faq->id,
                "parent_type" => "faq",
                "description" => $shop->name . " added a new FAQ",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Notification::insert($notifications);

        return response()->json($faq, 201);
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
        $faq = Faq::find($id);
        $faq->update($request->all());
        return response()->json($faq, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
    }

    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json(Faq::all());
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

            $faq = Faq::find($id)->update([
                "is_active" => $status
            ]);
            return response()->json($faq);
        } else {
            return response()->json(401);
        }
    }
}
