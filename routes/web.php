<?php

use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================
Route::view('/uu-dai', 'promotion')->name('promotions');
Route::view('/dieu-khoan-chung', 'terms')->name('terms');
Route::view('/chinh-sach-bao-mat', 'privacy')->name('privacy');
Route::view('/faq', 'faq')->name('faq');
Route::view('/lien-he-quang-cao', 'advertise')->name('advertise');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/phim', [App\Http\Controllers\HomeController::class, 'allMovies'])->name('movies.all');
Route::get('/phim/{slug}', [App\Http\Controllers\MovieController::class, 'show'])->name('movie.detail');
Route::get('/phim/{slug}/suat-chieu', [App\Http\Controllers\MovieController::class, 'loadShows'])->name('movie.showtimes');

Route::get('/phim-noi-bat', [App\Http\Controllers\FeaturedMoviesController::class, 'index'])
    ->name('movie.featured');

// ==================== GUEST USER ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

    Route::get('/verify-otp', [App\Http\Controllers\Auth\VerifyOtpController::class, 'show'])->name('verify-otp.show');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\VerifyOtpController::class, 'verify'])->name('verify-otp.verify');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\VerifyOtpController::class, 'resend'])->name('resend-otp');

    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])
        ->name('password.email');
    Route::get('/password/reset/combined', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showCombinedForm'])
        ->name('password.reset.combined');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'processCombined'])
        ->name('password.reset');

    Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])
        ->name('auth.google.callback');
});

// ==================== AUTHENTICATED USER ROUTES ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/history', [App\Http\Controllers\ProfileController::class, 'history'])->name('profile.history');
    Route::get('/profile/ticket/{booking_code}', [App\Http\Controllers\ProfileController::class, 'ticketDetail'])->name('profile.ticket.detail');

    Route::get('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'showForm'])
        ->name('password.change');
    Route::post('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'update'])
        ->name('password.change.update');

    // ✅ FIXED: Thêm middleware('auth') để tự lưu intended URL
    Route::get('/dat-ve/{show_id}', [App\Http\Controllers\SeatController::class, 'index'])->name('seat.selection');
    Route::post('/dat-ve/{show_id}/hold', [App\Http\Controllers\SeatController::class, 'holdSeats'])->name('seat.hold');

    Route::get('/combo-select', [App\Http\Controllers\SelectComboController::class, 'show'])->name('combo.select');
    Route::post('/combo-select', [App\Http\Controllers\SelectComboController::class, 'store'])->name('combo.store');

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/summary', [App\Http\Controllers\BookingController::class, 'summary'])->name('summary');
        Route::post('/apply-promo', [App\Http\Controllers\BookingController::class, 'applyPromo'])->name('apply-promo');
        Route::post('/remove-promo', [App\Http\Controllers\BookingController::class, 'removePromo'])->name('remove-promo');
        Route::get('/{booking_code}', [App\Http\Controllers\BookingController::class, 'detail'])->name('detail');
    });

    Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])
        ->name('logout');
});

// ==================== THANH TOÁN MOMO ====================
Route::get('/momo-payment', [App\Http\Controllers\PaymentController::class, 'momoPayment'])->name('momo.payment');
Route::post('/momo/create', [App\Http\Controllers\PaymentController::class, 'createMomoPayment'])->name('momo.create');
Route::post('/momo/atm', [App\Http\Controllers\PaymentController::class, 'createAtmPayment'])->name('momo.atm');

Route::get('/momo/return', [App\Http\Controllers\PaymentController::class, 'momoReturn'])->name('momo.return');
Route::match(['get', 'post'], '/momo/ipn', [App\Http\Controllers\PaymentController::class, 'momoIpn'])->name('momo.ipn');

// ==================== ADMIN PANEL ====================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/shows/import', [App\Http\Controllers\Admin\ShowImportController::class, 'showImportForm'])->name('shows.import.form');
        Route::post('/shows/import', [App\Http\Controllers\Admin\ShowImportController::class, 'import'])->name('shows.import');
        Route::get('/shows/import-template', [App\Http\Controllers\Admin\ShowImportController::class, 'downloadTemplate'])->name('shows.import.template');

        Route::resource('movies', App\Http\Controllers\Admin\MovieController::class)->parameters(['movies' => 'movie_id']);
        Route::post('movies/{movie_id}/toggle-status', [App\Http\Controllers\Admin\MovieController::class, 'toggleStatus'])->name('movies.toggleStatus');

        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('shows', App\Http\Controllers\Admin\ShowController::class)->parameters(['shows' => 'show_id']);
        Route::get('shows/{show_id}/detail', [App\Http\Controllers\Admin\ShowController::class, 'show'])->name('shows.detail');

        Route::get('cinemas/{cinema_id}/rooms', [App\Http\Controllers\Admin\ShowController::class, 'getRoomsByCinema'])->name('cinemas.rooms');
        Route::get('movies/{movie_id}', [App\Http\Controllers\Admin\ShowController::class, 'getMovieInfo'])->name('movies.info');

        Route::resource('combos', App\Http\Controllers\Admin\ComboController::class);
        Route::post('combos/{combo}/deactivate', [App\Http\Controllers\Admin\ComboController::class, 'deactivate'])->name('combos.deactivate');
        Route::post('combos/{combo}/activate', [App\Http\Controllers\Admin\ComboController::class, 'activate'])->name('combos.activate');

        Route::resource('promocodes', App\Http\Controllers\Admin\PromocodeController::class);
        Route::post('promocodes/{promocode}/deactivate', [App\Http\Controllers\Admin\PromocodeController::class, 'deactivate'])->name('promocodes.deactivate');
        Route::post('promocodes/{promocode}/activate', [App\Http\Controllers\Admin\PromocodeController::class, 'activate'])->name('promocodes.activate');

        Route::get('/revenue', [App\Http\Controllers\Admin\RevenueReportController::class, 'index'])->name('revenue.index');
        Route::get('/revenue/export', [App\Http\Controllers\Admin\RevenueReportController::class, 'export'])->name('revenue.export');

        Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers');
        Route::post('/customers/{user}/block', [App\Http\Controllers\Admin\CustomerController::class, 'block'])->name('customers.block');
        Route::post('/customers/{user}/unblock', [App\Http\Controllers\Admin\CustomerController::class, 'unblock'])->name('customers.unblock');
    });
});

// ==================== 404 FALLBACK ====================
Route::fallback(fn() => redirect()->route('home'));