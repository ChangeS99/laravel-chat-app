<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

/**
 * Configure how to channels will authenticate
 * */

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel_for_everyone', function ($user) {
    return true;
});

Broadcast::channel('home-user-{id}-groups', function ($user, $id) {
    return true;
});

Broadcast::channel('group-{gid}', function ($user, $gid) {

    // check if the group exists and user is a part of it
    $groups_users_exist = DB::table('groups_users')->where('groups_id', $gid)->where('users_id', $user->id)->exists();

    return $groups_users_exist;
});

Broadcast::channel("conversation-{cid}", function ($user, $cid) {

    // check if user is part of the channel
    $conversation_exists = DB::table('conversations')->where('id', $cid)->where('member_1', $user->id)->orWhere('member_2', $user->id)->first();

    return $conversation_exists ? true : false;
});
