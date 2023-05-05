<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('post')->group(function(){
    Route::POST('schedule',[App\Http\Controllers\DataController::class, 'PostSchedule'])->name('post.schedule');
    Route::POST('player',[App\Http\Controllers\DataController::class, 'PostPlayer'])->name('post.player');
});

Route::prefix('check')->group(function(){
    Route::GET('collabo',[App\Http\Controllers\DataController::class, 'CheckCollabo'])->name('check.collabo');
});

Route::prefix('notification')->group(function(){
    Route::POST('user',[App\Http\Controllers\NotificationController::class, 'RegisterUser'])->name('register.notification.user');
    Route::GET('check',[App\Http\Controllers\NotificationController::class, 'CheckUser'])->name('check.notification.user');
    Route::POST('get/schedule',[App\Http\Controllers\NotificationController::class, 'GetSchedule'])->name('get.notification.schedule');
    Route::POST('register/schedule',[App\Http\Controllers\NotificationController::class, 'RegisterSchedule'])->name('register.notification.schedule');
    Route::POST('remove/schedule',[App\Http\Controllers\NotificationController::class, 'RemoveSchedule'])->name('remove.notification.schedule');
    Route::POST('clear/schedule',[App\Http\Controllers\NotificationController::class, 'ClearSchedule'])->name('clear.notification.schedule');
});
