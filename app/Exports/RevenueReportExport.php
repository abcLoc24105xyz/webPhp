<?php

namespace App\Exports;

use App\Models\Reservation;
use Carbon\Carbon;

class RevenueReportExport
{
    protected $startDate;
    protected $endDate;
    protected $cinemaId;

    public function __construct($startDate, $endDate, $cinemaId = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->cinemaId  = $cinemaId;
    }

    public function export()
    {
        $query = Reservation::where('status', 'paid')
            ->whereBetween('created_at', [
                $this->startDate,
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->with(['show.movie', 'show.cinema', 'user', 'seats']);

        if ($this->cinemaId) {
            $query->whereHas('show', fn($q) => $q->where('cinema_id', $this->cinemaId));
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'DoanhThu_' 
            . Carbon::parse($this->startDate)->format('d-m-Y') 
            . '_den_' 
            . Carbon::parse($this->endDate)->format('d-m-Y') 
            . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($reservations) {
            $file = fopen('php://output', 'w');

            // QUAN TRỌNG: BOM để Excel nhận diện UTF-8 + tách cột đúng
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tiêu đề cột
            fputcsv($file, [
                'Mã Đơn',
                'Khách Hàng',
                'Email',
                'Phim',
                'Rạp',
                'Ngày Suất Chiếu',
                'Giờ Suất Chiếu',
                'Số Vé',
                'Tổng Tiền',
                'Thời Gian Đặt'
            ], ';');   // ← DÙNG DẤU CHẤM PHẨY (;) THAY VÌ DẤU PHẨY (,)

            foreach ($reservations as $r) {
                fputcsv($file, [
                    $r->booking_code ?? '-',
                    $r->user?->full_name ?? 'Khách lẻ',
                    $r->user?->email ?? '-',
                    $r->show?->movie?->title ?? '-',
                    $r->show?->cinema?->cinema_name ?? '-',
                    $r->show?->show_date 
                        ? Carbon::parse($r->show->show_date)->format('d/m/Y') 
                        : '-',
                    $r->show?->start_time ?? '-',
                    $r->seats->count(),
                    $r->total_amount,   // ← để nguyên số, Excel sẽ tự format
                    Carbon::parse($r->created_at)->format('d/m/Y H:i')
                ], ';'); // ← dùng dấu chấm phẩy
            }

            fclose($file);
        }, 200, $headers);
    }
}