<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài viết với thông tin người dùng và media
        $posts = Post::with(['user', 'media', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('crud_trangchu.trangchu', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4|max:10240',
        ]);
        $user = Auth::user();
        if (!$request->hasFile('media')) {
            return back()->withErrors(['media' => 'Bài viết phải có ít nhất một hình ảnh!'])->withInput();
        }
        $post = Post::create([
            'title' => $request->title,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        // Lưu media nếu có
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $type = $file->getClientMimeType();
                Media::create([
                    'post_id' => $post->id,
                    'file_url' => $path,
                    'file_type' => $type,
                ]);
            }
        }
        // Redirect
        return redirect()->route('trangchu')->with('success', 'Đăng bài thành công!');
    }
} 