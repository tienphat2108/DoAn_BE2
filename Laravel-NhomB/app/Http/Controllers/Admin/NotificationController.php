<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.guithongbao', compact('users'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'method' => 'required|in:database'
        ], [
            'user_id.required' => 'Vui lòng chọn người nhận thông báo',
            'user_id.exists' => 'Người dùng không tồn tại',
            'message.required' => 'Vui lòng nhập nội dung thông báo',
            'message.max' => 'Nội dung thông báo không được vượt quá 1000 ký tự',
            'method.required' => 'Vui lòng chọn hình thức gửi thông báo',
            'method.in' => 'Hình thức gửi thông báo không hợp lệ'
        ]);

        if ($validator->fails()) {
            $users = User::where('is_admin', false)->get();
            return redirect()->back()->withErrors($validator)->withInput()->with(compact('users'));
        }

        $user = User::findOrFail($request->user_id);
        
        try {
            $user->notify(new AdminNotification($request->message, $request->method));
            return redirect()->back()->with('success', 'Thông báo đã được gửi thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi gửi thông báo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi thông báo. Vui lòng thử lại sau.');
        }
    }

    public function getNotificationHistory()
    {
        try {
            $notifications = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('type', AdminNotification::class)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    $data = json_decode($notification->data);
                    $user = User::find($notification->notifiable_id);
                    return [
                        'message' => $data->message,
                        'sent_at' => $notification->created_at,
                        'user' => $user ? $user->name : 'Người dùng không tồn tại',
                        'method' => $notification->type === AdminNotification::class ? 'Thông báo hệ thống' : 'Email'
                    ];
                });

            return response()->json($notifications);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy lịch sử thông báo: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi lấy lịch sử thông báo'], 500);
        }
    }
} 