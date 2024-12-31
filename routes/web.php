<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [TournamentController::class, 'index'])
    ->name('tournament.index');

Route::get('/tournament/{id}', [TournamentController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('tournament.show');

Route::post('/tournament/play', [TournamentController::class, 'play'])
    ->name('tournament.play');

Route::get('/tournament/reult/{id}', [TournamentController::class, 'result'])
    ->where('id', '[0-9]+')
    ->name('tournament.result');

Route::get('players', [PlayerController::class, 'index']);
