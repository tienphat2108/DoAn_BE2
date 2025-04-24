<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function pending()
    {
        $posts = Post::whereIn('status', ['bản nháp', 'yêu cầu duyệt'])->paginate(10);
        return view('admin.posts.pending', compact('posts'));
    }

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post->update($request->all());
        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->status == 'đã duyệt') {
            $post->delete();
            return redirect()->back()->with('success', 'Bài đã duyệt bị xoá vì vi phạm.');
        }

        return redirect()->back()->with('error', 'Không thể xoá bài chưa duyệt.');
    }

    public function approve($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'đã duyệt';
        $post->save();

        // Optionally gửi thông báo về cho người đăng
        return redirect()->back()->with('success', 'Bài viết đã được duyệt.');
    }

    public function reject($id)
    {
        $post         = Post::findOrFail($id);
        $post->status = 'bị từ chối';
        $post->save();

        return redirect()->back()->with('error', 'Đã từ chối bài viết.');
    }

    public function requestEdit(Request $request, $id)
    {
        $post             = Post::findOrFail($id);
        $post->status     = 'yêu cầu chỉnh sửa';
        $post->admin_note = $request->input('note');
        $post->save();

        return redirect()->back()->with('info', 'Đã gửi yêu cầu chỉnh sửa.');
    }
}
