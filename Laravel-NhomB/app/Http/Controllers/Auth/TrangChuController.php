<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                        ->where('status', 'approved')
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

    public function canhan()
    {
        $user = Auth::user();
        if (Schema::hasTable('posts')) {
            $posts = Post::with(['user', 'media', 'likes', 'comments.user'])
                        ->where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->latest()
                        ->get();
        } else {
            $posts = collect();
        }
        return view('crud_trangchu.canhan', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = Auth::user();
        $file = $request->file('avatar');
        $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');
        $user->avatar_url = 'storage/' . $path;
        $user->save();
        return redirect()->route('canhan')->with('success', 'Đổi avatar thành công!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('canhan')->with('success', 'Đổi mật khẩu thành công!');
    }
}