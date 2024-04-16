<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Authenticated;

use Illuminate\Support\Facades\Route;

// View Routes
Route::get("signup", [AuthController::class, 'signup'])->name('signup')->middleware(Authenticated::class);
Route::get("signin", [AuthController::class, 'signin'])->name('signin')->middleware(Authenticated::class);
// Route::get('login', function () {
//     return redirect()->route('signin');
// })->name('login');

// Business Routes
Route::post('signin', [AuthController::class, 'signinStore'])->name('signin.store');
Route::post('signup', [AuthController::class, 'signupStore'])->name('signup.store');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
