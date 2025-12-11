<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::orderBy('user_id', 'desc')->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    // Khóa tài khoản (status = 0)
    public function block(User $user)
    {
        $user->update(['status' => 0]);
        return back()->with('success', "Đã khóa tài khoản {$user->full_name}");
    }

    // Mở khóa tài khoản (status = 1)
    public function unblock(User $user)
    {
        $user->update(['status' => 1]);
        return back()->with('success', "Đã mở khóa tài khoản {$user->full_name}");
    }
}