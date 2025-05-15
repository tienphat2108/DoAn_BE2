<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index() {
        // Lấy dữ liệu bình luận từ DB nếu cần
        // $comments = Comment::all();
        return view('admin.quanlybinhluan'/*, compact('comments')*/);
    }
  
}  