<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostApproved;
use App\Notifications\PostRejected;
use App\Notifications\PostEditRequested;
use App\Notifications\PostDeleted;
use Illuminate\Notifications\Notifiable;


class AdminPostController extends Controller
{
    // 1. Hiển thị danh sách bài chờ duyệt
    public function pending()
    {
        $posts = Post::whereIn('status', ['bản nháp', 'yêu cầu duyệt'])->paginate(10);
        return view('admin.posts.pending', compact('posts'));
    }

    // 2. Phê duyệt bài viết
    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'đã duyệt';
        $post->admin_note = null;
        $post->save();

        // Gửi thông báo cho user
        if ($post->user) {
            $post->user->notify(new PostApproved($post));
        }

        return redirect()->back()->with('success', 'Đã duyệt bài viết và gửi thông báo cho người dùng.');
    }

    // 3. Từ chối bài viết
    public function reject(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
        ]);
    
        $post             = Post::findOrFail($id);
        $post->status     = 'bị từ chối';
        $post->admin_note = $request->note;
        $post->save();
    
        // Gửi thông báo cho user
        if ($post->user) {
            $post->user->notify(new PostRejected($post, $request->note));
        }
    
        return redirect()->back()->with('error', 'Bài viết đã bị từ chối và người dùng đã nhận được thông báo.');
    }

    // 4. Gửi yêu cầu chỉnh sửa
    public function requestEdit(Request $request, $id)
{
    $request->validate([
        'note' => 'required|string',
    ]);

    $post             = Post::findOrFail($id);
    $post->status     = 'yêu cầu chỉnh sửa';
    $post->admin_note = $request->note;
    $post->save();

    if ($post->user) {
        $post->user->notify(new PostEditRequested($request->note));
    }

    return redirect()->back()->with('info', 'Đã gửi yêu cầu chỉnh sửa và người dùng đã được thông báo.');
}

    // 5. Xoá bài đã duyệt nếu vi phạm
    public function destroy($id)
{
    $post = Post::findOrFail($id);

    if ($post->status === 'đã duyệt') {
        if ($post->user) {
            $post->user->notify(new PostDeleted());
        }

        $post->delete();
        return redirect()->back()->with('success', 'Bài viết đã bị xoá và người dùng đã được thông báo.');
    }

    return redirect()->back()->with('error', 'Chỉ có thể xoá bài đã duyệt.');
}

    public function show($id)
{
    $post = Post::findOrFail($id);
    return view('admin.posts.show', compact('post'));
}

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->with(['user', 'media'])->get();
        return view('admin.quanlybainguongudung', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string',
            'scheduled_at' => 'nullable|date'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->status;
        $post->scheduled_at = $request->scheduled_at;
        $post->user_id = auth()->id();
        $post->save();

        return redirect()->back()->with('success', 'Bài viết đã được tạo thành công.');
    }
}
