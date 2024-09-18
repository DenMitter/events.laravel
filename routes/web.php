<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
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

Route::get('/', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{showId}', [ShowController::class, 'show'])->name('shows.show');
Route::get('/events/{eventId}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{eventId}/reserve', [EventController::class, 'reserve'])->name('events.reserve');