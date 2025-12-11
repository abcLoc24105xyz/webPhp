@extends('admin.layouts.app')
@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-900 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 sm:mb-10">
            <h2 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500 mb-2">
                BÁO CÁO DOANH THU
            </h2>
            <div class="text-xl sm:text-2xl font-bold text-gray-500 dark:text-purple-300 bg-white dark:bg-white/10 px-4 py-2 rounded-xl shadow-lg">
                {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d/m/Y') }} → {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d/m/Y') }}
            </div>
        </div>

        {{-- THỐNG KÊ TỔNG QUAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-indigo-700 to-blue-900 text-white p-4 sm:p-5 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02]">
                <h5 class="text-base sm:text-lg opacity-90 font-medium">Tổng Doanh Thu</h5>
                <h3 class="text-3xl sm:text-4xl font-black mt-2">{{ number_format($totalRevenue) }} đ</h3>
            </div>
            <div class="bg-gradient-to-br from-green-600 to-emerald-700 text-white p-4 sm:p-5 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02]">
                <h5 class="text-base sm:text-lg opacity-90 font-medium">Doanh Thu Vé</h5>
                <h3 class="text-3xl sm:text-4xl font-black mt-2">{{ number_format($ticketRevenue) }} đ</h3>
                <p class="text-green-200 text-sm sm:text-base mt-1">{{ number_format($totalTickets) }} vé</p>
            </div>
            <div class="bg-gradient-to-br from-fuchsia-600 to-pink-700 text-white p-4 sm:p-5 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02]">
                <h5 class="text-base sm:text-lg opacity-90 font-medium">Doanh Thu Combo</h5>
                <h3 class="text-3xl sm:text-4xl font-black mt-2">{{ number_format($comboRevenue) }} đ</h3>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white p-4 sm:p-5 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02]">
                <h5 class="text-base sm:text-lg opacity-90 font-medium">Số Đơn Hàng</h5>
                <h3 class="text-3xl sm:text-4xl font-black mt-2">{{ number_format($totalBookings) }}</h3>
                <p class="text-orange-200 text-sm sm:text-base mt-1">TB: {{ number_format($avgOrderValue) }}đ/đơn</p>
            </div>
        </div>

        {{-- BỘ LỌC + XUẤT EXCEL --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 sm:p-8 mb-10">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Từ ngày</label>
                    <input type="date" name="start_date" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500 transition" value="{{ $startDate }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Đến ngày</label>
                    <input type="date" name="end_date" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500 transition" value="{{ $endDate }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Chọn rạp</label>
                    <select name="cinema_id" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500 transition">
                        <option value="">Tất cả rạp</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->cinema_id }}" {{ $cinemaId == $cinema->cinema_id ? 'selected' : '' }}>
                                {{ $cinema->cinema_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="w-1/2 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:scale-105">
                        Lọc
                    </button>
                    <a href="{{ route('admin.revenue.export', request()->query()) }}"
                       class="w-1/2 py-3 bg-green-600 text-white font-bold rounded-xl shadow-lg hover:bg-green-700 transition transform hover:scale-105 text-center flex items-center justify-center">
                        Xuất Excel
                    </a>
                </div>
                <input type="hidden" name="tab" value="{{ $tab }}">
            </form>
        </div>

        {{-- TABS --}}
        <div class="mb-10">
            <ul class="flex flex-wrap text-center rounded-2xl overflow-hidden shadow-2xl" style="background: linear-gradient(90deg, #667eea, #764ba2);">
                @php
                    $tabs = [
                        'overview'     => 'Tổng Quan',
                        'movie'        => 'Theo Phim',
                        'category'     => 'Theo Thể Loại',
                        'cinema'       => 'Theo Rạp',
                        'transactions' => 'Giao Dịch',
                        'topviews'     => 'Top Xem Nhiều',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <li class="flex-1">
                        <a class="block py-4 sm:py-6 text-base sm:text-xl font-bold transition-all duration-300
                            {{ $tab === $key ? 'bg-white text-indigo-600 dark:bg-slate-900 dark:text-pink-500 shadow-inner' : 'text-white hover:bg-white/10 hover:shadow-inner' }}"
                            href="{{ route('admin.revenue.index', array_merge(request()->query(), ['tab' => $key])) }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- NỘI DUNG TAB --}}
        <div class="space-y-10">

            {{-- TAB TỔNG QUAN (ĐÃ THU NHỎ BIỂU ĐỒ) --}}
            @if($tab === 'overview')
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 sm:p-8">
                    <h4 class="text-2xl sm:text-3xl font-bold text-center mb-6 text-indigo-600 dark:text-purple-400">
                        Biểu Đồ Doanh Thu Theo Ngày
                    </h4>
                    <div class="w-full max-w-[800px] mx-auto" style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            @endif

            {{-- TAB THEO PHIM --}}
            @if($tab === 'movie')
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white p-6 sm:p-8">
                        <h4 class="text-2xl sm:text-3xl font-bold">Top Phim Doanh Thu Cao Nhất</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-900 dark:text-gray-200">
                            <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 rounded-tl-xl">#</th>
                                    <th scope="col" class="px-6 py-4">Phim</th>
                                    <th scope="col" class="px-6 py-4 text-end">Số Vé</th>
                                    <th scope="col" class="px-6 py-4 text-end">Doanh Thu</th>
                                    <th scope="col" class="px-6 py-4 text-center rounded-tr-xl">Tỷ Lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topMovies as $i => $m)
                                    <tr class="bg-white dark:bg-slate-800 border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        <td class="px-6 py-4 font-bold text-lg text-indigo-600 dark:text-purple-400">{{ $i + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($m->poster && file_exists(public_path('poster/' . basename($m->poster))))
                                                    <img src="{{ asset('poster/' . basename($m->poster)) }}" width="70" class="rounded-lg shadow me-4 object-cover object-center aspect-[2/3]">
                                                @else
                                                    <div class="w-[70px] h-[105px] bg-gray-200 dark:bg-slate-600 rounded-lg shadow me-4 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">No Poster</div>
                                                @endif
                                                <strong class="text-base sm:text-lg">{{ $m->title }}</strong>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-end text-lg text-indigo-600 dark:text-purple-400 font-bold">{{ number_format($m->tickets_sold) }}</td>
                                        <td class="px-6 py-4 text-end text-xl text-green-600 dark:text-emerald-400 font-black">{{ number_format($m->total_revenue) }} đ</td>
                                        <td class="px-6 py-4">
                                            <div class="w-full bg-gray-200 rounded-full h-8 dark:bg-slate-700">
                                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-base font-bold text-center p-1 leading-none rounded-full"
                                                     style="width: {{ $totalRevenue > 0 ? ($m->total_revenue/$totalRevenue)*100 : 0 }}%">
                                                    {{ $totalRevenue > 0 ? round(($m->total_revenue/$totalRevenue)*100, 1) : 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-16 text-muted dark:text-gray-400 text-xl">Chưa có dữ liệu phim</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- TAB THEO THỂ LOẠI --}}
            @if($tab === 'category')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($topCategories as $c)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition transform hover:scale-[1.02]">
                            <div class="text-center">
                                <h4 class="text-xl sm:text-2xl text-indigo-600 dark:text-purple-400 font-black mb-3">{{ $c->name }}</h4>
                                <h2 class="text-3xl sm:text-4xl text-green-600 dark:text-emerald-400 font-extrabold mb-2">{{ number_format($c->revenue) }} đ</h2>
                                <p class="text-base sm:text-lg text-gray-500 dark:text-gray-400">{{ number_format($c->tickets) }} vé</p>
                                <div class="w-full bg-gray-200 rounded-full h-6 mt-4 dark:bg-slate-700">
                                    <div class="bg-gradient-to-r from-teal-500 to-green-600 text-white text-sm font-bold text-center p-1 leading-none rounded-full"
                                         style="width: {{ $totalRevenue > 0 ? ($c->revenue/$totalRevenue)*100 : 0 }}%;">
                                        <strong>{{ $totalRevenue > 0 ? round(($c->revenue/$totalRevenue)*100, 1) : 0 }}%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-xl text-muted dark:text-gray-400">Không có dữ liệu thể loại</div>
                    @endforelse
                </div>
            @endif

            {{-- TAB THEO RẠP --}}
            @if($tab === 'cinema')
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-yellow-500 text-gray-900 p-6 sm:p-8">
                        <h4 class="text-2xl sm:text-3xl font-bold">Doanh Thu Theo Rạp</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-900 dark:text-gray-200">
                            <thead class="text-xs text-gray-900 uppercase bg-yellow-400 dark:bg-yellow-600/80">
                                <tr>
                                    <th scope="col" class="px-6 py-4 rounded-tl-xl">Rạp</th>
                                    <th scope="col" class="px-6 py-4 text-end">Số Vé</th>
                                    <th scope="col" class="px-6 py-4 text-end">Doanh Thu</th>
                                    <th scope="col" class="px-6 py-4 text-center rounded-tr-xl">Tỷ Lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByCinema as $c)
                                    <tr class="bg-white dark:bg-slate-800 border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        <td class="px-6 py-4 font-bold text-lg text-gray-900 dark:text-white">{{ $c->cinema_name }}</td>
                                        <td class="px-6 py-4 text-end text-lg text-indigo-600 dark:text-purple-400 font-bold">{{ number_format($c->tickets) }}</td>
                                        <td class="px-6 py-4 text-end text-xl text-red-600 dark:text-pink-400 font-black">{{ number_format($c->revenue) }} đ</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-4 py-2 text-base font-bold text-white bg-red-600 rounded-full dark:bg-pink-600">
                                                {{ $totalRevenue > 0 ? round(($c->revenue/$totalRevenue)*100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-10 text-xl text-muted dark:text-gray-400">Không có dữ liệu</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            {{-- TAB TOP XEM NHIỀU (VIEW COUNT) --}}
            @if($tab === 'topviews')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Top Thể Loại Xem Nhiều --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl h-full">
                        <div class="text-white p-6 sm:p-8 rounded-t-2xl" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <h4 class="text-2xl sm:text-3xl font-bold">Top 5 Thể Loại Xem Nhiều Nhất</h4>
                        </div>
                        <div class="p-6 sm:p-8 space-y-4">
                            @forelse($topViewCategories as $i => $cat)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-600 dark:bg-purple-600 text-white rounded-full flex items-center justify-center me-4 w-10 h-10 flex-shrink-0">
                                            <strong class="text-xl">{{ $i + 1 }}</strong>
                                        </div>
                                        <h5 class="mb-0 text-lg font-medium text-gray-900 dark:text-gray-200">{{ $cat->name }}</h5>
                                    </div>
                                    <h4 class="text-xl font-bold text-green-600 dark:text-emerald-400 mb-0">{{ number_format($cat->view_count) }} lượt</h4>
                                </div>
                            @empty
                                <div class="text-center py-6 text-lg text-muted dark:text-gray-400">Không có dữ liệu</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Top Phim Xem Nhiều --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl h-full">
                        <div class="text-white p-6 sm:p-8 rounded-t-2xl" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                            <h4 class="text-2xl sm:text-3xl font-bold">Top 10 Phim Xem Nhiều Nhất</h4>
                        </div>
                        <div class="p-6 sm:p-8 space-y-4">
                            @forelse($topViewMovies as $i => $movie)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <div class="bg-pink-600 dark:bg-red-600 text-white rounded-full flex items-center justify-center me-4 w-10 h-10 flex-shrink-0">
                                            <strong class="text-xl">{{ $i + 1 }}</strong>
                                        </div>
                                        <h5 class="mb-0 text-lg font-medium text-gray-900 dark:text-gray-200">{{ $movie->title }}</h5>
                                    </div>
                                    <h4 class="text-xl font-bold text-red-600 dark:text-pink-400 mb-0">{{ number_format($movie->view_count) }} lượt</h4>
                                </div>
                            @empty
                                <div class="text-center py-6 text-lg text-muted dark:text-gray-400">Không có dữ liệu</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif


            {{-- TAB GIAO DỊCH --}}
            @if($tab === 'transactions')
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gray-800 dark:bg-slate-900 text-white p-6 sm:p-8">
                        <h4 class="text-2xl sm:text-3xl font-bold">Danh Sách Giao Dịch Đã Thanh Toán</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-900 dark:text-gray-200">
                            <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Mã Đơn</th>
                                    <th scope="col" class="px-6 py-4">Khách</th>
                                    <th scope="col" class="px-6 py-4">Phim</th>
                                    <th scope="col" class="px-6 py-4">Rạp - Phòng</th>
                                    <th scope="col" class="px-6 py-4">Suất Chiếu</th>
                                    <th scope="col" class="px-6 py-4 text-center">Vé</th>
                                    <th scope="col" class="px-6 py-4 text-center">Combo</th>
                                    <th scope="col" class="px-6 py-4 text-end">Tổng Tiền</th>
                                    <th scope="col" class="px-6 py-4">Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservationsList as $r)
                                    <tr class="bg-white dark:bg-slate-800 border-b dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        <td class="px-6 py-4"><code class="bg-indigo-600 dark:bg-purple-600 text-white px-3 py-2 rounded font-mono text-sm">{{ $r->booking_code }}</code></td>
                                        <td class="px-6 py-4 font-bold">{{ $r->user->full_name ?? 'Khách lẻ' }}</td>
                                        <td class="px-6 py-4">{{ $r->show?->movie?->title ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold">{{ $r->show?->cinema?->cinema_name ?? '-' }}</div>
                                            <small class="text-muted dark:text-gray-400">{{ $r->show?->room?->room_name ?? '' }}</small>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($r->show)
                                                <span class="font-medium text-pink-600 dark:text-fuchsia-400">
                                                    {{ \Carbon\Carbon::parse($r->show->show_date)->format('d/m') }}
                                                    {{ substr($r->show->start_time, 0, 5) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-4 py-2 text-base font-bold text-white bg-green-600 rounded-full">
                                                {{ $r->seats->count() }} vé
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($r->combos->count() > 0)
                                                <span class="inline-flex items-center px-4 py-2 text-base font-bold text-white bg-purple-600 rounded-full">
                                                    {{ $r->combos->sum('pivot.quantity') }} món
                                                </span>
                                            @else
                                                <span class="text-muted dark:text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-end text-xl text-green-600 dark:text-emerald-400 font-black">
                                            {{ number_format($r->total_amount) }} đ
                                        </td>
                                        <td class="px-6 py-4 text-sm text-muted dark:text-gray-400">
                                            {{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->translatedFormat('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-center py-20 text-xl text-muted dark:text-gray-400">Chưa có giao dịch nào</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 bg-gray-50 dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600">
                        {{-- Laravel Pagination Links --}}
                        {{ $reservationsList->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- CHỈ LOAD CHART KHI TAB TỔNG QUAN --}}
@if($tab === 'overview')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Đảm bảo chỉ chạy 1 lần duy nhất
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('revenueChart');
        if (!canvas || canvas.chartInstance) return;

        const ctx = canvas.getContext('2d');

        // Hủy chart cũ nếu tồn tại (phòng trường hợp reload tab)
        if (canvas.chartInstance) {
            canvas.chartInstance.destroy();
        }

        canvas.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($revenues),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#c084fc',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 12,
                        padding: 14,
                        displayColors: false,
                        callbacks: {
                            label: ctx => 'Doanh thu: ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.08)', borderDash: [5, 5] },
                        ticks: {
                            callback: v => new Intl.NumberFormat('vi-VN', { notation: 'compact', compactDisplay: 'short' }).format(v),
                            font: { size: 13, weight: 'bold' },
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 13 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif
@endsection