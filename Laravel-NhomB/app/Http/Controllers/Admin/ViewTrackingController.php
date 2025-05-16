<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ViewTrackingController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách tất cả bài viết để hiển thị trong dropdown
        $allPosts = Post::select('id', 'title')->get();

        // Lấy giá trị lọc từ request
        $postId = $request->input('post_id', 'all');
        $timeRange = $request->input('time_range', 'all');

        // Query bài viết cần thống kê
        $postsQuery = Post::select('posts.id', 'posts.title');
        if ($postId !== 'all') {
            $postsQuery->where('posts.id', $postId);
        }

        $postsQuery->withCount([
            'views as today_views' => function ($query) use ($timeRange) {
                if ($timeRange === 'today') {
                    $query->whereDate('created_at', today());
                }
            },
            'views as week_views' => function ($query) use ($timeRange) {
                if ($timeRange === 'week') {
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                }
            },
            'views as month_views' => function ($query) use ($timeRange) {
                if ($timeRange === 'month') {
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                }
            },
            'views as all_views' => function ($query) use ($timeRange) {
                if ($timeRange === 'all') {
                    // Không filter
                }
            },
        ]);

        $posts = $postsQuery->get();

        return view('admin.theodoiluotxem', [
            'posts' => $posts,
            'allPosts' => $allPosts,
            'selectedPostId' => $postId,
            'selectedTimeRange' => $timeRange,
        ]);
    }
} 