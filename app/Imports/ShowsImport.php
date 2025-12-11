<?php

namespace App\Imports;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Show;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class ShowsImport
{
    private $errors = [];
    private $successCount = 0;
    private $skippedCount = 0;
    private $updatedCount = 0;

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    /**
     * Import file Excel
     */
    public function import($filePath)
    {
        Log::info(">>> BẮT ĐẦU IMPORT SUẤT CHIẾU");

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        if ($highestRow < 2) {
            throw new \Exception("File Excel trống hoặc chỉ có header");
        }

        // Đọc header
        $headerRow = $worksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, false)[0];
        $headers = array_map(function($h) {
            return preg_replace('/[^a-z0-9_]/', '', strtolower(trim($h ?? '')));
        }, $headerRow);

        // Đọc data
        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];

                // Skip dòng trống
                if ($this->isRowEmpty($rowData)) {
                    $this->skippedCount++;
                    continue;
                }

                // Map data với headers
                $data = [];
                foreach ($headers as $index => $header) {
                    $value = $rowData[$index] ?? null;
                    
                    // Convert Excel date/time nếu cần
                    if ($value !== null && is_numeric($value) && $value > 1) {
                        // Thử convert Excel date - nếu lỗi thì giữ nguyên
                        try {
                            $convertedValue = ExcelDate::excelToDateTimeObject($value);
                            $value = $convertedValue;
                        } catch (\Exception $e) {
                            // Giữ nguyên giá trị số
                        }
                    }
                    
                    $data[$header] = $value;
                }

                DB::beginTransaction();
                $this->processRow($data, $row);
                DB::commit();
                
                $this->successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                
                // Xử lý lỗi duplicate key
                if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
                    strpos($e->getMessage(), '1062') !== false) {
                    $this->errors[] = "Dòng {$row}: Suất chiếu này đã tồn tại (trùng lặp)";
                } else {
                    $this->errors[] = "Dòng {$row}: " . $e->getMessage();
                }
            }
        }

        Log::info(">>> HOÀN TẤT: {$this->successCount} tạo mới, {$this->updatedCount} cập nhật, " . 
                  count($this->errors) . " lỗi, {$this->skippedCount} bỏ qua");
    }

    /**
     * Check if row is empty
     */
    private function isRowEmpty($rowData)
    {
        foreach ($rowData as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }
        return true;
    }

    /**
     * Process single row
     */
    private function processRow($data, $rowNumber)
    {
        // Extract values
        $movieTitle = trim($data['movie_title'] ?? $data['movietitle'] ?? '');
        $cinemaName = trim($data['cinema_name'] ?? $data['cinemaname'] ?? '');
        $roomCode   = trim($data['room_code'] ?? $data['roomcode'] ?? '');
        $showDate   = $data['show_date'] ?? $data['showdate'] ?? null;
        $startTime  = $data['start_time'] ?? $data['starttime'] ?? null;
        $remaining  = $data['remaining_seats'] ?? $data['remainingseats'] ?? null;

        // Validate required
        if (!$movieTitle || !$cinemaName || !$roomCode || !$showDate || !$startTime) {
            throw new \Exception("Thiếu dữ liệu bắt buộc");
        }

        // Find movie
        $movie = Movie::whereRaw('LOWER(TRIM(title)) = ?', [strtolower($movieTitle)])->first();
        if (!$movie) {
            throw new \Exception("Không tìm thấy phim '{$movieTitle}'");
        }

        // Find cinema
        $cinema = Cinema::whereRaw('LOWER(TRIM(cinema_name)) = ?', [strtolower($cinemaName)])->first();
        if (!$cinema) {
            throw new \Exception("Không tìm thấy rạp '{$cinemaName}'");
        }

        // Find room
        $room = Room::where('room_code', $roomCode)
                    ->where('cinema_id', $cinema->cinema_id)
                    ->first();
        if (!$room) {
            throw new \Exception("Phòng '{$roomCode}' không tồn tại trong rạp '{$cinemaName}'");
        }

        // Parse date
        $date = $this->parseDate($showDate);
        if (!$date) {
            throw new \Exception("Sai định dạng ngày");
        }

        // Parse time
        $time = $this->parseTime($startTime);
        if (!$time) {
            throw new \Exception("Sai định dạng giờ");
        }

        // Calculate end time
        $endTime = $time->copy()->addMinutes($movie->duration);

        // Validate remaining seats
        $seats = $room->total_seats;
        if ($remaining !== null && $remaining !== '') {
            if (!is_numeric($remaining)) {
                throw new \Exception("remaining_seats phải là số");
            }
            $seats = (int)$remaining;
            if ($seats < 0 || $seats > $room->total_seats) {
                throw new \Exception("remaining_seats không hợp lệ");
            }
        }

        // Generate show_id
        $dateStr = $date->format('Ymd');
        $existing = Show::where('show_date', $date->format('Y-m-d'))
            ->where('cinema_id', $cinema->cinema_id)
            ->count();
        $seq = str_pad($existing + 1, 3, '0', STR_PAD_LEFT);
        $showId = "SHOW{$dateStr}{$seq}";

        // Kiểm tra xem đã tồn tại chưa (check đầy đủ các trường)
        $existingShow = Show::where('movie_id', $movie->movie_id)
            ->where('cinema_id', $cinema->cinema_id)
            ->where('room_code', $roomCode)
            ->where('show_date', $date->format('Y-m-d'))
            ->where('start_time', $time->format('H:i:s'))
            ->first();

        if ($existingShow) {
            // Đã tồn tại -> cập nhật
            $existingShow->update([
                'end_time'        => $endTime->format('H:i:s'),
                'remaining_seats' => $seats,
            ]);
            $this->updatedCount++;
            Log::info("Updated show: {$existingShow->show_id}");
        } else {
            // Kiểm tra show_id có bị trùng không
            $showIdExists = Show::where('show_id', $showId)->exists();
            
            if ($showIdExists) {
                // Show_id bị trùng, tăng sequence
                $retryCount = 0;
                do {
                    $retryCount++;
                    $seq = str_pad($existing + 1 + $retryCount, 3, '0', STR_PAD_LEFT);
                    $showId = "SHOW{$dateStr}{$seq}";
                    $showIdExists = Show::where('show_id', $showId)->exists();
                } while ($showIdExists && $retryCount < 10);
                
                if ($showIdExists) {
                    throw new \Exception("Không thể tạo show_id duy nhất");
                }
            }
            
            // Tạo mới
            Show::create([
                'show_id'         => $showId,
                'movie_id'        => $movie->movie_id,
                'cinema_id'       => $cinema->cinema_id,
                'room_code'       => $roomCode,
                'show_date'       => $date->format('Y-m-d'),
                'start_time'      => $time->format('H:i:s'),
                'end_time'        => $endTime->format('H:i:s'),
                'remaining_seats' => $seats,
            ]);
            Log::info("Created show: {$showId}");
        }
    }

    /**
     * Parse date
     */
    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            if ($value instanceof \DateTime) {
                return Carbon::instance($value);
            }

            if (is_numeric($value) && $value > 1) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            }

            $value = trim((string)$value);

            // dd/mm/yyyy
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value);
            }

            // yyyy-mm-dd
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
                return Carbon::createFromFormat('Y-m-d', $value);
            }

            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse time
     */
    private function parseTime($value)
    {
        if (!$value) return null;

        try {
            if ($value instanceof \DateTime) {
                return Carbon::createFromTime($value->format('H'), $value->format('i'), $value->format('s'));
            }

            if (is_numeric($value) && $value < 1 && $value >= 0) {
                $seconds = (int)($value * 86400);
                return Carbon::today()->startOfDay()->addSeconds($seconds);
            }

            $value = trim((string)$value);
            $value = preg_replace('/^\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4}\s+/', '', $value);

            if (preg_match('/^(\d{1,2}):(\d{2})(:\d{2})?$/', $value)) {
                $format = strlen($value) > 5 ? 'H:i:s' : 'H:i';
                return Carbon::createFromFormat($format, $value);
            }

            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}