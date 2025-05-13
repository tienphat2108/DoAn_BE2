<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TrangChuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\View\View;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\AdminCommentController;

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
    Route::get('/canhan', [TrangChuController::class, 'canhan'])->name('canhan');
    Route::post('/canhan/avatar', [TrangChuController::class, 'updateAvatar'])->name('canhan.avatar');
    Route::post('/canhan/password', [TrangChuController::class, 'updatePassword'])->name('canhan.password');
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
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

// Thông báo người dùng
// Route::get('/notifications', function () {
//     return view('user.notifications', ['notifications' => auth()->user()->notifications]);
// })->middleware('auth');
Route::get('/admin/posts/pending', [PostController::class, 'pending']);

// Route quản lý bài viết người dùng (CRUD cơ bản)
Route::resource('posts', PostController::class)->middleware('auth');

// Các route dành cho Admin
Route::prefix('admin/posts')->name('admin.posts.')->middleware('auth')->group(function () {
    Route::get('/pending', [AdminPostController::class, 'pending'])->name('pending');
    Route::get('/{id}', [AdminPostController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminPostController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminPostController::class, 'reject'])->name('reject');
    Route::post('/{id}/request-edit', [AdminPostController::class, 'requestEdit'])->name('requestEdit');
    Route::delete('/{id}', [AdminPostController::class, 'destroy'])->name('destroy');
});

Route::get('/admin/tuongtac', [InteractionController::class, 'index'])->name('admin.tuongtac');
Route::get('/admin/quanlybinhluan', [AdminCommentController::class, 'index'])->name('admin.quanlybinhluan');
Route::get('/admin/theodoiluotxem', function () {return view('admin.theodoiluotxem');})->name('admin.theodoiluotxem');
Route::get('/admin/xuatdulieu', function () {return view('admin.xuatdulieu');})->name('admin.xuatdulieu');