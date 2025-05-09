<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function index()
    {
        $posts = [
            ['title' => 'Bài viết A', 'likes' => 150, 'comments' => 30, 'shares' => 20],
            ['title' => 'Bài viết B', 'likes' => 220, 'comments' => 50, 'shares' => 35],
            ['title' => 'Bài viết C', 'likes' => 300, 'comments' => 80, 'shares' => 45],
        ];
        return view('admin.quanlytuongtac', compact('posts'));
    }
}