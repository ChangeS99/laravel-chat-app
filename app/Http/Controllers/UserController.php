<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class UserController extends Controller
{
    //

    public function search(Request $request, $username)
    {

        $users =  User::select('users.*', DB::raw('IFNULL(friends_users.user_id, 0) AS already_friends'))
            ->where('name', 'like', "%$username%")
            ->leftJoin('friends_users', function ($join) {
                $join->on('friends_users.friend_id', '=', 'users.id')
                    ->where('friends_users.user_id', '=', Auth::user()->id);
            })
            ->get();



        // $users = User::where('name', 'like',  "%$username%")->get();



        return response()->json([
            "users" => $users,
            "params" => $username
        ]);
    }

    public function friendList(Request $request)
    {
    }
}
