<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CustomAuth;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    //

    public function __construct()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
        ]);

        if (!$validated["name"]) {
            return response()->json([
                "error" => "Invalid name"
            ], '400');
        }

        // check if group already exists with the name and owner_id
        $group_exists = Group::where('name', $validated["name"])->where('owner_id', $request->user()->id)->exists();

        if ($group_exists) {
            return response()->json([
                "error" => "Group already exists"
            ], '400');
        }

        $group = Group::create([
            'name' => $validated["name"],
            'owner_id' => $request->user()->id
        ]);

        // need to attach this group to user
        $user = User::find($request->user()->id);
        $user->groups()->attach($group->id);

        return response()->json([
            "success" => true,
            "group" => $group
        ]);
    }

    // show page
    public function show(Request $request, $name)
    {

        if (!$request->user()) {
            return redirect('signin');
        }

        $group_exists = DB::table('groups')->where('name', $name)->first();

        $group = DB::table('groups_users')
            ->where('groups_id', $group_exists->id)
            ->join('groups', 'groups_users.groups_id', '=', 'groups.id')
            ->select('groups.name', 'groups.id', 'groups.owner_id')
            ->first();

        // DB::table('groups')->where('name', $name)->where('owner_id', $request->user()->id)
        //     ->select([
        //         'id', 'name', 'owner_id'
        //     ])->first();

        // check the length of $group
        if (!$group) {
            return redirect('home');
        }

        $groups = Group::where('owner_id', $request->user()->id)
            ->orderBy('created_at', 'asc')
            ->select([
                'id', 'name', 'owner_id'
            ])
            ->get();

        // get members of the group
        $members = DB::table('groups_users')
            ->where('groups_users.groups_id', $group->id)
            ->join('users', 'groups_users.users_id', '=', 'users.id')
            ->select('users.name', 'users.id')
            ->get();

        // get the friends of users that are not in the group


        $friends = DB::table('friends_users')
            ->where('friends_users.user_id', $request->user()->id)
            ->where('friends_users.accepted', 1) // uptil here i get all the friends
            ->join('users', 'friends_users.friend_id', '=', 'users.id')

            // ->join('users', 'friends_users.friend_id', '=', 'users.id')

            ->select('users.name', 'users.id')
            ->get();




        return inertia('Home/Group/GroupName', [
            "user" => $request->user(),
            "group" => $group,
            "groups" => $groups,
            "friends" => $friends,
            "members" => $members
        ]);
    }

    public function add_member(Request $request)
    {
        // check if data is provided
        $validated = $request->validate([
            'group_name' => ['required'],
            'user_id' => ['required']
        ]);

        if (!$validated["group_name"] || !$validated["user_id"]) {
            return response()->json([
                "error" => "Invalid data"
            ], '400');
        }

        // check if the user_id exists and is users friend
        $user_friend_exists = DB::table('friends_users')
            ->where('user_id', $request->user()->id)
            ->where('friend_id', $validated["user_id"])
            ->where('accepted', 1)
            ->first();

        if (!$user_friend_exists) {
            return response()->json([
                "error" => "User is not a friend"
            ], '400');
        }

        // check if the group exists
        $group_exists = DB::table('groups')->where('name', $validated["group_name"])->where('owner_id', $request->user()->id)->first();

        if (!$group_exists) {
            return response()->json([
                "error" => "Group does not exist"
            ], '400');
        }

        // add the user to the group
        $addedUserToGroup = DB::table('groups_users')->insert([
            "groups_id" => $group_exists->id,
            "users_id" => $validated["user_id"]
        ]);

        if (!$addedUserToGroup) {
            return response()->json([
                "error" => "Failed to add user to group"
            ], '400');
        }

        return response()->json([
            "success" => true
        ]);
    }
}
