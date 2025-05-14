<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Lấy danh sách bài viết với thông tin người dùng và media
    public function index()
    {
        $posts = Post::with(['user', 'media', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('crud_trangchu.trangchu', compact('posts'));
    }

    // Danh sách bài viết chờ duyệt hoặc bản nháp
    public function pending()
    {
        $posts = Post::whereIn('status', ['bản nháp', 'yêu cầu duyệt'])->paginate(10);
        return view('admin.posts.pending', compact('posts'));
    }

    // Hiển thị form tạo bài viết
    public function create()
    {
        return view('posts.create');
    }

    // Lưu bài viết mới
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

<<<<<<< HEAD
    // Hiển thị form chỉnh sửa bài viết
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);
        $post->update($request->all());
        return redirect()->route('posts.index');
    }

    // Xoá bài viết
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->status == 'đã duyệt') {
            $post->delete();
            return redirect()->back()->with('success', 'Bài đã duyệt bị xoá vì vi phạm.');
        }
        return redirect()->back()->with('error', 'Không thể xoá bài chưa duyệt.');
    }

    // Duyệt bài viết
    public function approve($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'đã duyệt';
        $post->save();
        // Optionally gửi thông báo về cho người đăng
        return redirect()->back()->with('success', 'Bài viết đã được duyệt.');
    }

    // Từ chối bài viết
    public function reject($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'bị từ chối';
        $post->save();
        return redirect()->back()->with('error', 'Đã từ chối bài viết.');
    }

    // Yêu cầu chỉnh sửa bài viết
    public function requestEdit(Request $request, $id)
    {
        $post             = Post::findOrFail($id);
        $post->status     = 'yêu cầu chỉnh sửa';
        $post->admin_note = $request->input('note');
        $post->save();
        return redirect()->back()->with('info', 'Đã gửi yêu cầu chỉnh sửa.');
    }
}
=======
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        // Nếu muốn kiểm tra quyền xóa, thêm ở đây (ví dụ: chỉ cho xóa bài của mình)
        // if (auth()->id() !== $post->user_id) abort(403);

        $post->delete();

        // Nếu là request AJAX (fetch), trả về JSON
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // Nếu là request thường, redirect về trang chủ
        return redirect()->route('trangchu')->with('success', 'Xóa bài viết thành công!');
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $post->title = $request->input('title');
        $post->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'title' => $post->title]);
        }
        return redirect()->route('trangchu')->with('success', 'Cập nhật thành công!');
    }
} 
>>>>>>> DoTienPhat
