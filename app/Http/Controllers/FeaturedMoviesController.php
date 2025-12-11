<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class FeaturedMoviesController extends Controller
{
    public function index()
    {
        // 1. Phim có suất CHIẾU SỚM (ưu tiên cao nhất)
        $earlyMovies = Movie::whereNotNull('early_premiere_date')
            ->where('early_premiere_date', '>=', now()->toDateString())
            ->whereIn('status', [1, 2]) // 1=sắp chiếu, 2=đang chiếu
            ->orderBy('early_premiere_date', 'asc')
            ->limit(6)
            ->select('movie_id', 'title', 'slug', 'poster', 'early_premiere_date', 'age_limit')
            ->get();

        // 2. Phim bán vé nhiều nhất (hot nhất hiện tại)
        $popularMovies = Movie::whereIn('movies.status', [2, 3])
            ->join('shows', 'movies.movie_id', '=', 'shows.movie_id')
            ->join('reservations', function ($join) {
                $join->on('shows.show_id', '=', 'reservations.show_id')
                     ->where('reservations.status', 'paid');
            })
            ->join('reservation_seats', 'reservations.booking_code', '=', 'reservation_seats.booking_code')
            ->select('movies.movie_id', 'movies.title', 'movies.slug', 'movies.poster', 'movies.release_date', 'movies.age_limit')
            ->selectRaw('COUNT(reservation_seats.seat_id) as booked_seats_count')
            ->groupBy('movies.movie_id', 'movies.title', 'movies.slug', 'movies.poster', 'movies.release_date', 'movies.age_limit')
            ->orderByDesc('booked_seats_count')
            ->limit(12)
            ->get();

        // 3. Phim DOANH THU CAO NHẤT (vé + combo đã thanh toán)
        $topRevenueMovies = Movie::query()
            ->leftJoin('shows', 'movies.movie_id', '=', 'shows.movie_id')
            ->leftJoin('reservations', function ($join) {
                $join->on('shows.show_id', '=', 'reservations.show_id')
                     ->where('reservations.status', 'paid'); // ĐÃ SỬA: chỉ rõ bảng
            })
            ->leftJoin('reservation_seats', 'reservations.booking_code', '=', 'reservation_seats.booking_code')
            ->leftJoin('reservation_combos', 'reservations.booking_code', '=', 'reservation_combos.booking_code')
            ->whereIn('movies.status', [2, 3]) // QUAN TRỌNG: chỉ rõ movies.status
            ->select([
                'movies.movie_id',
                'movies.title',
                'movies.slug',
                'movies.poster',
                'movies.release_date',
                'movies.age_limit',
            ])
            ->selectRaw('COALESCE(SUM(reservation_seats.seat_price) + SUM(reservation_combos.quantity * reservation_combos.combo_price), 0) as total_revenue')
            ->groupBy([
                'movies.movie_id',
                'movies.title',
                'movies.slug',
                'movies.poster',
                'movies.release_date',
                'movies.age_limit'
            ])
            ->orderByDesc('total_revenue')
            ->limit(12)
            ->get();

        // 4. Gộp tất cả, loại trùng, ưu tiên: Chiếu sớm > Hot > Doanh thu
        $featuredMovies = collect()
            ->merge($earlyMovies)
            ->merge($popularMovies)
            ->merge($topRevenueMovies)
            ->unique('movie_id')
            ->sortByDesc(function ($movie) {
                // Phim chiếu sớm lên đầu tuyệt đối
                if ($movie->early_premiere_date && $movie->early_premiere_date >= now()->toDateString()) {
                    return 999999;
                }
                // Sau đó là phim bán nhiều vé
                if (isset($movie->booked_seats_count)) {
                    return 100000 + $movie->booked_seats_count;
                }
                // Cuối cùng là doanh thu
                return $movie->total_revenue ?? 0;
            })
            ->take(12)
            ->values();

        return view('movie.featured', compact('featuredMovies'));
    }
}