<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlayerApiController;
use App\Http\Controllers\Api\TournamentApiController;

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

// players
Route::get('/players', [PlayerApiController::class, 'index']);
Route::get('/players/{id}', [PlayerApiController::class, 'show'])
    ->where('id', '[0-9]+');

// tournaments
Route::get('/tournaments', [TournamentApiController::class, 'index']);
Route::post('/tournaments', [TournamentApiController::class, 'store']);
Route::get('/tournaments/{id}', [TournamentApiController::class, 'show'])
    ->where('id', '[0-9]+');
