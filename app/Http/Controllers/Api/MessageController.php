<?php

namespace App\Http\Controllers\Api;

use App\Message;
use App\Conversation;
use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
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
    public function shopSent(Request $request)
    {
        $user_id = Auth::User()->id;
        // $user_id = User::find(1)->id;

        $conversation = Conversation::find($request['conversation_id']);

        return $this->sentMessage($conversation, $request['text'], $user_id);
    }

    private function sentMessage($conversation, $text, $user_id)
    {

        $data = [
            "last_sender_id" => $user_id,
            "msg_read" => '0',
            "last_message" => $text

        ];

        $conversation->update($data);

        $msg = Message::create([
            "sender_id" => $user_id,
            "conversation_id" => $conversation->id,
            "text" => $text
        ]);

        return response()->json($msg);
    }

    public function customerSent(Request $request)
    {

        //Fields required in the $request are 'participant_id', 'participant_type' => 0 for customer and 1 for shop, 'text'
        $user_id = Auth::user()->id;
        // $user_id = User::find(4)->id;

        $data = [
            "first_participant_id" => $user_id,
            "second_participant_id" => $request['participant_id'],
            "first_participant_type" => '0',
            "second_participant_type" => $request['participant_type']
        ];

        $conversation = Conversation::firstOrCreate($data);

        return $this->sentMessage($conversation, $request['text'], $user_id);
    }


    public function dent(Request $request)
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
    {
        $user = Auth::user();
        // $user = User::find(1);
        $conversation = Conversation::find($id);
        $messages = $conversation->messages;

        foreach ($messages as $msg) {
            if ($msg['sender_id'] == $user->id) {
                $msg['sender'] = 'true';
            } else {
                $msg['receiver'] = 'true';
            }
        }

        return response()->json($messages);
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
        //
    }
}
