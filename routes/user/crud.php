<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->group(function () {

    // seaching for a user with username
    Route::get("{username}", [UserController::class, 'search'])->name('user.search');

    // add a user as friend
    Route::post("friend/add", [FriendController::class, 'add'])->name('friend.add');
});
