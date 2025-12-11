<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Promocode;
use App\Models\PromoUserUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;   // ← BẮT BUỘC
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'reservation.show.movie'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'reservation.show.movie', 'reservation.seats', 'reservation.combos']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Xác nhận thủ công thanh toán MoMo + LƯU TRACKING MÃ GIẢM GIÁ
     */
    public function confirm(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn hàng đang chờ!');
        }

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái thanh toán
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Cập nhật đặt chỗ
            $payment->reservation->update(['status' => 'confirmed']);

            // Lưu tracking mã giảm giá nếu có
            $this->recordPromoUsage($payment->reservation);

            DB::commit();

            return back()->with('success', 'Đã xác nhận thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    public function cancel(Request $request, Payment $payment)
    {
        if (!in_array($payment->status, ['pending', 'completed'])) {
            return back()->with('error', 'Không thể hủy trạng thái này!');
        }

        DB::beginTransaction();

        try {
            $payment->update(['status' => 'cancelled']);
            $payment->reservation->update(['status' => 'cancelled']);

            // Xóa tracking mã giảm giá
            PromoUserUsage::where('booking_code', $payment->reservation->booking_code)->delete();

            DB::commit();

            return back()->with('success', 'Đã hủy thanh toán và đặt chỗ!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    /**
     * HELPER: Lưu tracking mã giảm giá
     */
    private function recordPromoUsage($reservation)
    {
        // Ưu tiên lấy mã từ session
        $promoCode = Session::get('applied_promo');

        // Nếu có mã giảm giá
        if ($promoCode) {
            $promo = Promocode::find($promoCode);

            if (!$promo) {
                return;
            }

            try {
                PromoUserUsage::firstOrCreate(
                    [
                        'promo_code' => $promoCode,
                        'user_id' => $reservation->user_id,
                    ],
                    [
                        'booking_code' => $reservation->booking_code,
                    ]
                );

                // Tăng số lần dùng
                $promo->increment('used_count');
            } catch (\Exception $e) {
                Log::warning('Promo usage tracking failed: ' . $e->getMessage());
            }
        }
    }
}
