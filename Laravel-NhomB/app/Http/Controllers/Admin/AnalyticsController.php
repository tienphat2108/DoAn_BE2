<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

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

    public function performanceReport()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic báo cáo hiệu suất
        return view('admin.baocaohieusuat');
    }

    public function sendNotification()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic gửi thông báo
        return view('admin.guithongbao');
    }
} 