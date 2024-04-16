<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\FriendRequest;
use App\Models\FriendsUsers;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function __construct()
    {
    }

    private function UserInstance($user)
    {

        // check if the $user is an instance of User Model
        if ($user !== null && $user instanceof User) {
            return $user;
        }
    }
    //
    // show friend list page
    public function index(Request $request)
    {
        $groups = Group::where('owner_id', $request->user()->id)->select([
            'id', 'name', 'owner_id'
        ])
            ->orderBy('created_at', 'asc')
            ->get();



        $friends = DB::table('friends_users')
            ->where('friends_users.user_id', $request->user()->id)
            ->where('friends_users.accepted', 1)
            ->join('users', 'friends_users.friend_id', '=', 'users.id')
            ->select('users.name', 'users.id')
            ->get();

        $pending_requests = $this->pendingList($request);

        $requests_list = $this->requestList($request);




        return inertia("Home/Friends/Index", [
            "groups" => $groups,
            "user" => $request->user(),
            "friends" => $friends,
            "pending_requests" => $pending_requests,
            "requests_list" => $requests_list
        ]);
    }

    public function add(Request $request)
    {
        // check if there is friend id or not
        $friend_id = $request->get('friend_id');

        if (!$friend_id || (strlen($friend_id) === 0)) {
            return response()->json([
                "success" => false,
                "error" => "No data provided."
            ]);
        }
        $user2 = User::find($friend_id);

        if ($user2->id === $request->user()->id) {
            return response()->json([
                "success" => false,
                "error" => "Cannot add yourself."
            ]);
        }
        // check if already friend from user
        $user =  Auth::user();

        $friends = [];

        // check if the $user is an instance of User Model
        if ($user !== null && $user instanceof User) {

            $is_friend = $user->isFriendsWith($user2);
            //$is_friend = Auth::user()->isFriendsWith($user2);

            if ($is_friend) {
                return response()->json([
                    "success" => false,
                    "error" => "Already Friends"
                ]);
            }

            $user->friends()->attach($friend_id);

            // create a row in friendRequests
            $friend_request = FriendRequest::create([
                'to_id' => $friend_id,
                'from_id' => $request->user()->id
            ]);



            // attach with user2 as well
            // user2 will only be attached if user2 accepts the friend request
            // $user2->friends()->attach(Auth::user()->id);

            // $friends = $user->friends()->get();

            // get the friend object
            $friend = DB::table('friends_users')
                ->where('friends_users.user_id', $request->user()->id)
                ->where('friends_users.friend_id', $friend_id)
                ->join('users', 'friends_users.friend_id', '=', 'users.id')
                ->select('users.id', 'users.name')
                ->first();

            return response()->json([
                "success" => true,
                "message" => "Friend Added",
                "friend" => $friend,
                // "friends" => $friends
            ]);
        }
    }

    public function accept(Request $request)
    {
        // get the friend id from request
        $request_id = $request->get("request_id");

        // check if the request_id is provided or empty string
        if (!$request_id || (strlen($request_id) === 0)) {

            return response()->json([
                "success" => false,
                "error" => "No data provided."
            ]);
        }

        // before attaching check if the user exists
        $requestedUser = User::where('id', $request_id)->first();

        // check if a row already exists in friends_users table
        $friendsExist = FriendsUsers::where('user_id', $request->user()->id)->where('friend_id', $request_id)->exists();

        if ($friendsExist) {



            return response()->json([
                "success" => false,
                "error" => "Already Friends"
            ]);
        }

        if (!$requestedUser) {
            return response()->json([
                "success" => false,
                "error" => "Request User not found."
            ]);
        }

        // attach it to the user
        $user = $this->UserInstance(Auth::user());

        $user->friends()->attach($request_id);


        // change the accepted status from both ends
        DB::table('friends_users')->where('user_id', $request->user()->id)->where('friend_id', $request_id)->update(['accepted' => 1]);

        DB::table('friends_users')->where('user_id', $request_id)->where('friend_id', $request->user()->id)->update(['accepted' => 1]);


        // delete a row from friendRequests table
        FriendRequest::where('to_id', $request->user()->id)
            ->where('from_id', $request_id)
            ->delete();

        // create a conversation with member_1 as user and member_2 as requestedUser
        Conversation::create([
            'member_1' => $request->user()->id,
            'member_2' => $request_id
        ]);

        // send json response with message

        return response()->json([
            "success" => true,
            "message" => "Request Accepted.",
            "user" => $requestedUser
        ]);
    }

    public function cancel(Request $request)
    {
        // get the id of the user
        $request_id = $request->get("request_id");

        // check if request_id is provided or empty string
        if (!$request_id || (strlen($request_id) === 0)) {

            return response()->json([
                "success" => false,
                "error" => "No data provided."
            ]);
        }

        // check if the user exists
        $requestedUser = User::where('id', $request_id)->first();

        if (!$requestedUser) {

            return response()->json([
                "success" => false,
                "error" => "Request User not found."
            ]);
        }
        DB::beginTransaction();
        // remove it from the friends_users table
        $removedFriendUser = DB::table('friends_users')
            ->where('user_id', $request->user()->id)
            ->where('friend_id', $request_id)
            ->delete();

        // remove it from the friend_requests table
        $removedFriendRequest = DB::table('friend_requests')
            ->where('to_id', $request_id)
            ->where('from_id', $request->user()->id)
            ->delete();

        if (!$removedFriendUser || !$removedFriendRequest) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "error" => "Something went wrong. Please try again."
            ]);
        }
        DB::commit();


        return response()->json([
            "success" => true,
            "message" => "Request removed.",
            "user" => $requestedUser
        ]);
    }

    public function requestList(Request $request)
    {

        function friendRequests($n)
        {
            return true;
        }

        $friend_requests = DB::table('friend_requests')
            ->leftJoin('users', 'friend_requests.from_id', '=', 'users.id')
            ->where('friend_requests.to_id', $request->user()->id)
            ->select('users.id', 'users.name',)
            ->get();

        return $friend_requests;

        // return response()->json([
        //     "friend_requests" => $friend_requests
        // ]);
    }

    public function pendingList(Request $request)
    {
        $pending_requests = DB::table('friend_requests')
            ->leftJoin('users', 'friend_requests.to_id', '=', 'users.id')
            ->where('friend_requests.from_id', $request->user()->id)
            ->select('users.id', 'users.name')->get();

        return $pending_requests;
    }
}
