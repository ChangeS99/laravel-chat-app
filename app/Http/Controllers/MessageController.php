<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //

    public function group_show($group_name)
    {
        $user_id = Auth::user()->id;
        //$messagesQuery = Group::with('messages', 'messages.user')->where('name', $group_name)->get();
        // $messages = Message::with('user')->where('group.name', $group_name)->get();
        $groupQuery = Group::whereHas('users', function ($query) use ($user_id) {
            $query->where('users_id', $user_id);
        })->where('name', $group_name)
            ->whereHas('messages')
            ->with('messages.user')
            ->first();



        return response()->json([
            "group" => $groupQuery,
        ]);
    }

    public function store(Request $request)
    {

        $message = Message::create([
            'user_id' => Auth::user()->id,
            'text' => $request->get('text'),
            'group_id' => $request->get('group_id'),
        ]);



        try {

            SendMessage::dispatch([
                "message" => $message,
                "deleted" => false
            ]);
        } catch (Exception $error) {
            return response()->json([
                "success" => false,
                "error" => "Something went wrong. Please try again.",
                "message" => "Message not delivered.",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Message created and job dispatched.",
        ]);
    }

    public function conversation_show(Request $request, $user_id)
    {
        // check if the conversation exist from both ends
        $member_1_conversation_exists = Conversation::where('member_1', Auth::user()->id)->where('member_2', $user_id)->exists();
        $member_2_conversation_exists = Conversation::where('member_1', $user_id)->where('member_2', Auth::user()->id)->exists();

        dd($member_1_conversation_exists, $member_2_conversation_exists);
    }
}
