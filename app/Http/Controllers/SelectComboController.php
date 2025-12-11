<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SelectComboController extends Controller
{
    public function show()
    {
        $booking = session('booking');

        // Kiểm tra session booking + ghế đã chọn
        if (!$booking || empty($booking['seats']) || empty($booking['show_id'])) {
            return redirect()->route('home')
                ->with('error', 'Vui lòng chọn ghế trước khi chọn combo!');
        }

        // LẤY SHOW VỚI ĐỦ QUAN HỆ + BẢO VỆ LỖI
        $show = Show::with(['movie', 'cinema', 'room'])
                    ->find($booking['show_id']);

        if (!$show) {
            session()->forget('booking');
            return redirect()->route('home')
                ->with('error', 'Suất chiếu không tồn tại hoặc đã bị xóa!');
        }

        $combos = Combo::where('status', 1)
                       ->orderBy('combo_name')
                       ->get();

        return view('booking.combo', compact('booking', 'show', 'combos'));
    }

    public function store(Request $request)
    {
        $booking = session('booking');

        // Kiểm tra lại booking tồn tại
        if (!$booking || empty($booking['seats']) || empty($booking['show_id'])) {
            return redirect()->route('home')
                ->with('error', 'Phiên đặt vé đã hết hạn hoặc không hợp lệ!');
        }

        // Validate dữ liệu combo
        $request->validate([
            'combos'         => 'nullable|array',
            'combos.*'       => 'integer|min:0|max:20'
        ]);

        $selectedCombos = [];
        $comboTotal     = 0;

        if ($request->filled('combos')) {
            foreach ($request->combos as $comboId => $qty) {
                $qty = (int) $qty;
                if ($qty <= 0) continue;

                $combo = Combo::find($comboId);

                // Kiểm tra combo tồn tại + đang hoạt động
                if (!$combo || $combo->status != 1) {
                    continue; // Bỏ qua combo không hợp lệ
                }

                $itemTotal = $combo->price * $qty;
                $comboTotal += $itemTotal;

                $selectedCombos[] = [
                    'id'         => $combo->combo_id,
                    'name'       => $combo->combo_name,
                    'image'      => $combo->image,
                    'price'      => (int) $combo->price,
                    'unit_price' => (int) $combo->price,
                    'quantity'   => $qty,
                    'total'      => $itemTotal,
                ];
            }
        }

        // CẬP NHẬT LẠI SESSION BOOKING - ĐẦY ĐỦ VÀ CHUẨN
        $booking['combos']       = $selectedCombos;
        $booking['combo_total']  = $comboTotal;
        $booking['grand_total']  = ($booking['total'] ?? 0) + $comboTotal;

        // Ghi lại session
        session(['booking' => $booking]);

        return redirect()->route('booking.summary')
                         ->with('success', 'Đã chọn combo thành công! Tiếp tục xác nhận đơn hàng nhé!');
    }
}