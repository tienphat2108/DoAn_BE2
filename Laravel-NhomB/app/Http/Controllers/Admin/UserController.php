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

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Không thể xóa tài khoản admin.');
        }

        $user->delete();
        return back()->with('success', 'Đã xóa người dùng thành công.');
    }
} 