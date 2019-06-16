<?php

namespace App\Http\Controllers\Api;

use App\Message;
use App\Conversation;
use App\User;
use App\Events\NewMessage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{

    protected $user_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->user_id = Auth::id();
    }


    public function index()
    {
        //
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
     * Store a new message.
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Required parameters are (conversation_id, text)
        $conversation = Conversation::findOrFail($request['conversation_id']);

        if ($this->user_id == $conversation->user_id || $this->user_id == $conversation->shop_owner_id) {

            $request['sender_id'] = $this->user_id;

            $type = null;
            $receiver_id = null;

            if ($this->user_id == $conversation->user_id) {
                $type = "shop";
                $receiver_id = $conversation->shop_owner_id;
            } else {
                $type = "customer";
                $receiver_id = $conversation->user_id;
            }

            $msg = Message::create($request->all());

            if ($msg['sender_id'] == $this->user_id) {
                $msg['sender'] = 'true';
            } else {
                $msg['receiver'] = 'true';
            }

            broadcast(new NewMessage($msg, $type, $receiver_id));

            $conversation->update([
                "last_sender_id" => $this->user_id,
                "last_message" => $request['text'],
                "is_read" => 0
            ]);

            return response()->json($msg, 200);
        }

        return response()->json("User is not a participant in the conversation", 401);
    }


    public function newMessage(Request $request)
    {
        // Required parameters are (shop_owner_id, text)

        if ($this->user_id != $request['shop_owner_id']) {

            $conversation = Conversation::firstOrCreate([
                "user_id" => $this->user_id,
                "shop_owner_id" => $request['shop_owner_id']
            ]);

            $conversation->update([
                "last_sender_id" => $this->user_id,
                "last_message" => $request['text']
            ]);

            $msg = Message::create([
                "conversation_id" => $conversation->id,
                "sender_id" => $this->user_id,
                "text" => $request['text']
            ]);

            return response()->json($msg, 200);
        }

        return response()->json("User can not start a conversation with itself", 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conversation = Conversation::find($id);

        if ($this->user_id == $conversation->user_id || $this->user_id == $conversation->shop_owner_id) {

            $messages = $conversation->messages->sort()->values()->all();

            foreach ($messages as $msg) {
                if ($msg['sender_id'] == $this->user_id) {
                    $msg['sender'] = 'true';
                } else {
                    $msg['receiver'] = 'true';
                }
            }

            return response()->json($messages);
        }
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
