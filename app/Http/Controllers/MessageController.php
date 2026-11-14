<?php

namespace App\Http\Controllers;

use App\Events\NewMessageReceived;
use App\Events\ReceiveMessage;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\User;


class MessageController extends Controller
{
    public function sendMessage(MessageRequest $request)
    {
        $date = date('M d, y');
        $msg = Message::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'subject'=>$request->subject,
            'message'=>$request->message,
            'date'=>$date
        ]);

        $admins = User::where('role','admin')->pluck('email')->toArray();
        ReceiveMessage::dispatch(
            $admins,
            $request->name,
            $request->email,
            $request->subject,
            $request->message
        );
        NewMessageReceived::dispatch($msg);

        return response()->json([
            'message'=>'We recieved your message. We contact you shortly'
        ],200);

    }
}
