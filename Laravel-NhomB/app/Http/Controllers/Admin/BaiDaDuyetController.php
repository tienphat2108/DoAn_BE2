<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostEvaluation;

class BaiDaDuyetController extends Controller
{
    public function index() {
        // Lấy danh sách bài đã duyệt, ví dụ:
        $posts = Post::where('status', 'approved')->get(); // Bài đã duyệt
        $pendingPosts = Post::where('status', 'pending')->get(); // Bài chờ kiểm tra

        return view('admin.baidaduyet', compact('posts', 'pendingPosts'));
    }

    public function addComment(Request $request) {
        // Xử lý lưu comment ở đây
        // Ví dụ:
        // Comment::create([...]);
        return back()->with('success', 'Đã thêm nhận xét!');
    }

    public function show($id)
    {
        $post = Post::with(['evaluations.user'])->findOrFail($id);
        return view('admin.baidaduyet_show', compact('post'));
    }
}
