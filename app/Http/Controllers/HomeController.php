<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        // ==================== PHIM ĐANG CHIẾU (FEATURED) ====================
        $featuredMovies = Movie::whereHas('shows', function ($q) use ($today) {
            $q->where('show_date', '>=', $today)
              ->where('remaining_seats', '>', 0);
        })
        ->with('category')
        ->where('status', 2) // 2 = đang chiếu
        ->inRandomOrder()
        ->limit(10)
        ->get();

        // ==================== PHIM SẮP CHIẾU ====================
        $upcomingMovies = Movie::where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereDoesntHave('shows')
                  ->orWhereHas('shows', function ($q2) use ($today) {
                      $q2->where('show_date', '>', $today->copy()->addDays(30));
                  });
            })
            ->orWhere('release_date', '>', $today)
            ->with('category')
            ->orderBy('release_date', 'asc')
            ->limit(10)
            ->get();

        // ==================== SUẤT CHIẾU SỚM: CHỈ LẤY TỪ HÔM NAY TRỞ ĐI ===
        $specialMovies = Movie::whereNotNull('early_premiere_date')
            ->whereDate('early_premiere_date', '>=', $today) // <-- QUAN TRỌNG: chỉ từ hôm nay trở đi
            ->with('category')
            ->orderBy('early_premiere_date', 'asc')
            ->limit(6)
            ->get();

        return view('home', compact('featuredMovies', 'upcomingMovies', 'specialMovies'));
    }

    public function allMovies(Request $request)
    {
        $today = Carbon::today();
        $categories = Category::orderBy('name')->get();
        $cinemas = Cinema::where('status', 1)->orderBy('cinema_name')->get();

        $currentTab = $request->get('tab', 'showing');

        // ==================== TAB: SUẤT CHIẾU SỚM ====================
        if ($currentTab === 'special') {
            $specialMovies = Movie::whereNotNull('early_premiere_date')
                ->whereDate('early_premiere_date', '>=', $today) // chỉ hiển thị từ hôm nay trở đi
                ->with('category')
                ->when($request->filled('search'), fn($q) => $q->where('title', 'like', "%{$request->search}%"))
                ->when($request->filled('category'), fn($q) => $q->where('cate_id', $request->category))
                ->when($request->filled('early_date'), fn($q) => $q->whereDate('early_premiere_date', $request->early_date))
                ->orderBy('early_premiere_date', 'asc')
                ->paginate(12)
                ->withQueryString();

            return view('all-movies', compact('specialMovies', 'categories', 'cinemas', 'currentTab'));
        }

        // ==================== TAB: PHIM ĐANG CHIẾU ====================
        if ($currentTab === 'showing') {
            $query = Movie::whereHas('shows', function ($q) use ($today) {
                $q->where('show_date', '>=', $today)
                  ->where('remaining_seats', '>', 0);
            })
            ->with('category')
            ->where('status', 2);

            $request->filled('search')   && $query->where('title', 'like', "%{$request->search}%");
            $request->filled('category') && $query->where('cate_id', $request->category);
            $request->filled('cinema')   && $query->whereHas('shows', fn($q) => $q->where('cinema_id', $request->cinema));
            $request->filled('show_date')&& $query->whereHas('shows', fn($q) => $q->where('show_date', $request->show_date));

            $showingMovies = $query
                ->orderByRaw("
                    CASE WHEN EXISTS (
                        SELECT 1 FROM shows 
                        WHERE shows.movie_id = movies.movie_id 
                          AND show_date >= ? 
                          AND remaining_seats > 0
                    ) THEN 0 ELSE 1 END,
                    (SELECT MIN(show_date) FROM shows WHERE shows.movie_id = movies.movie_id AND remaining_seats > 0),
                    movies.created_at DESC
                ", [$today])
                ->paginate(12)
                ->withQueryString();

            return view('all-movies', compact('showingMovies', 'categories', 'cinemas', 'currentTab'));
        }

        // ==================== TAB: PHIM SẮP CHIẾU (mặc định) ====================
        $query = Movie::where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereDoesntHave('shows')
                  ->orWhereHas('shows', function ($q2) use ($today) {
                      $q2->where('show_date', '>', $today->copy()->addDays(30));
                  });
            })
            ->orWhere('release_date', '>', $today)
            ->with('category');

        $request->filled('search')       && $query->where('title', 'like', "%{$request->search}%");
        $request->filled('category')     && $query->where('cate_id', $request->category);
        $request->filled('release_date') && $query->whereDate('release_date', $request->release_date);

        $upcomingMovies = $query
            ->orderBy('release_date', 'asc')
            ->paginate(12, ['*'], 'upcoming_page')
            ->withQueryString();

        return view('all-movies', compact('upcomingMovies', 'categories', 'cinemas', 'currentTab'));
    }
}