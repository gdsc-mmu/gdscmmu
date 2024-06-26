<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DiveController;
use App\Http\Middleware\EnsureCommittee;

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

Route::get('/events/create', [EventController::class, 'create'])->middleware('auth', EnsureCommittee::class);

Route::post('/events/create/store', [EventController::class, 'store'])->name('events.store')->middleware('auth', EnsureCommittee::class);

Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware('auth', EnsureCommittee::class);

Route::PUT('/events/{event}/edit', [EventController::class, 'update'])->name('events.update')->middleware('auth', EnsureCommittee::class);

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::DELETE('/events/{event}', [EventController::class, 'destroy'])->name('events.delete');

Route::get('/dives', [DiveController::class, 'dives'])->name('dives.index');

Route::get('/dives/{dive}/edit', [DiveController::class, 'edit'])->name('dives.edit')->middleware('auth');

Route::get('/dives/{dive}', [DiveController::class, 'show'])->name('dives.show');

Route::get('/login', [UserController::class, 'login']);

Route::post('/login', [UserController::class, 'loginUser'])->name('login');

Route::get('/signup', [UserController::class, 'signup']);

Route::post('/signup', [UserController::class, 'signupUser']);

Route::get('/logout', [UserController::class, 'logout']);

Route::get('/recovery', [UserController::class, 'recovery'])->middleware('guest')->name('password.request');

Route::post('/recovery', [UserController::class, 'sendRecoveryEmail'])->middleware('guest')->name('password.email');

Route::get('/recovery/{token}', [UserController::class, 'resetPasswordForm'])->middleware('guest')->name('password.reset');

Route::post('/recovery/{token}', [UserController::class, 'resetPassword'])->middleware('guest')->name('password.update');
