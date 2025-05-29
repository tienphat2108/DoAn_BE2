<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostEditRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PostHistory;

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
        $post->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
        return redirect()->back()->with('success', 'Bài viết đã được duyệt thành công');
    }

    public function reject(Post $post)
    {
        $post->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Bài viết đã bị từ chối');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $post = Post::lockForUpdate()->find($id);

            if (!$post) {
                DB::rollBack();
                return redirect()->route('admin.baichoduyet')->with('error', 'Bài viết đã bị xóa ở nơi khác. Vui lòng tải lại trang.');
            }

            if ($post->status === 'approved' || $post->status === 'pending') {
                // Ghi lịch sử xóa bài viết
                PostHistory::create([
                    'post_id' => $post->id,
                    'user_id' => Auth::id(),
                    'action' => 'delete',
                    'details' => 'Bài viết đã bị xóa bởi admin'
                ]);

                $post->delete();
                DB::commit();
                return redirect()->route('admin.baichoduyet')->with('success', 'Bài viết đã được xóa thành công');
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Chỉ có thể xóa bài viết đang chờ duyệt hoặc đã duyệt');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi xóa bài viết: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa bài viết. Vui lòng thử lại.');
        }
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

    public function getApprovalStats(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        Log::info('Ngày lọc: ' . $date);

        // Thống kê theo ngày
        $dailyStats = Post::where('status', 'approved')
            ->whereDate('approved_at', $date)
            ->count();

        // Thống kê theo khoảng thời gian
        $rangeStats = Post::where('status', 'approved')
            ->whereDate('approved_at', '>=', $startDate)
            ->whereDate('approved_at', '<=', $endDate)
            ->count();

        // Thống kê chi tiết theo ngày trong khoảng thời gian
        $detailedStats = Post::where('status', 'approved')
            ->whereDate('approved_at', '>=', $startDate)
            ->whereDate('approved_at', '<=', $endDate)
            ->selectRaw('DATE(approved_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        return response()->json([
            'date_input' => $date,
            'daily_count' => $dailyStats,
            'range_count' => $rangeStats,
            'detailed_stats' => $detailedStats
        ]);
    }
} 