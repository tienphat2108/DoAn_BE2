<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCommentController extends Controller
{
    public function index(Request $request) 
    {
        $query = Comment::with(['user', 'post']);

        // Lọc theo bài viết nếu có
        if ($request->filled('post_id') && $request->post_id != 'all') {
            $query->where('post_id', $request->post_id);
        }

        // Lọc theo người dùng nếu có
        if ($request->filled('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
        }

        // Tìm kiếm theo nội dung
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        // Log câu truy vấn SQL cuối cùng (chỉ trong môi trường debug)
        if (config('app.debug')) {
            DB::listen(function ($sql) {
                \Illuminate\Support\Facades\Log::info('AdminCommentController Query: ' . $sql->sql, $sql->bindings);
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->get();
        // Log số lượng bình luận được lấy
        \Illuminate\Support\Facades\Log::info('AdminCommentController fetching comments. Count: ' . $comments->count());

        $posts = Post::all();
        $users = User::all();

        return view('admin.quanlybinhluan', compact('comments', 'posts', 'users'));
    }

    /**
     * Xóa bình luận
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Bình luận đã được xóa thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa bình luận'
            ], 500);
        }
    }

    /**
     * Cập nhật bình luận
     */
    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->content = $request->content;
            $comment->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Bình luận đã được cập nhật thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật bình luận'
            ], 500);
        }
    }
}  