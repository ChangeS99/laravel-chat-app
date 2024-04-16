<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuth;
use Illuminate\Support\Facades\Route;

Route::prefix("chat")->middleware(CustomAuth::class)->group(function () {

    // show all friends of users
    Route::get('{id}', [ChatController::class, 'index'])->name('chat.index');

    // send a new message
    Route::post('store', [ChatController::class, 'store'])->name('chat.store');

    // get messages of a conversation
    Route::get('show/{friend_id}', [ChatController::class, 'show'])->name('chat.show');
});
