<?php

namespace App\Http\Controllers;

use App\Jobs\SendConversationMessage;
use App\Jobs\SendMessage;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    //

    // function to check if conversation exists
    private function conversation_exists($request, $friend_id)
    {
        $member_1_conversation_exists = DB::table('conversations')->where('member_1', $request->user()->id)->where('member_2', $friend_id)->first();

        $member_2_conversation_exists = DB::table('conversations')->where('member_1', $friend_id)->where('member_2', $request->user()->id)->first();
        $exists = true;
        if (!$member_1_conversation_exists && !$member_2_conversation_exists) {
            $exists = false;

            return [
                "exists" => $exists,
                "conversation_id" => null
            ];
        }

        // get the messages for this conversation
        $conversation_id = $member_1_conversation_exists ? $member_1_conversation_exists->id : $member_2_conversation_exists->id;

        return [
            "exists" => $exists,
            "conversation_id" => $conversation_id
        ];
    }


    public function index(Request $request, $friend_id)
    {

        // check if the converstation exist from both sides
        // for member_1 and member_2
        $member_1_conversation_exists = DB::table('conversations')->where('member_1', $request->user()->id)->where('member_2', $friend_id)->first();

        $member_2_conversation_exists = DB::table('conversations')->where('member_1', $friend_id)->where('member_2', $request->user()->id)->first();

        if (!$member_1_conversation_exists && !$member_2_conversation_exists) {
            dd("Huh member2");
        }

        // get the messages for this conversation
        $conversation_id = $member_1_conversation_exists ? $member_1_conversation_exists->id : $member_2_conversation_exists->id;

        $conversation = Conversation::where('conversations.id', $conversation_id)->first();



        $groups = Group::where('owner_id', $request->user()->id)->select([
            'id', 'name', 'owner_id'
        ])->get();

        $friends = DB::table('friends_users')
            ->where('friends_users.user_id', $request->user()->id)
            ->where('friends_users.friend_id', $friend_id)->first();

        $friend = DB::table('users')->where('id', $friend_id)->first();


        if ($friends === null) {
            return redirect('home');
            // return inertia("Home/Friends/Index", [
            //     "groups" => $groups,
            //     "user" => $request->user(),
            //     "friends" => $allFriends
            // ]);
        }

        return inertia("Chat/Index", [
            "groups" => $groups,
            "user" => $request->user(),
            "friend" => $friend,
            "conversation" => $conversation
        ]);
    }

    public function show(Request $request, $friend_id)
    {

        // check if friend id is valid
        if (!User::where('id', $friend_id)->exists()) {
            return response()->json([
                "success" => false,
                "error" => "Friend id is not valid."
            ]);
        }

        // check if they are friends or not
        $is_friend = $request->user()->isFriendsWith(User::find($friend_id));
        $is_friend_other = User::find($friend_id)->isFriendsWith($request->user());
        if (!$is_friend && !$is_friend_other) {
            return response()->json([
                "success" => false,
                "error" => "Not friends."
            ]);
        }

        $conversation_exist = $this->conversation_exists($request, $friend_id);

        if (!$conversation_exist["exists"]) {
            return response()->json([
                "success" => false,
                "error" => "Conversation does not exist."
            ]);
        }

        $conversation_id = $conversation_exist["conversation_id"];



        $conversation_2 = DB::table('messages')
            ->where("messages.conversation_id", $conversation_id)
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->select('messages.text', 'messages.created_at', 'users.name', 'users.id as userId')
            ->get();

        return response()->json([
            "messages" => $conversation_2
        ]);
    }

    public function store(Request $request)
    {
        // check if friend id was provided with request
        if (!$request->has('friend_id')) {
            return response()->json([
                "success" => false,
                "error" => "No data provided."
            ]);
        }

        $conversation_exist = $this->conversation_exists($request, $request->get('friend_id'));

        if (!$conversation_exist["exists"]) {
            return response()->json([
                "success" => false,
                "error" => "Conversation does not exist."
            ]);
        }

        $conversation_id = $conversation_exist["conversation_id"];


        $message = Message::create([
            'text' => $request->get('text'),
            'user_id' => $request->user()->id,
            'conversation_id' => $conversation_id
        ]);

        try {

            SendConversationMessage::dispatch([
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
}
