<?php


use App\Http\Controllers\GroupController;
use App\Http\Middleware\CustomAuth;
use Illuminate\Support\Facades\Route;


Route::prefix('group')->middleware('custom-auth')->group(function () {
    // creating a new group from home page
    Route::post('store', [GroupController::class, 'store'])->name('group.store');

    // routing to a group page
    Route::get('{name}', [GroupController::class, 'show'])->name('group.show')->middleware(CustomAuth::class);

    // adding a member to group
    Route::post('add-member', [GroupController::class, 'add_member'])->name('group.add-member');
});
