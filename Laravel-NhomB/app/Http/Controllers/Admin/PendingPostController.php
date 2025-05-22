<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PostApproved;
use App\Notifications\PostRejected;

class PendingPostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'media'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.posts.pending', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with(['user', 'media', 'comments.user'])->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'approved';
        $post->save();

        // Ghi lịch sử duyệt bài viết
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'approve',
            'details' => 'Bài viết đã được duyệt'
        ]);

        // Gửi thông báo cho người dùng
        if ($post->user) {
            $post->user->notify(new PostApproved($post));
        }

        return redirect()->back()->with('success', 'Bài viết đã được duyệt thành công.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:500'
        ]);

        $post = Post::findOrFail($id);
        $post->status = 'rejected';
        $post->admin_note = $request->reject_reason;
        $post->save();

        // Ghi lịch sử từ chối bài viết
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'reject',
            'details' => 'Bài viết bị từ chối: ' . $request->reject_reason
        ]);

        // Gửi thông báo cho người dùng
        if ($post->user) {
            $post->user->notify(new PostRejected($post, $request->reject_reason));
        }

        return redirect()->back()->with('success', 'Bài viết đã bị từ chối.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Ghi lịch sử xóa bài viết
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'action' => 'delete',
            'details' => 'Bài viết đã bị xóa bởi admin'
        ]);

        $post->delete();
        return redirect()->back()->with('success', 'Bài viết đã được xóa thành công.');
    }
} 