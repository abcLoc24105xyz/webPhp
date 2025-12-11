<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;           // ← ĐÃ THÊM DÒNG NÀY
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ← ĐÃ THÊM DÒNG NÀY

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request) // ← ĐÃ CÓ Request
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
            // Xóa ảnh cũ nếu có
            if ($user->ava) {
                Storage::disk('public')->delete($user->ava); // ← ĐÃ CÓ Storage
            }
            $path = $request->file('ava')->store('avatars', 'public');
            $user->ava = $path;
        }

        $user->save();

        return redirect()->route('profile.show')
                         ->with('success', 'Cập nhật thông tin thành công!');
    }

    public function history()
    {
        $bookings = Auth::user()->reservations()
            ->with([
                'show.movie',
                'show.cinema',
                'show.room',
                'seats' // ĐÃ ĐỔI THÀNH seats → HOẠT ĐỘNG NGON LÀNH!
            ])
            ->latest('created_at')
            ->paginate(10);

        return view('profile.history', compact('bookings'));
    }

    public function ticketDetail($booking_code)
    {
        $booking = Auth::user()->reservations()
            ->with([
                'show.movie',
                'show.cinema',
                'show.room',
                'seats',           // ĐÃ ĐỔI
                'combos'
            ])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('profile.ticket-detail', compact('booking'));
    }
}