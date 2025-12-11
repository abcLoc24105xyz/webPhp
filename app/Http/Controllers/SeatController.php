<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\SeatHold;
use App\Models\Seat;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SeatController extends Controller
{
    public function index($show_id)
    {
        $show = Show::with(['movie', 'cinema', 'room.seats'])->findOrFail($show_id);

        // Kiểm tra suất còn hiệu lực không
        // FIX: Parse đúng cách - show_date là DATE, end_time là TIME
        try {
            $showDate = $show->show_date;
            if (is_string($showDate)) {
                $showDate = preg_replace('/\s+.*$/', '', $showDate); // Bỏ giờ phút
            }
            
            $endTime = $show->end_time;
            if (is_string($endTime)) {
                $endTime = substr($endTime, 0, 5); // Lấy H:i (HH:MM)
                $endTime = $endTime . ':00'; // Thêm :00 → HH:MM:00
            }
            
            $showEndTime = Carbon::parse($showDate . ' ' . $endTime);
            
            if ($showEndTime->isPast()) {
                abort(404, 'Suất chiếu đã kết thúc');
            }
        } catch (\Exception $e) {
            // Nếu lỗi parse, bỏ qua check
        }

        // LẤY GHẾ ĐANG BỊ GIỮ (CÒN HẠN 10 PHÚT)
        $heldSeats = SeatHold::where('show_id', $show_id)
            ->where('expires_at', '>', now())
            ->pluck('seat_id');

        // LẤY GHẾ ĐÃ BÁN (THANH TOÁN THÀNH CÔNG)
        $soldSeats = ReservationSeat::whereHas('reservation', function ($q) use ($show_id) {
                $q->where('show_id', $show_id)
                  ->whereIn('status', ['paid', 'completed']);
            })
            ->pluck('seat_id');

        // GỘP LẠI: GHẾ KHÔNG THỂ CHỌN = ĐANG GIỮ + ĐÃ BÁN
        $unavailableSeats = $heldSeats->merge($soldSeats)->unique();

        $combos = Combo::where('status', 'active')->get();

        return view('booking.seat-selection', compact('show', 'unavailableSeats', 'combos'));
    }

    public function holdSeats(Request $request, $show_id)
    {
        
        if (!verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return response()->json([
                'success' => false,
                'message' => 'Xác minh thất bại. Vui lòng thử lại!'
            ], 429);
        }

        $request->validate([
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,seat_id',
        ]);

        $show = Show::findOrFail($show_id);
        $userId = Auth::check() ? Auth::id() : null;

        // XÓA GIỮ GHẾ CŨ CỦA USER NÀY
        SeatHold::where('show_id', $show_id)
            ->when($userId, fn($q) => $q->where('user_id', $userId), fn($q) => $q->whereNull('user_id'))
            ->delete();

        $requestedSeats = Seat::whereIn('seat_id', $request->seats)->get()->keyBy('seat_id');

        $total = 0;
        $seatDetails = [];

        foreach ($request->seats as $seatId) {
            $seat = $requestedSeats->get($seatId);
            if (!$seat) {
                continue;
            }

            // KIỂM TRA GHẾ CÓ BỊ GIỮ HOẶC ĐÃ BÁN CHƯA
            $isHeld = SeatHold::where('show_id', $show_id)
                ->where('seat_id', $seatId)
                ->where('expires_at', '>', now())
                ->exists();

            $isSold = ReservationSeat::whereHas('reservation', function ($q) use ($show_id) {
                    $q->where('show_id', $show_id)
                      ->whereIn('status', ['paid', 'completed']);
                })
                ->where('seat_id', $seatId)
                ->exists();

            if ($isHeld || $isSold) {
                return response()->json([
                    'success' => false,
                    'message' => "Ghế {$seat->seat_num} đã được đặt hoặc đang bị giữ!"
                ], 409);
            }

            // GIỮ GHẾ THÀNH CÔNG
            SeatHold::create([
                'show_id'    => $show_id,
                'seat_id'    => $seatId,
                'user_id'    => $userId,
                'expires_at' => now()->addMinutes(10),
            ]);

            $price = $seat->default_price;
            $total += $price;

            $typeName = match($seat->seat_type) {
                2 => 'VIP',
                3 => 'Couple',
                default => 'Thường'
            };

            $seatDetails[] = [
                'seat_id'    => $seat->seat_id,
                'seat_num'   => $seat->seat_num,
                'type'       => $seat->seat_type,
                'type_name'  => $typeName,
                'price'      => $price
            ];
        }

        // LƯU VÀO SESSION – ĐẦY ĐỦ THÔNG TIN
        session([
            'booking' => [
                'show_id'     => $show_id,
                'seats'       => $seatDetails,
                'combos'      => [],
                'total'       => $total,
                'expires_at'  => now()->addMinutes(10)->timestamp
            ]
        ]);

        // ✅ FIX: CHỈ TRẢ SUCCESS = TRUE, KHÔNG REDIRECT NGAY
        // JavaScript sẽ handle notification thay vì redirect
        return response()->json([
            'success' => true,
            'total'   => number_format($total) . 'đ',
            'seats'   => $seatDetails,
            'message' => 'Đã giữ ghế thành công! Thời gian giữ: 10 phút'
        ]);
    }
}