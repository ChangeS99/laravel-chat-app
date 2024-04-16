<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Middleware\CustomAuth;

use Illuminate\Support\Facades\Route;



Route::get('/', [IndexController::class, 'index']);

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware(CustomAuth::class);
// Route::get('/messages/{group_name}', [HomeController::class, 'messages'])->name('messages');

// Route::post('/message', [HomeController::class, 'message']);

// Route::post('/broadcasting/auth', function () {
//     return true;
// });

// Auth Routes
require __DIR__ . '/user/auth.php';

// Group Routes
require __DIR__ . '/group/group.php';

// Message Routes
require __DIR__ . '/message/message.php';

// user crud Routes
require __DIR__ . '/user/crud.php';

// friends route
require __DIR__ . '/friend/friends.php';

// char route
require __DIR__ . '/chat/chat.php';
