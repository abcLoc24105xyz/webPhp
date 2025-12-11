<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileEditController extends Controller
{
    // ĐÃ BỎ __construct() → KHÔNG CÒN MIDDLEWARE TRONG CONTROLLER!

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:15',
            'ava'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user->full_name = $request->full_name;
        $user->phone = $request->phone;

        if ($request->hasFile('ava')) {
            if ($user->ava) {
                Storage::disk('public')->delete($user->ava);
            }
            $path = $request->file('ava')->store('avatars', 'public');
            $user->ava = $path;
        }

        $user->save();

        return redirect()->route('profile.show')
                         ->with('success', 'Cập nhật thông tin thành công!');
    }
}