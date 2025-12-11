<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShowController extends Controller
{
    public function index()
    {
        $query = Show::with(['movie', 'cinema', 'room'])
            ->orderBy('show_date', 'desc');

        // Tìm kiếm
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('show_id', 'like', "%{$search}%")
                  ->orWhereHas('movie', function ($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo rạp
        if (request('cinema')) {
            $query->where('cinema_id', request('cinema'));
        }

        // Lọc theo ngày
        if (request('date')) {
            $query->whereDate('show_date', request('date'));
        }
        
        $shows = $query->paginate(15);
        $cinemas = Cinema::where('status', 1)->get();

        return view('admin.shows.index', compact('shows', 'cinemas'));
    }

    public function create()
    {
        // Lấy phim đang chiếu (2) hoặc sắp chiếu (1)
        $movies = Movie::whereIn('status', [1, 2])
            ->pluck('title', 'movie_id');

        $cinemas = Cinema::where('status', 1)
            ->pluck('cinema_name', 'cinema_id');

        return view('admin.shows.create', compact('movies', 'cinemas'));
    }

    // AJAX lấy danh sách phòng theo rạp
    public function getRoomsByCinema($cinema_id)
    {
        $rooms = Room::where('cinema_id', $cinema_id)
            ->pluck('room_name', 'room_code');

        return response()->json($rooms);
    }

    // AJAX lấy thông tin phim (duration + dates)
    public function getMovieInfo($movie_id)
    {
        $movie = Movie::find($movie_id);
        
        if (!$movie) {
            return response()->json(['error' => 'Phim không tìm thấy'], 404);
        }
        
        return response()->json([
            'duration' => $movie->duration ?? 120,
            'title' => $movie->title,
            'release_date' => $movie->release_date ? $movie->release_date->format('Y-m-d') : null,
            'early_premiere_date' => $movie->early_premiere_date ? $movie->early_premiere_date->format('Y-m-d') : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,movie_id',
            'cinema_id'  => 'required|exists:cinemas,cinema_id',
            'room_code'  => 'required|exists:rooms,room_code',
            'show_date'  => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);

        $movie    = Movie::findOrFail($request->movie_id);
        $showDate = Carbon::parse($request->show_date);

        // === KIỂM TRA NGÀY CHIẾU HỢP LỆ (chỉ kiểm tra "từ ngày nào trở đi") ===
        if ($movie->early_premiere_date) {
            $earlyDate   = Carbon::parse($movie->early_premiere_date);
            $releaseDate = Carbon::parse($movie->release_date);

            // Được chiếu vào: ngày chiếu sớm HOẶC từ ngày công chiếu trở đi
            $allowed = $showDate->isSameDay($earlyDate) || $showDate->gte($releaseDate);

            if (!$allowed) {
                return back()
                    ->withErrors(['show_date' => "Chỉ được tạo suất vào ngày chiếu sớm ({$earlyDate->format('d/m/Y')}) hoặc từ ngày công chiếu ({$releaseDate->format('d/m/Y')}) trở đi!"])
                    ->withInput();
            }
        } else {
            // Không có chiếu sớm → chỉ từ ngày công chiếu trở đi
            $releaseDate = Carbon::parse($movie->release_date);

            if ($showDate->lt($releaseDate)) {
                return back()
                    ->withErrors(['show_date' => "Chỉ được tạo suất từ ngày công chiếu ({$releaseDate->format('d/m/Y')}) trở đi!"])
                    ->withInput();
            }
        }

        // === KIỂM TRA TRÙNG LỊCH ===
        $conflict = Show::where('room_code', $request->room_code)
            ->where('show_date', $request->show_date)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['room_code' => 'Phòng này đã có suất chiếu trùng thời gian!'])
                ->withInput();
        }

        // === TẠO MÃ SUẤT CHIẾU ===
        $dateFormat = $showDate->format('Ymd');
        $lastShow   = Show::where('show_id', 'like', "SHOW{$dateFormat}%")
            ->orderByDesc('show_id')
            ->first();

        $sequence = $lastShow ? ((int)substr($lastShow->show_id, -3)) + 1 : 1;
        $show_id  = "SHOW{$dateFormat}" . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        $room = Room::where('room_code', $request->room_code)->first();

        Show::create([
            'show_id'         => $show_id,
            'movie_id'        => $request->movie_id,
            'cinema_id'       => $request->cinema_id,
            'room_code'       => $request->room_code,
            'show_date'       => $request->show_date,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'remaining_seats' => $room->total_seats ?? 0,
        ]);

        return redirect()->route('admin.shows.index')
            ->with('success', 'Tạo suất chiếu thành công!');
    }

    public function edit($show_id)
    {
        $show = Show::findOrFail($show_id);

        $movies = Movie::whereIn('status', [1, 2])
            ->pluck('title', 'movie_id');

        $cinemas = Cinema::where('status', 1)
            ->pluck('cinema_name', 'cinema_id');

        $rooms = Room::where('cinema_id', $show->cinema_id)
            ->pluck('room_name', 'room_code');

        return view('admin.shows.edit', compact('show', 'movies', 'cinemas', 'rooms'));
    }

    public function update(Request $request, $show_id)
    {
        $show = Show::findOrFail($show_id);

        $request->validate([
            'movie_id'   => 'required|exists:movies,movie_id',
            'cinema_id'  => 'required|exists:cinemas,cinema_id',
            'room_code'  => 'required|exists:rooms,room_code',
            'show_date'  => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);

        $movie    = Movie::findOrFail($request->movie_id);
        $showDate = Carbon::parse($request->show_date);

        // === KIỂM TRA NGÀY CHIẾU HỢP LỆ ===
        if ($movie->early_premiere_date) {
            $earlyDate   = Carbon::parse($movie->early_premiere_date);
            $releaseDate = Carbon::parse($movie->release_date);

            $allowed = $showDate->isSameDay($earlyDate) || $showDate->gte($releaseDate);

            if (!$allowed) {
                return back()
                    ->withErrors(['show_date' => "Chỉ được tạo suất vào ngày chiếu sớm ({$earlyDate->format('d/m/Y')}) hoặc từ ngày công chiếu ({$releaseDate->format('d/m/Y')}) trở đi!"])
                    ->withInput();
            }
        } else {
            $releaseDate = Carbon::parse($movie->release_date);
            if ($showDate->lt($releaseDate)) {
                return back()
                    ->withErrors(['show_date' => "Chỉ được tạo suất từ ngày công chiếu ({$releaseDate->format('d/m/Y')}) trở đi!"])
                    ->withInput();
            }
        }

        // === KIỂM TRA TRÙNG LỊCH (loại trừ suất hiện tại) ===
        $conflict = Show::where('room_code', $request->room_code)
            ->where('show_date', $request->show_date)
            ->where('show_id', '!=', $show_id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors(['room_code' => 'Phòng này đã có suất chiếu trùng thời gian!'])
                ->withInput();
        }

        $show->update($request->only([
            'movie_id', 'cinema_id', 'room_code',
            'show_date', 'start_time', 'end_time'
        ]));

        return redirect()->route('admin.shows.index')
            ->with('success', 'Cập nhật suất chiếu thành công!');
    }
}