<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TrangChuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AnalyticsController;

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

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/trangchu', [TrangChuController::class, 'index'])->name('trangchu');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý người dùng
    Route::get('/quanlynguoidung', [UserController::class, 'index'])->name('quanlynguoidung');
    Route::delete('/quanlynguoidung/{user}', [UserController::class, 'destroy'])->name('deleteUser');
    
    // Quản lý bài viết
    Route::get('/quanlybainguoidung', [PostController::class, 'index'])->name('quanlybainguoidung');
    Route::post('/quanlybainguoidung/{post}/approve', [PostController::class, 'approve'])->name('approvePost');
    Route::delete('/quanlybainguoidung/{post}', [PostController::class, 'destroy'])->name('deletePost');
    
    // Các trang khác
    Route::get('/baichoduyet', [PostController::class, 'pendingPosts'])->name('baichoduyet');
    Route::get('/baidaduyet', [PostController::class, 'approvedPosts'])->name('baidaduyet');
    Route::get('/lichdangbai', [PostController::class, 'postSchedule'])->name('lichdangbai');
    Route::get('/phantichtruycap', [AnalyticsController::class, 'index'])->name('phantichtruycap');
});

// Redirect root to login
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.quanlybainguoidung');
        }
        return redirect()->route('trangchu');
    }
    return redirect()->route('login');
}); 