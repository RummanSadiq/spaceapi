<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function shopConversations()
    {

        $user = Auth::user();

        $conversations = $user->shopConversations;

        $conversations = $this->addFields($conversations, $user);

        return response()->json($conversations);
    }

    public function customerConversations()
    {
        $user = Auth::user();

        $conversations = $user->customerConversations;

        $conversations = $this->addFields($conversations, $user);

        return response()->json($conversations);
    }

    private function addFields($conversations, $user)
    {
        foreach ($conversations as $con) {

            $con['username'] = $user->name;

            if ($con['last_sender_id'] == $user->id) {
                $con['prefix'] = "You: ";
                // $con['read'] = '1';
            } else {
                $con['prefix'] = "";
            }
        }

        return $conversations;
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
     * @param  \App\Controller  $controller
     * @return \Illuminate\Http\Response
     */
    public function show(Controller $controller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Controller  $controller
     * @return \Illuminate\Http\Response
     */
    public function edit(Controller $controller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Controller  $controller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Controller $controller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Controller  $controller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Controller $controller)
    {
        //
    }
}
