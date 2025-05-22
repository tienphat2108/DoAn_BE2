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
            $posts = Post::with(['user', 'media', 'likes', 'comments.user', 'views'])
                        ->where('status', 'approved')
                        ->latest()
                        ->get();
            // Tăng lượt xem cho mỗi bài viết
            foreach ($posts as $post) {
                // Kiểm tra nếu user đã xem bài này trong session thì không tăng nữa (chống spam F5)
                $viewedKey = 'viewed_post_' . $post->id;
                if (!session()->has($viewedKey)) {
                    $post->views()->create([
                        'user_id' => Auth::id(),
                    ]);
                    session()->put($viewedKey, true);
                }
            }
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
            $scheduledPosts = Post::where('user_id', $user->id)->where('status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
            $canceledPosts = Post::where('user_id', $user->id)->where('status', 'canceled')->orderBy('updated_at', 'desc')->get();
            $pendingPosts = Post::where('user_id', $user->id)->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        } else {
            $posts = collect();
            $scheduledPosts = collect();
            $canceledPosts = collect();
            $pendingPosts = collect();
        }
        return view('crud_trangchu.canhan', [
            'user' => $user,
            'posts' => $posts,
            'scheduledPosts' => $scheduledPosts,
            'canceledPosts' => $canceledPosts,
            'pendingPosts' => $pendingPosts,
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

    public function scheduleMulti(Request $request)
    {
        $user = Auth::user();
        $postIds = $request->input('post_ids', []);
        $scheduledAts = $request->input('scheduled_at', []);
        $count = 0;
        foreach ($postIds as $postId) {
            $post = Post::where('id', $postId)->where('user_id', $user->id)->first();
            if ($post && !empty($scheduledAts[$postId])) {
                $post->scheduled_at = $scheduledAts[$postId];
                $post->status = 'scheduled';
                $post->save();
                $count++;
            }
        }
        return redirect()->route('canhan')->with('success', 'Đã lên lịch cho ' . $count . ' bài viết!');
    }
}