<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TrangChuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\PostHistoryController;
use Illuminate\View\View;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\PostApprovalController;

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
    // Route::get('/home', [TrangChuController::class, 'index'])->name('trangchu'); // Đã comment để tránh trùng tên
    Route::get('/trangchu', [TrangChuController::class, 'index'])->name('trangchu');
    Route::get('/canhan', [TrangChuController::class, 'canhan'])->name('canhan');
    Route::post('/canhan/avatar', [TrangChuController::class, 'updateAvatar'])->name('canhan.avatar');
    Route::post('/canhan/password', [TrangChuController::class, 'updatePassword'])->name('canhan.password');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/like', [PostController::class, 'unlike'])->name('posts.unlike');
    Route::post('/posts/{post}/report', [PostController::class, 'report'])->name('posts.report')->middleware('auth');
});

// Route bình luận cho user thường (phải đặt ngoài group admin)
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý người dùng
    Route::get('/quanlynguoidung', [UserController::class, 'index'])->name('quanlynguoidung');
    Route::delete('/quanlynguoidung/{user}', [UserController::class, 'destroy'])->name('deleteUser');
    
    // Quản lý bài viết
    Route::get('/quanlybainguoidung', [AdminPostController::class, 'index'])->name('quanlybainguoidung');
    Route::post('/quanlybainguoidung/{post}/approve', [AdminPostController::class, 'approve'])->name('approvePost');
    Route::delete('/quanlybainguoidung/{post}', [AdminPostController::class, 'destroy'])->name('deletePost');
    
    // Quản lý bài viết chờ duyệt
    Route::get('/pending-posts', [PostApprovalController::class, 'index'])->name('pending-posts');
    Route::post('/posts/{post}/approve', [PostApprovalController::class, 'approve'])->name('posts.approve');
    Route::post('/posts/{post}/reject', [PostApprovalController::class, 'reject'])->name('posts.reject');
    
    // Các trang khác
    Route::get('/baichoduyet', [AdminPostController::class, 'pendingPosts'])->name('baichoduyet');
    Route::get('/baidaduyet', [AdminPostController::class, 'approvedPosts'])->name('baidaduyet');
    Route::get('/lichdangbai', [AdminPostController::class, 'postSchedule'])->name('lichdangbai');
    Route::get('/phantichtruycap', [AnalyticsController::class, 'index'])->name('phantichtruycap');
    
    // Lịch sử đăng bài
    Route::get('/post-history', [PostHistoryController::class, 'index'])->name('post-history');
    Route::post('/api/post-history/filter', [PostHistoryController::class, 'filter'])->name('post-history.filter');
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

// Route quản lý bài viết người dùng (CRUD cơ bản)
Route::resource('posts', PostController::class)->middleware('auth');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/posts/{id}/share', [PostController::class, 'share'])->name('posts.share')->middleware('auth');
