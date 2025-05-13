<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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
// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý người dùng
    Route::get('/quanlynguoidung', [UserController::class, 'index'])->name('quanlynguoidung');
    Route::delete('/quanlynguoidung/{user}', [UserController::class, 'destroy'])->name('deleteUser');
});
