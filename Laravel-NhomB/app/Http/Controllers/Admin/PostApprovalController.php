<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostEditRequest;

class PostApprovalController extends Controller
{
    public function index()
    {
        $pendingPosts = Post::where('status', 'pending')
            ->with(['user', 'media'])
            ->latest()
            ->paginate(10);

        return view('admin.posts.pending', compact('pendingPosts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'media', 'comments.user']);
        return view('admin.posts.show', compact('post'));
    }

    public function approve(Post $post)
    {
        $post->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Bài viết đã được duyệt thành công');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Bài viết đã bị từ chối');
    }

    public function destroy(Post $post)
    {
        if ($post->status === 'approved') {
            $post->delete();
            return redirect()->back()->with('success', 'Bài viết đã được xóa thành công');
        }
        return redirect()->back()->with('error', 'Chỉ có thể xóa bài viết đã được duyệt');
    }

    public function requestEdit(Request $request, Post $post)
    {
        $request->validate([
            'edit_reason' => 'required|string|min:10'
        ]);

        // Gửi email thông báo cho người dùng
        Mail::to($post->user->email)->send(new PostEditRequest($post, $request->edit_reason));

        // Cập nhật trạng thái bài viết
        $post->update([
            'status' => 'needs_edit',
            'edit_reason' => $request->edit_reason
        ]);

        return redirect()->back()->with('success', 'Yêu cầu chỉnh sửa đã được gửi đến người dùng');
    }
} 