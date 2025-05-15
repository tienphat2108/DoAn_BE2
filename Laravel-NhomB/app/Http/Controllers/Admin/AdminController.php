<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function baiduyet()
    {
        // Dữ liệu mẫu cho bảng 1
        $posts = [
            ['image' => 'img1.jpg', 'title' => 'Đẹp quá...', 'status' => 'Đã đăng', 'created_at' => '2025-03-19', 'action' => 'Theo dõi'],
            ['image' => 'img2.jpg', 'title' => 'Xinh quá đi...', 'status' => 'Đã đăng', 'created_at' => '2025-03-19', 'action' => 'Theo dõi'],
            ['image' => 'img3.jpg', 'title' => 'Cưng quá...', 'status' => 'Đã đăng', 'created_at' => '2025-03-19', 'action' => 'Theo dõi'],
            ['image' => 'img4.jpg', 'title' => 'Này hay nè...', 'status' => 'Đã đăng', 'created_at' => '2025-03-19', 'action' => 'Theo dõi'],
            ['image' => 'img5.jpg', 'title' => 'Drama đi mn...', 'status' => 'Đã đăng', 'created_at' => '2025-03-19', 'action' => 'Theo dõi'],
        ];

        // Dữ liệu mẫu cho bảng 2
        $categories = [
            ['topic' => 'xã hội', 'author' => 'messy', 'status' => 'Đã đăng', 'posted' => 33, 'pending' => 5],
            ['topic' => 'văn học', 'author' => 'tố hữu', 'status' => 'chờ duyệt', 'posted' => 2, 'pending' => 6],
            ['topic' => 'âm nhạc', 'author' => 'APT', 'status' => 'Yêu cầu duyệt', 'posted' => 12, 'pending' => 0],
        ];

        // Truyền dữ liệu sang view
        return view('admin.baiduyet', compact('posts', 'categories'));
    }
}
