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
        $query = Post::with(['user', 'likes', 'postComments'])
            ->select('posts.*')
            ->selectRaw('COUNT(DISTINCT post_likes.like_id) as likes_count')
            ->selectRaw('COUNT(DISTINCT post_comments.id) as comments_count')
            ->leftJoin('post_likes', 'posts.id', '=', 'post_likes.post_id')
            ->leftJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')
            ->groupBy('posts.id');

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
        
        $posts = $query->orderBy($sortBy, $sortOrder)->paginate(10);
        $users = User::all();

        return view('admin.interactions', compact('posts', 'users'));
    }
}