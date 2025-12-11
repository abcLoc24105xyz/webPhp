<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function index()
    {
        $promocodes = Promocode::orderBy('end_date', 'desc')->paginate(15);
        return view('admin.promocodes.index', compact('promocodes'));
    }

    public function create()
    {
        return view('admin.promocodes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code'      => 'required|string|unique:promocode,promo_code|max:20',
            'description'     => 'nullable|string',
            'discount_type'   => 'required|in:1,2',
            'discount_value'  => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'usage_limit'     => 'nullable|integer|min:1',
            'status'          => 'required|in:0,1',
        ]);

        // Xử lý dữ liệu trước khi insert
        $data = $request->all();

        // Đơn tối thiểu: để trống → 0 (tránh lỗi NOT NULL)
        $data['min_order_value'] = $request->filled('min_order_value') ? $request->min_order_value : 0;

        // Giới hạn dùng: để trống → NULL (không giới hạn)
        $data['usage_limit'] = $request->filled('usage_limit') ? $request->usage_limit : null;

        // Đảm bảo kiểu dữ liệu đúng
        $data['discount_type'] = (int) $request->discount_type;
        $data['status'] = (int) $request->status;
        $data['discount_value'] = (float) $request->discount_value;

        Promocode::create($data);

        return redirect()->route('admin.promocodes.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit(Promocode $promocode)
    {
        return view('admin.promocodes.edit', compact('promocode'));
    }

    public function update(Request $request, Promocode $promocode)
    {
        $request->validate([
            'promo_code'      => 'required|string|max:20|unique:promocode,promo_code,'.$promocode->promo_code.',promo_code',
            'description'     => 'nullable|string',
            'discount_type'   => 'required|in:1,2',
            'discount_value'  => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'usage_limit'     => 'nullable|integer|min:1',
            'status'          => 'required|in:0,1',
        ]);

        $data = $request->all();

        $data['min_order_value'] = $request->filled('min_order_value') ? $request->min_order_value : 0;
        $data['usage_limit']     = $request->filled('usage_limit') ? $request->usage_limit : null;

        $data['discount_type'] = (int) $request->discount_type;
        $data['status'] = (int) $request->status;
        $data['discount_value'] = (float) $request->discount_value;

        $promocode->update($data);

        return redirect()->route('admin.promocodes.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function deactivate(Promocode $promocode)
    {
        $promocode->update(['status' => 0]);
        return back()->with('success', 'Đã tắt mã giảm giá!');
    }

    public function activate(Promocode $promocode)
    {
        $promocode->update(['status' => 1]);
        return back()->with('success', 'Đã bật lại mã giảm giá!');
    }

    public function destroy(Promocode $promocode)
    {
        $promocodeName = $promocode->promo_code;

        $promocode->delete();

        return redirect()
            ->route('admin.promocodes.index')
            ->with('success', "Đã xóa vĩnh viễn mã giảm giá: {$promocodeName}");
    }
}