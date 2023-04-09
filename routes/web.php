<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*main routes*/
Route::get('/',[App\Http\Controllers\ViewController::class, 'TopPage'])->name('toppage');

Route::get('data',function () {
    return Inertia::render('DataView');
})->name('dataview');

Route::prefix('add')->group(function(){
    Route::get('/',function () {
        return Inertia::render('AddData');
    })->name('adddata');
    Route::get('player',[App\Http\Controllers\ViewController::class,'CreatePlayer'])->name('create.player');
    Route::get('complete',[App\Http\Controllers\ViewController::class,'LaunchComplete'])->name('data.launched');
});

Route::get('chat',function () {
    return Inertia::render('ChatView');
})->name('chatview');

Route::get('tools',function () {
    return Inertia::render('ToolsTop');
})->name('toolstop');
/*main routes end*/

Route::prefix('create')->group(function(){
    Route::get('schedule',function () {
        return Inertia::render('CreateSchedule');
    })->name('create.schedule');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
