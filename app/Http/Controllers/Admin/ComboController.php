<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ComboController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query builder
        $query = Combo::query();

        // 1. LỌC theo Tên Combo (search)
        if ($request->filled('search')) {
            $query->where('combo_name', 'like', '%' . $request->search . '%');
        }

        // 2. LỌC theo Trạng thái (status)
        // Kiểm tra request('status') có giá trị '0' (Đã ẩn) hoặc '1' (Hoạt động)
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
             $query->where('status', $request->status);
        }

        // 3. SẮP XẾP (sort)
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                // Mặc định sắp xếp theo combo_id mới nhất
                $query->orderBy('combo_id', 'desc');
                break;
        }

        // Thực hiện phân trang
        $combos = $query->paginate(15)->withQueryString(); // Giữ lại các tham số lọc trên URL

        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        return view('admin.combos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'combo_name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $data = $request->only(['combo_name', 'description', 'price', 'status']);

        // XỬ LÝ ẢNH → LƯU VÀO public/images/combos
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Tạo tên file an toàn hơn
            $extension = $image->getClientOriginalExtension();
            $safeName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . uniqid() . '.' . $extension;

            // Đảm bảo thư mục tồn tại
            $path = public_path('images/combos');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $image->move($path, $filename);
            $data['image'] = 'images/combos/' . $filename; // ← Lưu đường dẫn tương đối
        }

        Combo::create($data);

        return redirect()->route('admin.combos.index')->with('success', 'Thêm combo thành công!');
    }

    public function edit(Combo $combo)
    {
        return view('admin.combos.edit', compact('combo'));
    }

    public function update(Request $request, Combo $combo)
    {
        $request->validate([
            'combo_name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $data = $request->only(['combo_name', 'description', 'price', 'status']);

        // XỬ LÝ ẢNH KHI CẬP NHẬT
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($combo->image && File::exists(public_path($combo->image))) {
                File::delete(public_path($combo->image));
            }

            $image = $request->file('image');
            // Tạo tên file an toàn hơn
            $extension = $image->getClientOriginalExtension();
            $safeName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . uniqid() . '.' . $extension;

            $path = public_path('images/combos');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $image->move($path, $filename);
            $data['image'] = 'images/combos/' . $filename;
        }

        $combo->update($data);

        return redirect()->route('admin.combos.index')->with('success', 'Cập nhật combo thành công!');
    }
    
    // THÊM: Xóa combo hoàn toàn
    public function destroy(Combo $combo)
    {
        // Xóa ảnh liên quan nếu tồn tại
        if ($combo->image && File::exists(public_path($combo->image))) {
            File::delete(public_path($combo->image));
        }

        $combo->delete();

        return back()->with('success', 'Đã xóa combo thành công!');
    }

    // Ẩn combo (status = 0)
    public function deactivate(Combo $combo)
    {
        $combo->update(['status' => 0]);
        return back()->with('success', 'Đã ẩn combo khỏi khách hàng!');
    }

    // Hiện combo (status = 1)
    public function activate(Combo $combo)
    {
        $combo->update(['status' => 1]);
        return back()->with('success', 'Đã hiện lại combo!');
    }
}