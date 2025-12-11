<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Movie;
use App\Models\Show;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\ReservationCombo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // === DOANH THU HÔM NAY – TÍNH CHUẨN TỪ 2 BẢNG RIÊNG (CHÍNH XÁC 100%) ===
        $todayTicketRevenue = DB::table('reservation_seats')
            ->join('reservations', 'reservations.booking_code', '=', 'reservation_seats.booking_code')
            ->where('reservations.status', 'paid')
            ->whereDate('reservations.created_at', $today)
            ->sum('reservation_seats.seat_price');

        $todayComboRevenue = DB::table('reservation_combos')
            ->join('reservations', 'reservations.booking_code', '=', 'reservation_combos.booking_code')
            ->where('reservations.status', 'paid')
            ->whereDate('reservations.created_at', $today)
            ->sum(DB::raw('reservation_combos.quantity * reservation_combos.combo_price'));

        $todayRevenue = $todayTicketRevenue + $todayComboRevenue; // ← TỔNG CHUẨN NHẤT

        // === DOANH THU 7 NGÀY GẦN NHẤT – CŨNG TÍNH CHUẨN NHƯ TRÊN ===
        $revenueLast7Days = DB::table('reservation_seats')
            ->join('reservations', 'reservations.booking_code', '=', 'reservation_seats.booking_code')
            ->where('reservations.status', 'paid')
            ->whereBetween('reservations.created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->selectRaw('DATE(reservations.created_at) as date')
            ->selectRaw('SUM(reservation_seats.seat_price) as ticket_revenue')
            ->groupBy('date')
            ->unionAll(
                DB::table('reservation_combos')
                    ->join('reservations', 'reservations.booking_code', '=', 'reservation_combos.booking_code')
                    ->where('reservations.status', 'paid')
                    ->whereBetween('reservations.created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
                    ->selectRaw('DATE(reservations.created_at) as date')
                    ->selectRaw('SUM(reservation_combos.quantity * reservation_combos.combo_price) as ticket_revenue')
                    ->groupBy('date')
            )
            ->get()
            ->groupBy('date')
            ->map->sum('ticket_revenue');

        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label   = $date->translatedFormat('d/m');

            $chartLabels[] = $label;
            $chartData[]   = $revenueLast7Days[$dateStr] ?? 0;
        }

        // === TOP 5 PHIM HOT (đúng rồi, giữ nguyên) ===
        $topMovies = DB::table('reservation_seats')
            ->join('reservations', 'reservations.booking_code', '=', 'reservation_seats.booking_code')
            ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
            ->join('movies', 'movies.movie_id', '=', 'shows.movie_id')
            ->where('reservations.status', 'paid')
            ->selectRaw('movies.movie_id, movies.title, movies.poster, COUNT(*) as tickets_sold')
            ->groupBy('movies.movie_id', 'movies.title', 'movies.poster')
            ->orderByDesc('tickets_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalMovies'        => Movie::count(),
            'todayRevenue'       => $todayRevenue,
            'todayTicketRevenue' => $todayTicketRevenue,
            'todayComboRevenue'  => $todayComboRevenue,
            'todayShows'         => Show::whereDate('show_date', $today)->count(),
            'newCustomers'      => User::whereDate('created_at', $today)->count(),

            'chartLabels'        => $chartLabels,
            'chartData'          => $chartData,
            'topMovies'          => $topMovies,
        ]);
    }
}