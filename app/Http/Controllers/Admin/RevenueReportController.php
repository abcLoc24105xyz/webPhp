<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $tab       = $request->get('tab', 'overview');
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        $cinemaId  = $request->get('cinema_id');

        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->endOfDay();

        // === DOANH THU THEO NGÀY (ĐÃ FIX LỖI 'has') ===
        $dailyRevenueQuery = DB::table('reservations')
            ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
            ->join('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
            ->leftJoin('reservation_combos as rc', 'rc.booking_code', '=', 'reservations.booking_code')
            ->where('reservations.status', 'paid')
            ->whereBetween('reservations.created_at', [$start, $end]);

        if ($cinemaId) {
            $dailyRevenueQuery->where('shows.cinema_id', $cinemaId);
        }

        $dailyRevenue = $dailyRevenueQuery
            ->selectRaw('DATE(reservations.created_at) as date, 
                         COALESCE(SUM(rs.seat_price), 0) + COALESCE(SUM(rc.quantity * rc.combo_price), 0) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        // Tạo mảng ngày + doanh thu đầy đủ
        $dates = $revenues = [];
        $period = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate)->addDay());
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $dates[]    = $date->translatedFormat('d/m');
            $revenues[] = (float)($dailyRevenue[$d] ?? 0);
        }

        // === TỔNG DOANH THU, VÉ, COMBO ===
        $baseQuery = DB::table('reservations')
            ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
            ->where('reservations.status', 'paid')
            ->whereBetween('reservations.created_at', [$start, $end]);

        if ($cinemaId) {
            $baseQuery->where('shows.cinema_id', $cinemaId);
        }

        $totalRevenue = $baseQuery->clone()
            ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
            ->leftJoin('reservation_combos as rc', 'rc.booking_code', '=', 'reservations.booking_code')
            ->sum(DB::raw('COALESCE(rs.seat_price, 0) + COALESCE(rc.quantity * rc.combo_price, 0)'));

        $ticketRevenue = $baseQuery->clone()
            ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
            ->sum('rs.seat_price');

        $comboRevenue = $totalRevenue - $ticketRevenue;

        $totalTickets = $baseQuery->clone()
            ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
            ->count('rs.seat_id');

        $totalBookings = $baseQuery->count('reservations.booking_code');

        // ĐÃ SỬA CHÍNH TẢ: avgTicketPrice (không phải avgTicicketPrice)
        $avgTicketPrice = $totalTickets > 0 ? round($ticketRevenue / $totalTickets) : 0;
        $avgOrderValue  = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;

        // === TOP 10 PHIM ===
        $topMovies = collect();
        if (in_array($tab, ['movie', 'overview'])) {
            $topMovies = DB::table('reservations')
                ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
                ->join('movies', 'movies.movie_id', '=', 'shows.movie_id')
                ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
                ->leftJoin('reservation_combos as rc', 'rc.booking_code', '=', 'reservations.booking_code')
                ->where('reservations.status', 'paid')
                ->whereBetween('reservations.created_at', [$start, $end])
                ->when($cinemaId, fn($q) => $q->where('shows.cinema_id', $cinemaId))
                ->groupBy('movies.movie_id', 'movies.title', 'movies.poster')
                ->selectRaw('
                    movies.movie_id,
                    movies.title,
                    movies.poster,
                    COUNT(rs.seat_id) as tickets_sold,
                    COALESCE(SUM(rs.seat_price), 0) + COALESCE(SUM(rc.quantity * rc.combo_price), 0) as total_revenue
                ')
                ->orderByDesc('total_revenue')
                ->limit(10)
                ->get();
        }

        // === TOP THỂ LOẠI ===
        $topCategories = collect();
        if ($tab === 'category') {
            $topCategories = DB::table('reservations')
                ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
                ->join('movies', 'movies.movie_id', '=', 'shows.movie_id')
                ->join('categories', 'categories.cate_id', '=', 'movies.cate_id')
                ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
                ->leftJoin('reservation_combos as rc', 'rc.booking_code', '=', 'reservations.booking_code')
                ->where('reservations.status', 'paid')
                ->whereBetween('reservations.created_at', [$start, $end])
                ->when($cinemaId, fn($q) => $q->where('shows.cinema_id', $cinemaId))
                ->groupBy('categories.cate_id', 'categories.name')
                ->selectRaw('
                    categories.name,
                    COUNT(rs.seat_id) as tickets,
                    COALESCE(SUM(rs.seat_price), 0) + COALESCE(SUM(rc.quantity * rc.combo_price), 0) as revenue
                ')
                ->orderByDesc('revenue')
                ->get();
        }

        // === DOANH THU THEO RẠP ===
        $revenueByCinema = collect();
        if ($tab === 'cinema') {
            $revenueByCinema = DB::table('reservations')
                ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
                ->join('cinemas', 'cinemas.cinema_id', '=', 'shows.cinema_id')
                ->leftJoin('reservation_seats as rs', 'rs.booking_code', '=', 'reservations.booking_code')
                ->leftJoin('reservation_combos as rc', 'rc.booking_code', '=', 'reservations.booking_code')
                ->where('reservations.status', 'paid')
                ->whereBetween('reservations.created_at', [$start, $end])
                ->when($cinemaId, fn($q) => $q->where('shows.cinema_id', $cinemaId))
                ->groupBy('cinemas.cinema_id', 'cinemas.cinema_name')
                ->selectRaw('
                    cinemas.cinema_name,
                    COUNT(rs.seat_id) as tickets,
                    COALESCE(SUM(rs.seat_price), 0) + COALESCE(SUM(rc.quantity * rc.combo_price), 0) as revenue
                ')
                ->orderByDesc('revenue')
                ->get();
        }

        // === TOP XEM NHIỀU ===
        $topViewMovies = $topViewCategories = collect();
        if ($tab === 'topviews') {
            $topViewMovies = DB::table('reservations')
                ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
                ->join('movies', 'movies.movie_id', '=', 'shows.movie_id')
                ->where('reservations.status', 'paid')
                ->whereBetween('reservations.created_at', [$start, $end])
                ->when($cinemaId, fn($q) => $q->where('shows.cinema_id', $cinemaId))
                ->groupBy('movies.movie_id', 'movies.title')
                ->selectRaw('movies.title, COUNT(*) as view_count')
                ->orderByDesc('view_count')
                ->limit(10)
                ->get();

            $topViewCategories = DB::table('reservations')
                ->join('shows', 'shows.show_id', '=', 'reservations.show_id')
                ->join('movies', 'movies.movie_id', '=', 'shows.movie_id')
                ->join('categories', 'categories.cate_id', '=', 'movies.cate_id')
                ->where('reservations.status', 'paid')
                ->whereBetween('reservations.created_at', [$start, $end])
                ->when($cinemaId, fn($q) => $q->where('shows.cinema_id', $cinemaId))
                ->groupBy('categories.cate_id', 'categories.name')
                ->selectRaw('categories.name, COUNT(*) as view_count')
                ->orderByDesc('view_count')
                ->limit(5)
                ->get();
        }

        // === DANH SÁCH GIAO DỊCH ===
        $reservationsList = Reservation::with(['user', 'show.movie', 'show.cinema', 'show.room', 'seats', 'combos'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->when($cinemaId, fn($q) => $q->whereHas('show', fn($sq) => $sq->where('cinema_id', $cinemaId)))
            ->orderByDesc('created_at')
            ->paginate(20);

        $cinemas = Cinema::where('status', 1)->orderBy('cinema_name')->get();

        return view('admin.revenue.index', compact(
            'tab', 'startDate', 'endDate', 'cinemaId',
            'totalRevenue', 'ticketRevenue', 'comboRevenue',
            'totalTickets', 'totalBookings',
            'avgTicketPrice', 'avgOrderValue', // ← ĐÃ SỬA
            'dates', 'revenues',
            'topMovies', 'topCategories', 'revenueByCinema',
            'topViewMovies', 'topViewCategories',
            'reservationsList', 'cinemas'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->format('Y-m-d'));
        $cinemaId  = $request->get('cinema_id');

        $exporter = new \App\Exports\RevenueReportExport($startDate, $endDate, $cinemaId);
        return $exporter->export();
    }
}