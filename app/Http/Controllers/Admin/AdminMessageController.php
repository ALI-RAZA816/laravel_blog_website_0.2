<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageStatusUpdated;
use App\Events\ReplyMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function fetchMessages()
    {
        $messages = Message::latest()->paginate(5);

        return response()->json([
            'messages'      => $messages,
            'totalMessages' => Message::select('id', 'status')->get(),
            'response_avg'  => Message::where('reply', 'replied')->avg('reply_diff'),
        ], 200);
    }

    public function fetchSingle(string $id)
    {
        $message = Message::where('id',$id)->first();
        if(!$message){
            return response()->json([
                'message' => 'Message not found'
            ],404);
        }
        $message->update(['status' => 'read']);
        MessageStatusUpdated::dispatch($message);
        return response()->json([
            'message' => $message
        ],200);
    }

    public function deleteMessage(string $id)
    {
        $message = Message::where('id',$id)->first();
        if(!$message){
            return response()->json([
                'message' => 'Message not found'
            ],404);
        }
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully'
        ],200);
    }

    public function markAsRead(string $id)
    {
        $message = Message::where('id',$id)->first();
        if(!$message){
            return response()->json([
                'message' => 'Message not found'
            ],404);
        }

        $message->update(['status' => 'read']);
        MessageStatusUpdated::dispatch($message);
        return response()->json([
            'message' => 'Message marked as read successfully'
        ],200);
    }

    public function markAsUnread(string $id)
    {
        $message = Message::where('id',$id)->first();
        if(!$message){
            return response()->json([
                'message' => 'Message not found'
            ],404);
        }
        
        $message->update(['status' => 'unread']);
        MessageStatusUpdated::dispatch($message);
        return response()->json([
            'message' => 'Message marked as unread successfully'
        ],200);
    }

    public function sendReply(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
           'reply' => 'required|string|max:10000'
        ],[
            'reply.required' => 'Reply message is required',
        ]);

        $message = Message::where('id',$request->id)->first();
        if(!$message){
            return response()->json([
                'message' => 'Message not found'
            ],404);
        }

        if($message->reply == 'replied'){
            return response()->json([
                'message' => 'Reply already sent'
            ],400);
        }

        $replyAt = now();

        $difference = $message->created_at->diffInHours($replyAt);

        $message->update([
            'reply'      => 'replied',
            'reply_at'   => $replyAt,
            'reply_diff' => $difference,
        ]);
        
        ReplyMessage::dispatch($message->email, $request->reply);
        return response()->json([
            'message' => 'Reply sent successfully'
        ],200);
    }
}
