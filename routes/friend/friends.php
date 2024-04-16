<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuth;
use Illuminate\Support\Facades\Route;

Route::prefix("friends")->middleware(CustomAuth::class)->group(function () {

    // show all friends of users
    Route::get('', [FriendController::class, 'index'])->name('friends.index');

    // accept friend request
    Route::post('accept', [FriendController::class, 'accept'])->name('friends.accept');

    // cancel friend request
    Route::post('cancel', [FriendController::class, 'cancel'])->name('friends.cancel');
})->middleware(CustomAuth::class);
