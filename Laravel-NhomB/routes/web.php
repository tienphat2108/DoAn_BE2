<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\PostController;

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

// Đăng xuất
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Trang chính
Route::get('/', function () {
    return view('welcome');
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
