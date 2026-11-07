<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Automated Cron Webhook (invoked by GitHub Actions, cron-job.org, etc.)
Route::match(['get', 'post'], '/cron/sync-bevy', [EventController::class, 'cronSync'])->name('api.cron.sync_bevy');
