<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // cosntructor
    // middlware for auth
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = User::where('id', auth()->id())->select([
            'id', 'name', 'email',
        ])->first();

        // $groups = Group::where('owner_id', $user->id)->select([

        //     'id', 'name', 'owner_id'
        // ])->get();

        $groups = DB::table('groups_users')
            ->where('users_id', $request->user()->id)
            ->join('groups', 'groups_users.groups_id', '=', 'groups.id')
            ->select('groups.name', 'groups.id', 'groups.owner_id')
            ->orderBy('groups.created_at', 'asc')
            ->get();



        $friend_requests = DB::table('friends_users')
            ->select('friends_users.user_id', 'users.name', 'users.email')
            ->join('users', 'friends_users.user_id', '=', 'users.id')
            ->where('friends_users.friend_id', $request->user()->id)
            ->where('friends_users.user_id', '!=', $request->user()->id)
            ->get();
        // ->where('friends_users.user_id', $request->user()->id)
        // ->where('friends_users.user_id', '!=', $request->user()->id)
        // ->groupBy('friends_users.user_id')
        // ->get();

        return inertia("Home/Index", [
            "user" => $user,
            "groups" => $groups,
            "friend_requests" => $friend_requests
        ]);
    }
}
