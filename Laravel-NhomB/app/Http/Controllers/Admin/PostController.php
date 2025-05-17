<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostHistory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('admin.quanlybainguoidung', compact('posts'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
        
        // Ghi lại lịch sử
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'action' => 'publish',
            'details' => 'Bài viết đã được duyệt và đăng'
        ]);
        
        return back()->with('success', 'Đã duyệt bài viết thành công.');
    }

    public function destroy(Post $post)
    {
        // Ghi lại lịch sử trước khi xóa
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'action' => 'cancel',
            'details' => 'Bài viết đã bị hủy'
        ]);
        
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết thành công.');
    }

    public function pendingPosts()
    {
        $posts = Post::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.pending', ['posts' => $posts]);
    }

    public function approvedPosts()
    {
        $posts = Post::where('status', 'approved')->orderBy('created_at', 'desc')->get();
        return view('admin.baidaduyet', compact('posts'));
    }

    public function postSchedule()
    {
        $scheduledPosts = Post::where('status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
        $histories = \App\Models\PostHistory::with(['post', 'user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.quanlylichdangbai', compact('scheduledPosts', 'histories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:pending,approved,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4|max:10240',
        ]);

        $validated['user_id'] = auth()->id();
        $post = Post::create($validated);

        // Ghi lại lịch sử
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'action' => 'create',
            'details' => 'Bài viết mới được tạo'
        ]);

        // Lưu media nếu có
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $type = $file->getClientMimeType();
                \App\Models\Media::create([
                    'post_id' => $post->id,
                    'file_url' => $path,
                    'file_type' => $type,
                ]);
            }
        }

        return redirect()->route('admin.quanlybainguoidung')
            ->with('success', 'Bài viết đã được tạo thành công.');
    }
} 