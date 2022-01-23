<?php

use App\Http\Controllers\v1\api\GamesController;
use App\Http\Controllers\v1\IndexController;
use Illuminate\Support\Facades\Route;

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

// Pages
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/board', [IndexController::class, 'board'])->name('board');

// API
Route::apiResource('/api/v1/games', GamesController::class)->except(['index', 'create', 'edit', 'destroy']);
Route::get('/api/v1/games/win-check/{id}', [GamesController::class, 'winCheck']);
Route::get('/api/v1/games/pc-move/{id}', [GamesController::class, 'pcMove']);
