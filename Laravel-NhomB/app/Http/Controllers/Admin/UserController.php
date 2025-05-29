<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.quanlynguoidung', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            // If user is not found, it might have been deleted elsewhere
             if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng đã bị xóa ở nơi khác. Vui lòng tải lại trang.'
                ], 404); // Use 404 Not Found status code
            }
            return back()->with('error', 'Người dùng đã bị xóa ở nơi khác. Vui lòng tải lại trang.');
        }

        if ($user->is_admin) {
             if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa tài khoản admin.'
                ], 403); // Use 403 Forbidden status code
            }
            return back()->with('error', 'Không thể xóa tài khoản admin.');
        }

        $user->delete();

        if (request()->expectsJson()) {
             return response()->json(['success' => true, 'message' => 'Đã xóa người dùng thành công.']);
        }
        return back()->with('success', 'Đã xóa người dùng thành công.');
    }
} 