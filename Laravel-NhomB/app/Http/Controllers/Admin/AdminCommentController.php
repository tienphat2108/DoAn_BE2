<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

        // Log data của bình luận đầu tiên để kiểm tra cấu trúc và ID (NEW LOG)
        if ($comments->count() > 0) {
            \Illuminate\Support\Facades\Log::info('First comment data:', $comments->first()->toArray());
        } else {
            \Illuminate\Support\Facades\Log::info('No comments fetched.');
        }

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
            $comment = Comment::where('comment_id', $id)->firstOrFail();
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

    /**
     * Xóa nhiều bình luận cùng lúc
     */
    public function bulkDelete(Request $request)
    {
        // Log the received comment_ids
        \Illuminate\Support\Facades\Log::info('Bulk delete requested for comment IDs: ' . json_encode($request->input('comment_ids')));

        $request->validate([
            'comment_ids' => 'required|array',
            // Sử dụng comment_id làm khóa chính trong validation
            'comment_ids.*' => 'exists:post_comments,comment_id',
        ]);

        $commentIds = $request->input('comment_ids');

        // Log comment IDs after validation
        \Illuminate\Support\Facades\Log::info('Bulk delete comment IDs after validation: ' . json_encode($commentIds));

        try {
            // Log comment IDs before delete query
            \Illuminate\Support\Facades\Log::info('Bulk delete processing comment IDs: ' . json_encode($commentIds));

            // Sử dụng comment_id làm khóa chính trong whereIn
            $deletedCount = Comment::whereIn('comment_id', $commentIds)->delete();

            // Log the result of the delete operation
            \Illuminate\Support\Facades\Log::info('Bulk delete result - Number of comments deleted: ' . $deletedCount);

            if ($deletedCount > 0) {
                Session::flash('success', 'Đã xóa thành công ' . $deletedCount . ' bình luận.');
            } else {
                // Log that no comments were deleted (optional, covered by $deletedCount log)
                \Illuminate\Support\Facades\Log::info('Bulk delete result - No comments were deleted.');
                Session::flash('error', 'Không có bình luận nào được xóa.');
            }

        } catch (\Exception $e) {
            Session::flash('error', 'Có lỗi xảy ra khi xóa bình luận: ' . $e->getMessage());
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Bulk delete comments failed: ' . $e->getMessage(), ['comment_ids' => $commentIds]);
        }

        // Chuyển hướng về trang quản lý bình luận, giữ lại các bộ lọc hiện tại
        $searchParams = $request->only(['search', 'post_id', 'user_id']);
        return redirect()->route('admin.quanlybinhluan', $searchParams);
    }
}  