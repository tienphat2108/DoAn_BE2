<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class AdminController extends Controller
{
    public function baidaduyetIndex() {
        $posts = Post::where('status', 'approved')->get(); // hoặc 'published' tùy logic
        return view('admin.baidaduyet', compact('posts'));
    }
}
