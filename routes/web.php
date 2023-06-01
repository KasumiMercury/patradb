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
/* Authorization */
Route::get("/transition-login", [App\Http\Controllers\LoginController::class, "index"])->name("transition.login");

/*main routes*/
Route::get('/',[App\Http\Controllers\ViewController::class, 'TopPage'])->name('toppage');
Route::get('/privacypolicy',function(){
    return Inertia::render('PrivacyPolicy');
})->name('privacypolicy');
Route::get('/sitepolicy',function(){
    return Inertia::render('SitePolicy');
})->name('sitepolicy');

Route::prefix('memories')->group(function(){
    Route::get('/',function () {
        return Inertia::render('MemoryTop');
    })->name('memory.top');
    Route::get('search',[App\Http\Controllers\ViewController::class,'CreatePlayer'])->name('memory.search');
    Route::get('videos',[App\Http\Controllers\ViewController::class,'SearchVideo'])->name('memory.videos');
    Route::get('collabo',[App\Http\Controllers\ViewController::class,'SearchCollabo'])->name('memory.collabo');
    Route::get('list',[App\Http\Controllers\ViewController::class,'CreatePlayer'])->name('memory.list');
    Route::get('player',[App\Http\Controllers\ViewController::class,'CreatePlayer'])->name('memory.player');
});

Route::prefix('launch')->group(function(){
    Route::get('/',function () {
        return Inertia::render('LaunchTop');
    })->name('adddata');
    Route::get('player',[App\Http\Controllers\ViewController::class,'CreatePlayer'])->name('create.player');
    Route::get('complete/{id}',[App\Http\Controllers\ViewController::class,'LaunchComplete'])->name('data.launched');
    Route::get('collabo',function(){
        return Inertia::render('RegisterCollabo');
    })->name('create.collabo');
});

Route::prefix('post')->group(function(){
    Route::POST('collabo',[App\Http\Controllers\DataController::class, 'PostCollabo'])->name('post.collabo');
});

Route::get('chat',[App\Http\Controllers\ViewController::class,'ViewChat'])->name('chatview');

Route::get('tools',function () {
    return Inertia::render('ToolsTop');
})->name('toolstop');
/*main routes end*/

Route::prefix('create')->group(function(){
    Route::get('schedule',function(){
        return Inertia::render('CreateSchedule');
    })->middleware('auth.guard')->name('create.schedule');
    Route::get('stream',function () {
        return Inertia::render('CreateStream');
    })->name('create.stream');
});

// admin only routes group with AdminGuard middleware
Route::prefix('admin')->middleware('auth.admin')->group(function(){
    Route::get('/',function () {
        return Inertia::render('TopPage');
    })->name('admin.top');
    Route::get('schedule',function () {
        return Inertia::render('AdminSchedule');
    })->name('admin.schedule');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        redirect()->route('toppage');
    })->name('dashboard');
});
