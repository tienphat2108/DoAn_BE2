<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostHistoryController extends Controller
{
    public function index()
    {
        $histories = PostHistory::with(['post', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.post-history', compact('histories'));
    }

    public function filter(Request $request)
    {
        $query = PostHistory::with(['post', 'user']);
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $histories = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.post-history', compact('histories'));
    }
} 