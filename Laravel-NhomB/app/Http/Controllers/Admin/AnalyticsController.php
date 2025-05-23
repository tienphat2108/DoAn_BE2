<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Exports\PostsExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PostView;
use App\Models\Comment;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic phân tích
        return view('admin.phantichtruycap');
    }

    public function viewTracking()
    {
        $allPosts = Post::all();
        $selectedPostId = null;
        $selectedTimeRange = null;
        $posts = [];
        return view('admin.theodoiluotxem', compact('allPosts', 'selectedPostId', 'selectedTimeRange', 'posts'));
    }

    public function exportData()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic xuất dữ liệu
        return view('admin.xuatdulieu');
    }

    public function export(Request $request)
    {
        $dataType = $request->query('type');
        $formatType = $request->query('format');

        switch ($dataType) {
            case 'posts':
                $exporter = new PostsExport();
                $filename = 'posts_' . date('Ymd_His') . '.' . $formatType;
                return Excel::download($exporter, $filename);
                break;
            case 'users':
                $exporter = new UsersExport();
                 $filename = 'users_' . date('Ymd_His') . '.' . $formatType;
                return Excel::download($exporter, $filename);
                break;
            default:
                return back()->with('error', 'Loại dữ liệu không hợp lệ.');
        }
    }

    public function performanceReport(Request $request)
    {
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        // Get current month's data
        $currentMonthStart = now()->setMonth($selectedMonth)->setYear($selectedYear)->startOfMonth();
        $currentMonthEnd = now()->setMonth($selectedMonth)->setYear($selectedYear)->endOfMonth();

        // Get previous month's data
        $previousMonthStart = $currentMonthStart->copy()->subMonth();
        $previousMonthEnd = $currentMonthEnd->copy()->subMonth();

        // Get views data
        $currentMonthViews = \App\Models\PostView::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $previousMonthViews = \App\Models\PostView::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $viewsChange = $previousMonthViews > 0 ? (($currentMonthViews - $previousMonthViews) / $previousMonthViews) * 100 : 0;

        // Get likes data
        $currentMonthLikes = \App\Models\Like::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $previousMonthLikes = \App\Models\Like::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $likesChange = $previousMonthLikes > 0 ? (($currentMonthLikes - $previousMonthLikes) / $previousMonthLikes) * 100 : 0;

        // Get shares data
        $currentMonthShares = \App\Models\Post::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->sum('shares_count');
        $previousMonthShares = \App\Models\Post::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->sum('shares_count');
        $sharesChange = $previousMonthShares > 0 ? (($currentMonthShares - $previousMonthShares) / $previousMonthShares) * 100 : 0;

        // Get comments data
        $currentMonthComments = \App\Models\Comment::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $previousMonthComments = \App\Models\Comment::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $commentsChange = $previousMonthComments > 0 ? (($currentMonthComments - $previousMonthComments) / $previousMonthComments) * 100 : 0;

        // Get new users data
        $currentMonthUsers = \App\Models\User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $previousMonthUsers = \App\Models\User::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $usersChange = $previousMonthUsers > 0 ? (($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100 : 0;

        $stats = [
            'views' => [
                'current' => $currentMonthViews,
                'previous' => $previousMonthViews,
                'change' => round($viewsChange, 1)
            ],
            'likes' => [
                'current' => $currentMonthLikes,
                'previous' => $previousMonthLikes,
                'change' => round($likesChange, 1)
            ],
            'shares' => [
                'current' => $currentMonthShares,
                'previous' => $previousMonthShares,
                'change' => round($sharesChange, 1)
            ],
            'comments' => [
                'current' => $currentMonthComments,
                'previous' => $previousMonthComments,
                'change' => round($commentsChange, 1)
            ],
            'users' => [
                'current' => $currentMonthUsers,
                'previous' => $previousMonthUsers,
                'change' => round($usersChange, 1)
            ]
        ];

        return view('admin.baocaohieusuat', compact('stats', 'selectedMonth', 'selectedYear'));
    }

    public function sendNotification()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic gửi thông báo
        return view('admin.guithongbao');
    }
} 