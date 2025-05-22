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
        $query = Post::with(['user'])
            ->withCount(['likes', 'comments']);

        // Lọc theo người dùng
        if ($request->has('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
        }

        // Lọc theo khoảng thời gian
        if ($request->has('time_range')) {
            switch ($request->time_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'likes':
                $query->orderByDesc('likes_count');
                break;
            case 'comments':
                $query->orderByDesc('comments_count');
                break;
            case 'shares':
                $query->orderByDesc('shares_count');
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $posts = $query->paginate(10);
        $users = User::all();

        return view('admin.quanlytuongtac', compact('posts', 'users'));
    }
}