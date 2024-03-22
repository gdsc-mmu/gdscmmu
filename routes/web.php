<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index']);

Route::get('/team', [IndexController::class, 'team']);

Route::get('/events', [EventController::class, 'events'])->name('events.index');

Route::get('/events/create', [EventController::class, 'create']);

Route::post('/events/create/store', [EventController::class, 'store'])->name('events.store');

Route::get('/login', [UserController::class, 'login']);

Route::post('/login', [UserController::class, 'loginUser']);

Route::get('/signup', [UserController::class, 'signup']);

Route::post('/signup', [UserController::class, 'signupUser']);

Route::get('/logout', [UserController::class, 'logout']);
