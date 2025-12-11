<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class MovieController extends Controller
{
    // Status constants
    const STATUS_COMING_SOON = 1;
    const STATUS_SHOWING = 2;
    const STATUS_ENDED = 3;

   public function index(Request $request)
    {
        try {
            $query = Movie::with('category');

            // Lọc theo tên phim
            if ($search = $request->get('search')) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            // Lọc theo thể loại
            if ($category = $request->get('category')) {
                $query->where('cate_id', $category);
            }

            // Lọc theo trạng thái
            if ($status = $request->get('status')) {
                $query->where('status', $status);
            }

            // Lọc theo ngày công chiếu
            if ($date = $request->get('release_date')) {
                $query->whereDate('release_date', $date);
            }

            $movies = $query->orderBy('movie_id', 'desc')->paginate(10);

            return view('admin.movies.index', compact('movies'));
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                return back()->with('warning', 'Chưa có danh mục nào.');
            }
            return view('admin.movies.create', compact('categories'));
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'title'                 => 'required|string|max:255|unique:movies,title',
                'cate_id'               => 'required|exists:categories,cate_id',
                'director'              => 'required|string|max:150',
                'duration'              => 'required|integer|min:1|max:300',
                'release_date'          => 'required|date|date_format:Y-m-d',
                'early_premiere_date'   => 'nullable|date|date_format:Y-m-d|before:release_date',
                'age_limit'             => 'required|in:0,13,16,18',
                'poster'                => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'trailer'               => 'nullable|url|max:255',
                'description'           => 'required|string|max:2000',
            ], [
                'title.unique'          => 'Tiêu đề phim đã tồn tại.',
                'early_premiere_date.before' => 'Ngày chiếu sớm phải trước ngày công chiếu.',
                'poster.required'       => 'Vui lòng chọn poster.',
                'poster.image'          => 'Poster phải là file ảnh.',
                'poster.max'            => 'Kích thước poster không vượt quá 2MB.',
            ]);

            // Upload poster
            $posterFile = $request->file('poster');
            $posterPath = public_path('poster');
            
            if (!is_dir($posterPath)) {
                mkdir($posterPath, 0755, true);
            }

            $posterName = time() . '_' . $posterFile->getClientOriginalName();
            $posterFile->move($posterPath, $posterName);

            // Determine initial status based on dates
            $startDate = $validated['early_premiere_date'] ?? $validated['release_date'];
            $today = Carbon::now()->startOfDay();
            
            if ($today->gte(Carbon::parse($startDate)->startOfDay())) {
                $initialStatus = self::STATUS_SHOWING;
            } else {
                $initialStatus = self::STATUS_COMING_SOON;
            }

            // Create movie
            $movie = Movie::create([
                'title'               => $validated['title'],
                'slug'                => 'temp',
                'cate_id'             => $validated['cate_id'],
                'director'            => $validated['director'],
                'duration'            => $validated['duration'],
                'release_date'        => $validated['release_date'],
                'early_premiere_date' => $validated['early_premiere_date'] ?? null,
                'age_limit'           => $validated['age_limit'],
                'poster'              => $posterName,
                'trailer'             => $validated['trailer'] ?? null,
                'description'         => $validated['description'],
                'status'              => $initialStatus,
                'created_at'          => now(),
            ]);

            // Generate slug: ten-phim-movie_id
            $baseSlug = Str::slug($validated['title']);
            $finalSlug = $baseSlug . '-' . $movie->movie_id;

            $count = 1;
            while (Movie::where('slug', $finalSlug)->exists()) {
                $finalSlug = $baseSlug . '-' . $movie->movie_id . '-' . $count;
                $count++;
            }

            $movie->update(['slug' => $finalSlug]);

            $statusLabel = $initialStatus == self::STATUS_COMING_SOON ? 'Sắp chiếu' : 'Đang chiếu';
            return redirect()->route('admin.movies.index')
                ->with('success', "Thêm phim thành công! (Trạng thái: $statusLabel)");

        } catch (Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $categories = Category::all();
            return view('admin.movies.edit', compact('movie', 'categories'));
        } catch (Exception $e) {
            return back()->with('error', 'Phim không tồn tại.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);

            $validated = $request->validate([
                'title'                 => 'required|string|max:255|unique:movies,title,' . $id . ',movie_id',
                'cate_id'               => 'required|exists:categories,cate_id',
                'director'              => 'required|string|max:150',
                'duration'              => 'required|integer|min:1|max:300',
                'release_date'          => 'required|date|date_format:Y-m-d',
                'early_premiere_date'   => 'nullable|date|date_format:Y-m-d|before:release_date',
                'age_limit'             => 'required|in:0,13,16,18',
                'poster'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'trailer'               => 'nullable|url|max:255',
                'description'           => 'required|string|max:2000',
            ]);

            $data = [
                'title'               => $validated['title'],
                'cate_id'             => $validated['cate_id'],
                'director'            => $validated['director'],
                'duration'            => $validated['duration'],
                'release_date'        => $validated['release_date'],
                'early_premiere_date' => $validated['early_premiere_date'] ?? null,
                'age_limit'           => $validated['age_limit'],
                'trailer'             => $validated['trailer'] ?? null,
                'description'         => $validated['description'],
            ];

            // Handle poster upload
            if ($request->hasFile('poster')) {
                $posterPath = public_path('poster');
                
                if ($movie->poster && file_exists($posterPath . '/' . $movie->poster)) {
                    try {
                        unlink($posterPath . '/' . $movie->poster);
                    } catch (Exception $e) {
                        // Log warning
                    }
                }

                $posterFile = $request->file('poster');
                $posterName = time() . '_' . $posterFile->getClientOriginalName();
                
                if (!is_dir($posterPath)) {
                    mkdir($posterPath, 0755, true);
                }

                $posterFile->move($posterPath, $posterName);
                $data['poster'] = $posterName;
            }

            // Update slug if title changed
            $baseSlug = Str::slug($validated['title']);
            $finalSlug = $baseSlug . '-' . $movie->movie_id;

            $count = 1;
            while (Movie::where('slug', $finalSlug)->where('movie_id', '!=', $movie->movie_id)->exists()) {
                $finalSlug = $baseSlug . '-' . $movie->movie_id . '-' . $count;
                $count++;
            }

            $data['slug'] = $finalSlug;

            // Auto-update status based on dates
            $startDate = $validated['early_premiere_date'] ?? $validated['release_date'];
            $today = Carbon::now()->startOfDay();
            $endDate = Carbon::parse($startDate)->addWeeks(4)->startOfDay();
            
            if ($today->gt($endDate) && $movie->status !== self::STATUS_ENDED) {
                $data['status'] = self::STATUS_ENDED;
            } elseif ($today->gte(Carbon::parse($startDate)->startOfDay()) && $movie->status !== self::STATUS_SHOWING) {
                $data['status'] = self::STATUS_SHOWING;
            } elseif ($today->lt(Carbon::parse($startDate)->startOfDay()) && $movie->status !== self::STATUS_COMING_SOON) {
                $data['status'] = self::STATUS_COMING_SOON;
            }

            $movie->update($data);

            return redirect()->route('admin.movies.index')
                ->with('success', 'Cập nhật phim thành công!');

        } catch (Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);

            // Kiểm tra xem phim có suất chiếu nào không
            if ($movie->shows()->exists()) {
                return back()->with('error', 'Không thể xóa phim đã có suất chiếu!');
            }

            // Xóa file poster nếu tồn tại
            $posterPath = public_path('poster');
            if ($movie->poster && file_exists($posterPath . '/' . $movie->poster)) {
                try {
                    unlink($posterPath . '/' . $movie->poster);
                } catch (Exception $e) {
                    // Log warning but continue
                }
            }

            $movie->delete();

            return redirect()->route('admin.movies.index')
                ->with('success', 'Xóa phim thành công!');

        } catch (Exception $e) {
            return back()->with('error', 'Lỗi khi xóa phim: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            
            // Auto-calculate status based on dates
            $startDate = $movie->early_premiere_date ?? $movie->release_date;
            $today = Carbon::now()->startOfDay();
            $endDate = Carbon::parse($startDate)->addWeeks(4)->startOfDay();
            
            if ($today->gt($endDate)) {
                $newStatus = self::STATUS_ENDED;
                $label = 'Kết thúc';
            } elseif ($today->gte(Carbon::parse($startDate)->startOfDay())) {
                $newStatus = self::STATUS_SHOWING;
                $label = 'Đang chiếu';
            } else {
                $newStatus = self::STATUS_COMING_SOON;
                $label = 'Sắp chiếu';
            }

            $movie->update(['status' => $newStatus]);

            return back()->with('success', "Trạng thái cập nhật: $label");

        } catch (Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Get movie info (for AJAX)
     */
    public function show($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            
            return response()->json([
                'duration' => $movie->duration ?? 120,
                'title' => $movie->title,
                'release_date' => $movie->release_date ? $movie->release_date->format('Y-m-d') : null,
                'early_premiere_date' => $movie->early_premiere_date ? $movie->early_premiere_date->format('Y-m-d') : null,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Phim không tìm thấy'], 404);
        }
    }

    /**
     * Command để cập nhật status tất cả phim hàng ngày
     */
    public function updateAllStatus()
    {
        try {
            $movies = Movie::all();
            $updated = 0;

            foreach ($movies as $movie) {
                $oldStatus = $movie->status;
                
                $startDate = $movie->early_premiere_date ?? $movie->release_date;
                $today = Carbon::now()->startOfDay();
                $endDate = Carbon::parse($startDate)->addWeeks(4)->startOfDay();
                
                if ($today->gt($endDate)) {
                    $newStatus = self::STATUS_ENDED;
                } elseif ($today->gte(Carbon::parse($startDate)->startOfDay())) {
                    $newStatus = self::STATUS_SHOWING;
                } else {
                    $newStatus = self::STATUS_COMING_SOON;
                }

                if ($oldStatus !== $newStatus) {
                    $movie->update(['status' => $newStatus]);
                    $updated++;
                }
            }

            return "Cập nhật $updated phim thành công.";

        } catch (Exception $e) {
            return "Lỗi: " . $e->getMessage();
        }
    }
}