<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostHistory;
use Illuminate\Http\Request;

class PostHistoryController extends Controller
{
    public function index()
    {
        $histories = PostHistory::with(['post', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.quanlylichdangbai', compact('histories'));
    }

    public function filter(Request $request)
    {
        $query = PostHistory::with(['post', 'user']);

        if ($request->action) {
            $query->where('action', $request->action);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->search) {
            $query->whereHas('post', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($request->ajax()) {
            return response()->json($histories);
        }

        return view('admin.quanlylichdangbai', compact('histories'));
    }
} 