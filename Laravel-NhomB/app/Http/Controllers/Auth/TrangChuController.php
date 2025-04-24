<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TrangChuController extends Controller
{
    public function index()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
        
        // Kiểm tra xem bảng posts có tồn tại không
        if (Schema::hasTable('posts')) {
            // Lấy danh sách bài viết với các quan hệ
            $posts = Post::with(['user', 'media', 'likes', 'comments.user'])
                        ->latest()
                        ->get();
        } else {
            $posts = collect(); // Trả về collection rỗng nếu bảng chưa tồn tại
        }
        
        // Trả về view với dữ liệu
        return view('crud_trangchu.trangchu', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
}