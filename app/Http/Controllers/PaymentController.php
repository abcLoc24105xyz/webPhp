<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\ReservationCombo;
use App\Models\Promocode;
use App\Models\PromoUserUsage;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentController extends Controller
{
    public function momoPayment()
    {
        return redirect()->route('booking.summary');
    }

    public function createMomoPayment(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập!'], 401);
            }

            $booking    = session('booking');
            $tempCode   = session('temp_booking_code');

            if (!$booking || !is_array($booking) || empty($booking['seats']) || !$tempCode) {
                return response()->json(['success' => false, 'message' => 'Phiên hết hạn! Vui lòng chọn ghế lại.'], 400);
            }

            $amount = (int)($booking['grand_total'] ?? 0);

            // ==================== 0 ĐỒNG → TẠO ĐƠN paid NGAY, KHÔNG QUA MOMO ====================
            if ($amount <= 0) {
                $this->createZeroPaymentReservation($tempCode, $booking);
                return response()->json([
                    'success'      => true,
                    'zero_payment' => true,
                    'redirect_url' => route('booking.detail', $tempCode)
                ]);
            }

            // ==================== CÓ TIỀN → TẠO ĐƠN PENDING + GỌI MOMO ====================
            $existing = Reservation::where('booking_code', $tempCode)
                ->whereIn('status', ['pending', 'paid'])
                ->first();

            if ($existing && $existing->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'payUrl'  => route('booking.detail', $tempCode)
                ]);
            }

            if (!$existing) {
                $this->createPendingReservation($tempCode, $booking);
            }

            // Tạo link MoMo
            $orderId     = $tempCode;
            $requestId   = $tempCode . '_' . time();
            $orderInfo   = "Thanh toán vé phim - Mã: $orderId";
            $redirectUrl = route('momo.return');
            $ipnUrl      = route('momo.ipn');

            $partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
            $accessKey   = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
            $secretKey   = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');

            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=payWithATM";
            $signature = hash_hmac('sha256', $rawHash, $secretKey);

            $payload = [
                "partnerCode" => $partnerCode, "accessKey" => $accessKey, "requestId" => $requestId,
                "amount" => $amount, "orderId" => $orderId, "orderInfo" => $orderInfo,
                "redirectUrl" => $redirectUrl, "ipnUrl" => $ipnUrl, "lang" => "vi",
                "extraData" => "", "requestType" => "payWithATM", "signature" => $signature
            ];

            $response = $this->curlPost('https://test-payment.momo.vn/v2/gateway/api/create', $payload);
            $result   = json_decode($response, true);

            if (!$result || $result['resultCode'] != 0) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Lỗi MoMo'
                ], 400);
            }

            return response()->json(['success' => true, 'payUrl' => $result['payUrl']]);

        } catch (Exception $e) {
            Log::critical('MoMo Error', ['msg' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Hệ thống bận!'], 500);
        }
    }

    // ==================== CALLBACK ====================
    public function momoReturn(Request $request)
    {
        if ($request->resultCode == 0) {
            $this->confirmPaidReservation($request->orderId);
            return redirect()->route('booking.detail', $request->orderId)
                ->with('success', 'Thanh toán thành công!');
        }
        return redirect()->route('booking.summary')
            ->with('error', 'Thanh toán thất bại: ' . ($request->message ?? 'Lỗi không xác định'));
    }

    public function momoIpn(Request $request)
    {
        if ($request->resultCode == 0) {
            $this->confirmPaidReservation($request->orderId);
        }
        return response()->json(['ErrCode' => 0]);
    }

    // ==================== 0 ĐỒNG – TẠO ĐƠN paid NGAY ====================
    private function createZeroPaymentReservation($bookingCode, $booking)
    {
        if (Reservation::where('booking_code', $bookingCode)->where('status', 'paid')->exists()) {
            return;
        }

        DB::transaction(function () use ($bookingCode, $booking) {
            $show = Show::find($booking['show_id']);

            $reservation = Reservation::create([
                'booking_code'   => $bookingCode,
                'user_id'        => Auth::id(),
                'show_id'        => $booking['show_id'],
                'total_amount'   => 0,
                'status'         => 'paid',
                'payment_method' => 'free',
                'paid_at'        => now(),
            ]);

            foreach ($booking['seats'] ?? [] as $s) {
                ReservationSeat::create([
                    'booking_code' => $bookingCode,
                    'seat_id'      => $s['seat_id'],
                    'seat_price'   => $s['price'] ?? 0
                ]);
            }

            if (!empty($booking['combos'])) {
                foreach ($booking['combos'] as $c) {
                    ReservationCombo::create([
                        'booking_code' => $bookingCode,
                        'combo_id'     => $c['id'],
                        'quantity'     => $c['quantity'],
                        'combo_price'  => $c['price']
                    ]);
                }
            }

            // ✅ LƯU TRACKING MÃ GIẢM GIÁ NẾU CÓ
            $this->recordPromoUsage($reservation, $bookingCode);

            $show->decrement('remaining_seats', count($booking['seats']));

            $reservation->load(['show.movie', 'show.cinema', 'show.room']);
            $this->sendConfirmationEmail($reservation, $bookingCode);
        });

        session()->forget(['booking', 'temp_booking_code', 'applied_promo', 'discount_amount']);
    }

    // ==================== TẠO ĐƠN PENDING ====================
    private function createPendingReservation($bookingCode, $booking)
    {
        DB::transaction(function () use ($bookingCode, $booking) {
            $show = Show::find($booking['show_id']);

            Reservation::create([
                'booking_code'   => $bookingCode,
                'user_id'        => Auth::id(),
                'show_id'        => $booking['show_id'],
                'total_amount'   => $booking['grand_total'],
                'status'         => 'pending',
                'payment_method' => 'momo_atm',
                'payment_id'     => $bookingCode,
                'expires_at'     => now()->addMinutes(15),
            ]);

            foreach ($booking['seats'] ?? [] as $s) {
                ReservationSeat::create([
                    'booking_code' => $bookingCode,
                    'seat_id'      => $s['seat_id'],
                    'seat_price'   => $s['price'] ?? 0
                ]);
            }

            if (!empty($booking['combos'])) {
                foreach ($booking['combos'] as $c) {
                    ReservationCombo::create([
                        'booking_code' => $bookingCode,
                        'combo_id'     => $c['id'],
                        'quantity'     => $c['quantity'],
                        'combo_price'  => $c['price']
                    ]);
                }
            }

            $show->decrement('remaining_seats', count($booking['seats']));
        });
    }

    // ==================== XÁC NHẬN THANH TOÁN THÀNH CÔNG (MoMo) ====================
    private function confirmPaidReservation($bookingCode)
    {
        $reservation = Reservation::where('booking_code', $bookingCode)
                                  ->where('status', 'pending')
                                  ->first();

        if (!$reservation) return;

        DB::transaction(function () use ($reservation, $bookingCode) {
            $reservation->update([
                'status'  => 'paid',
                'paid_at' => now(),
                'expires_at' => null
            ]);

            // ✅ LƯU TRACKING MÃ GIẢM GIÁ NẾU CÓ
            $this->recordPromoUsage($reservation, $bookingCode);

            $reservation->load(['show.movie', 'show.cinema', 'show.room']);
            $this->sendConfirmationEmail($reservation, $bookingCode);
        });

        session()->forget(['booking', 'temp_booking_code', 'applied_promo', 'discount_amount']);
    }

    // ==================== ✅ LƯU TRACKING MÃ GIẢM GIÁ ====================
    private function recordPromoUsage($reservation, $bookingCode)
    {
        $promoCode = session('applied_promo');

        // Nếu không có mã hoặc đã lưu rồi thì bỏ qua
        if (!$promoCode) {
            return;
        }

        // Kiểm tra mã có tồn tại không
        $promo = Promocode::find($promoCode);
        if (!$promo) {
            return;
        }

        // Lưu tracking vào database (unique key sẽ chặn duplicate)
        try {
            PromoUserUsage::firstOrCreate(
                [
                    'promo_code' => $promoCode,
                    'user_id'    => $reservation->user_id,
                ],
                [
                    'booking_code' => $bookingCode
                ]
            );

            // Tăng used_count của mã
            $promo->increment('used_count');

            Log::info("Promo usage recorded: {$promoCode} by user {$reservation->user_id}");

        } catch (\Exception $e) {
            Log::warning('Promo usage tracking failed: ' . $e->getMessage());
        }
    }

    // ==================== GỬI EMAIL ====================
    private function sendConfirmationEmail($reservation, $bookingCode)
    {
        try {
            $user = Auth::user();
            if (!$user?->email) return;

            $seats = ReservationSeat::where('booking_code', $bookingCode)
                ->join('seats', 'reservation_seats.seat_id', '=', 'seats.seat_id')
                ->pluck('seats.seat_num')->toArray();

           $combos = ReservationCombo::where('booking_code', $bookingCode)
                ->join('combos', 'reservation_combos.combo_id', '=', 'combos.combo_id')
                ->get(['combos.combo_name', 'reservation_combos.quantity', 'reservation_combos.combo_price']);

            Mail::send('emails.booking-confirmation', [
                'user'        => $user,
                'reservation' => $reservation,
                'seats'       => $seats,
                'combos'      => $combos,
                'bookingCode' => $bookingCode,
                'qrCodeUrl'   => $this->generateQRCode($bookingCode),
                'detailLink'  => route('booking.detail', $bookingCode),
                'isFree'      => ($reservation->total_amount == 0)
            ], function ($m) use ($user) {
                $m->to($user->email)->subject('Xác nhận đặt vé thành công - Cinema Booking');
            });
        } catch (Exception $e) {
            Log::error('Email error: ' . $e->getMessage());
        }
    }

    private function generateQRCode($bookingCode)
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . urlencode(route('booking.detail', $bookingCode));
    }

    private function curlPost($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}