<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'likes', 'comments'])
            ->select('posts.*')
            ->selectRaw('COUNT(DISTINCT post_likes.id) as likes_count')
            ->selectRaw('COUNT(DISTINCT comments.id) as comments_count')
            ->leftJoin('post_likes', 'posts.id', '=', 'post_likes.post_id')
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->groupBy('posts.id', 'posts.user_id', 'posts.title', 'posts.content', 'posts.latitude', 'posts.longitude', 'posts.status', 'posts.scheduled_at', 'posts.shares_count', 'posts.created_at', 'posts.updated_at');

        // Lọc theo người dùng
        if ($request->has('user_id') && $request->user_id != 'all') {
            $query->where('posts.user_id', $request->user_id);
        }

        // Lọc theo khoảng thời gian
        if ($request->has('time_range')) {
            switch ($request->time_range) {
                case 'today':
                    $query->whereDate('posts.created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('posts.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('posts.created_at', now()->month)
                          ->whereYear('posts.created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('posts.created_at', now()->year);
                    break;
            }
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'likes':
                $query->orderBy('likes_count', $sortOrder);
                break;
            case 'comments':
                $query->orderBy('comments_count', $sortOrder);
                break;
            case 'shares':
                $query->orderBy('shares_count', $sortOrder);
                break;
            default:
                $query->orderBy('posts.created_at', $sortOrder);
        }

        $posts = $query->paginate(10);
        $users = User::all();

        return view('admin.quanlytuongtac', compact('posts', 'users'));
    }
}