<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TrangChuController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::get('/canhan', [TrangChuController::class, 'canhan'])->name('canhan');
    Route::post('/canhan/avatar', [TrangChuController::class, 'updateAvatar'])->name('canhan.avatar');
    Route::post('/canhan/password', [TrangChuController::class, 'updatePassword'])->name('canhan.password');
});
