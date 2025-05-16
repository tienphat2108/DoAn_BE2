<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Tạm thời return view trống, sau này sẽ thêm logic phân tích
        return view('admin.phantichtruycap');
    }
} 