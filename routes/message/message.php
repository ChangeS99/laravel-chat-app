<?php


use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix("message")->middleware('custom-auth')->group(function () {

    Route::post("store", [MessageController::class, "store"])->name('message.store');

    Route::get("{group_name}/show", [MessageController::class, "group_show"])->name('message.group.show');

    // route for conversation
    Route::get('converstation/{user_id}', [MessageController::class, 'conversation_show'])->name('message.conversation.show');
});
