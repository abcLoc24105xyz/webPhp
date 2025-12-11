<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ShowsImport;
use Illuminate\Support\Facades\Log;

class ShowImportController extends Controller
{
    /**
     * Form import
     */
    public function showImportForm()
    {
        return view('admin.shows.import');
    }

    /**
     * Tải file mẫu
     */
    public function downloadTemplate()
    {
        $file = public_path('templates/import_shows_template.xlsx');

        if (!file_exists($file)) {
            return back()->with('error', 'File mẫu không tồn tại');
        }

        return response()->download($file, 'Mau_Import_SuatChieu.xlsx');
    }

    /**
     * Import Excel
     */
    public function import(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        // Validate
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240'
        ], [
            'excel_file.required' => 'Vui lòng chọn file Excel',
            'excel_file.mimes'    => 'Chỉ chấp nhận file .xlsx hoặc .xls',
            'excel_file.max'      => 'File vượt quá 10MB'
        ]);

        try {
            $import = new ShowsImport();
            $import->import($request->file('excel_file')->getRealPath());

            // Tổng kết
            $summary = [
                'total'   => $import->getSuccessCount() + $import->getUpdatedCount() + count($import->getErrors()) + $import->getSkippedCount(),
                'success' => $import->getSuccessCount(),
                'updated' => $import->getUpdatedCount(),
                'failed'  => count($import->getErrors()),
                'skipped' => $import->getSkippedCount()
            ];

            // Có lỗi
            if ($import->hasErrors()) {
                // Thất bại hoàn toàn - QUAY LẠI trang import
                if ($import->getSuccessCount() === 0 && $import->getUpdatedCount() === 0) {
                    return back()
                        ->with('error', 'Import thất bại. Không có dòng nào được xử lý thành công.')
                        ->with('import_errors', $import->getErrors())
                        ->with('import_summary', $summary)
                        ->withInput();
                }

                // Thành công một phần - QUAY LẠI trang import với cảnh báo
                $successMsg = [];
                if ($import->getSuccessCount() > 0) {
                    $successMsg[] = "tạo mới {$import->getSuccessCount()} suất";
                }
                if ($import->getUpdatedCount() > 0) {
                    $successMsg[] = "cập nhật {$import->getUpdatedCount()} suất";
                }
                
                return back()
                    ->with('warning', "Import hoàn tất với một số lỗi. Đã " . implode(' và ', $successMsg) . ".")
                    ->with('import_errors', $import->getErrors())
                    ->with('import_summary', $summary)
                    ->withInput();
            }

            // Thành công 100% - MỚI chuyển về index
            $message = [];
            if ($import->getSuccessCount() > 0) {
                $message[] = "tạo mới {$import->getSuccessCount()} suất";
            }
            if ($import->getUpdatedCount() > 0) {
                $message[] = "cập nhật {$import->getUpdatedCount()} suất";
            }

            // Nếu chỉ cập nhật (không có tạo mới)
            if ($import->getSuccessCount() === 0 && $import->getUpdatedCount() > 0) {
                return redirect()
                    ->route('admin.shows.index')
                    ->with('info', "Import hoàn tất: Đã " . implode(' và ', $message) . " chiếu!")
                    ->with('import_summary', $summary);
            }

            return redirect()
                ->route('admin.shows.index')
                ->with('success', "Import thành công: Đã " . implode(' và ', $message) . " chiếu!")
                ->with('import_summary', $summary);

        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            return back()
                ->with('error', 'Lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }
}