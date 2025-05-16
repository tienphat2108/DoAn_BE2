<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('media')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.quanlybainguoidung', compact('posts'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
        return back()->with('success', 'Đã duyệt bài viết thành công.');
    }

    public function reject(Request $request, Post $post)
    {
        $request->validate([
            'note' => 'required|string',
        ]);
        $post->update([
            'status' => 'rejected',
            'admin_note' => $request->note
        ]);
        return back()->with('error', 'Bài viết đã bị từ chối.');
    }

    public function requestEdit(Request $request, Post $post)
    {
        $request->validate([
            'edit_reason' => 'required|string|min:10'
        ]);
        $post->update([
            'status' => 'needs_edit',
            'edit_reason' => $request->edit_reason
        ]);
        return back()->with('info', 'Đã gửi yêu cầu chỉnh sửa bài viết.');
    }

    public function destroy(Post $post)
    {
        if ($post->status === 'approved') {
            $post->delete();
            return back()->with('success', 'Đã xóa bài viết thành công.');
        }
        return back()->with('error', 'Chỉ có thể xóa bài viết đã được duyệt.');
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
        $posts = Post::where('status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
        return view('admin.lichdangbai', compact('posts'));
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

    public function sendToPending(Post $post)
    {
        if ($post->status === 'waiting') {
            $post->update(['status' => 'pending']);
            return back()->with('success', 'Bài viết đã chuyển sang chờ duyệt.');
        }
        return back()->with('error', 'Chỉ có thể gửi bài ở trạng thái yêu cầu duyệt.');
    }
} 