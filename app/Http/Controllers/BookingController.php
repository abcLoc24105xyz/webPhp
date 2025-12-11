<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Reservation;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function summary()
    {
        $booking = session('booking');

        if (!$booking || empty($booking['seats'])) {
            return redirect()->route('home')->with('error', 'Không có thông tin đặt vé!');
        }

        $show = Show::with(['movie', 'cinema', 'room'])->findOrFail($booking['show_id']);

        if (!session('temp_booking_code')) {
            do {
                $tempCode = date('y') . Str::upper(Str::random(8));
            } while (Reservation::where('booking_code', $tempCode)->exists());

            session(['temp_booking_code' => $tempCode]);
        }

        $this->recalculateTotals();

        return view('booking.summary', compact('show', 'booking'));
    }

    public function detail($bookingCode)
    {
        $reservation = Reservation::with(['show.movie', 'show.cinema', 'show.room', 'seats', 'combos'])
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('booking.detail', compact('reservation'));
    }

    public function history()
    {
        $reservations = Reservation::with(['show.movie', 'show.cinema'])
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->paginate(10);

        return view('booking.history', compact('reservations'));
    }

    /**
     * ✅ ÁP DỤNG MÃ GIẢM GIÁ - KIỂM TRA USER ĐÃ DÙNG CHƯA
     */
    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string|max:50'
        ]);

        $code    = strtoupper(trim($request->promo_code));
        $userId  = Auth::id();
        $booking = session('booking');

        if (!$booking || empty($booking['seats'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đặt vé!'
            ], 400);
        }

        // LẤY MÃ GIẢM GIÁ
        $promo = Promocode::where('promo_code', $code)->active()->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn!'
            ], 400);
        }

        // ✅ KIỂM TRA USER ĐÃ DÙNG MÃ NÀY CHƯA
        if ($promo->isUsedByUser($userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng mã này rồi! Mỗi tài khoản chỉ được dùng mã này 1 lần.'
            ], 400);
        }

        // KIỂM TRA ĐIỀU KIỆN ĐƠN HÀNG
        $seatTotal  = $booking['total'] ?? 0;
        $comboTotal = collect($booking['combos'] ?? [])->sum(fn($c) => ($c['quantity'] ?? 1) * ($c['price'] ?? 0));
        $subTotal   = $seatTotal + $comboTotal;

        if ($promo->min_order_value > 0 && $subTotal < $promo->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($promo->min_order_value) . 'đ trở lên để sử dụng mã này!'
            ], 400);
        }

        // TÍNH DISCOUNT
        $discount = $promo->calculateDiscount($subTotal);
        $grandTotal = max(0, $subTotal - $discount);

        session([
            'applied_promo'       => $promo->promo_code,
            'discount_amount'     => $discount,
            'booking.grand_total' => $grandTotal,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => "Áp dụng mã {$promo->promo_code} thành công!",
            'discount'    => number_format($discount) . 'đ',
            'grand_total' => number_format($grandTotal) . 'đ',
        ]);
    }

    public function removePromo(Request $request)
    {
        $request->session()->forget(['applied_promo', 'discount_amount']);
        $this->recalculateTotals();

        return response()->json([
            'success'   => true,
            'message'   => 'Đã xóa mã giảm giá thành công!',
            'new_total' => number_format(session('booking.grand_total', 0)) . 'đ'
        ]);
    }

    private function recalculateTotals()
    {
        $booking = session('booking', []);

        $seatTotal  = $booking['total'] ?? 0;
        $comboTotal = collect($booking['combos'] ?? [])->sum(fn($c) => ($c['quantity'] ?? 1) * ($c['price'] ?? 0));
        $discount   = session('discount_amount', 0);

        $grandTotal = max(0, $seatTotal + $comboTotal - $discount);

        session(['booking.grand_total' => $grandTotal]);
    }
}