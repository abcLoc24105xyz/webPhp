{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Tổng quan về hiệu suất hệ thống GhienCine.')

@section('content')
<div class="max-w-7xl mx-auto py-2">

    <!-- Chào mừng -->
    <div class="text-center mb-12">
        <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 mb-3 tracking-tight">
            XIN CHÀO ADMIN!
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 font-medium">
            Sẵn sàng quản lý rạp phim hôm nay?
        </p>
        <p class="text-base text-purple-300 mt-2 font-semibold">
            Hôm nay là <span class="text-pink-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d/m/Y') }}</span>
        </p>
    </div>

    <!-- 4 Card KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl p-6 shadow-2xl transform hover:scale-105 transition duration-300 border border-purple-500/30">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-film text-3xl text-white/80"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Tổng Phim</h3>
            <p class="text-5xl font-black text-white">{{ $totalMovies }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 shadow-2xl transform hover:scale-105 transition duration-300 border border-green-500/30">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-money-bill-wave text-3xl text-white/80"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Doanh Thu Hôm Nay</h3>
            <p class="text-4xl font-black text-white mt-1">{{ number_format($todayRevenue) }}đ</p>
            <div class="text-green-100 text-xs mt-3 space-y-1 border-t border-green-400/50 pt-2">
                <div class="flex justify-between"><span><i class="fas fa-ticket-alt mr-1"></i> Vé</span> <span>{{ number_format($todayTicketRevenue) }}đ</span></div>
                <div class="flex justify-between"><span><i class="fas fa-glass-whiskey mr-1"></i> Combo</span> <span>{{ number_format($todayComboRevenue) }}đ</span></div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 shadow-2xl transform hover:scale-105 transition duration-300 border border-yellow-500/30">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-calendar-alt text-3xl text-white/80"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Suất Chiếu Hôm Nay</h3>
            <p class="text-5xl font-black text-white">{{ $todayShows }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl p-6 shadow-2xl transform hover:scale-105 transition duration-300 border border-blue-500/30">
            <div class="flex items-center justify-between mb-4">
                <i class="fas fa-user-plus text-3xl text-white/80"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Khách Mới Hôm Nay</h3>
            <p class="text-5xl font-black text-white">+{{ $newCustomers }}</p>
        </div>
    </div>

    <!-- Biểu đồ + Top phim -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        
        <!-- BIỂU ĐỒ DOANH THU – ĐÃ FIX HOÀN TOÀN, KHÔNG CÒN TRÀN -->
        <div class="bg-black/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
            <div class="p-6 border-b border-white/10">
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <i class="fas fa-chart-line text-green-400"></i>
                    Doanh Thu 7 Ngày Gần Nhất
                </h3>
            </div>
            <div class="p-6">
                <div class="relative h-80 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 phim hot -->
        <div class="bg-black/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10">
            <div class="p-6 border-b border-white/10">
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <i class="fas fa-fire-alt text-pink-400"></i>
                    Top 5 Phim Hot Nhất
                </h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                @forelse($topMovies as $movie)
                    <div class="flex items-center justify-between bg-white/5 rounded-xl p-4 hover:bg-white/10 transition duration-300 border border-transparent hover:border-pink-500/50 group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center text-xl font-black text-white shadow-lg">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <p class="text-white font-bold text-lg group-hover:text-pink-300 transition">
                                    {{ Str::limit($movie->title, 30) }}
                                </p>
                                <p class="text-pink-200 text-sm flex items-center gap-2">
                                    <i class="fas fa-ticket-alt"></i>
                                    {{ $movie->tickets_sold }} vé đã bán
                                </p>
                            </div>
                        </div>
                        <div class="text-3xl text-pink-400 opacity-70 group-hover:opacity-100 transition">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-12 text-lg">Chưa có dữ liệu phim hot</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Khích lệ tinh thần -->
    <div class="text-center bg-gradient-to-r from-purple-600/30 to-pink-600/30 backdrop-blur-xl rounded-2xl p-10 border border-purple-500/50 shadow-2xl">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
            Hoàn thành tốt công việc hôm nay!
        </h2>
        <p class="text-xl text-purple-200 max-w-4xl mx-auto leading-relaxed">
            Tiếp tục duy trì và phát triển đế chế giải trí <span class="text-pink-400 font-bold">GhienCine</span> vững mạnh!
        </p>
        <div class="mt-8">
            <span class="inline-block animate-bounce text-6xl text-purple-400">
                <i class="fas fa-rocket"></i>
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chartData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 10,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 12,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.08)', drawBorder: false },
                    ticks: {
                        color: '#e2e8f0',
                        padding: 12,
                        font: { size: 13, weight: 'bold' },
                        callback: function(value) {
                            if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' tỷ';
                            if (value >= 1000000) return (value / 1000000).toFixed(0) + ' triệu';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                            return value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#e2e8f0', padding: 12, font: { size: 13 } }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
});
</script>
@endpush
@endsection