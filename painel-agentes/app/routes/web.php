<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth.session')->group(function () {
    Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');
    Route::post('/chat/{id}/message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::get('/chats/{id}/partial', [ChatController::class, 'partial'])->name('chats.partial');
    Route::post('/chat/close', [ChatController::class, 'close'])->name('chat.close');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');