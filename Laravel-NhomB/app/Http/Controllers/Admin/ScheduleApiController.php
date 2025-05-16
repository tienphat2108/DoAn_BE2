<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleApiController extends Controller
{
    // Lấy danh sách bài viết (giả lập)
    public function posts()
    {
        return response()->json([
            ['id' => 1, 'content' => 'Bài viết mẫu 1'],
            ['id' => 2, 'content' => 'Bài viết mẫu 2'],
        ]);
    }

    // Lấy lịch đăng bài (giả lập)
    public function schedule()
    {
        return response()->json([
            ['id' => 1, 'content' => 'Bài viết đã lên lịch', 'time' => now()->addHour()->toDateTimeString()],
        ]);
    }

    // Lấy lịch sử đăng bài (giả lập)
    public function history()
    {
        return response()->json([
            ['id' => 1, 'content' => 'Bài viết đã đăng', 'time' => now()->subDay()->toDateTimeString()],
        ]);
    }

    // Lưu nháp (mock)
    public function saveDraft(Request $request)
    {
        Log::info('Draft saved', $request->all());
        return response()->json(['message' => 'Draft saved']);
    }

    // Lên lịch bài viết (mock)
    public function addSchedule(Request $request)
    {
        Log::info('Scheduled post', $request->all());
        return response()->json(['message' => 'Scheduled']);
    }

    // Hủy lịch bài viết (mock)
    public function cancelSchedule($id)
    {
        Log::info('Canceled schedule', ['id' => $id]);
        return response()->json(['message' => 'Canceled']);
    }

    // Duyệt lịch đăng bài (mock)
    public function approveSchedule()
    {
        Log::info('Approved schedule');
        return response()->json(['message' => 'Approved']);
    }

    // Lên lịch hàng loạt (mock)
    public function bulkSchedule(Request $request)
    {
        Log::info('Bulk schedule', $request->all());
        return response()->json(['message' => 'Bulk scheduled']);
    }

    // Đăng bài ngay (mock)
    public function postNow(Request $request)
    {
        Log::info('Post now', $request->all());
        return response()->json(['message' => 'Posted now']);
    }
} 