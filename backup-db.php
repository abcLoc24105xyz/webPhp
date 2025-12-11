<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'cinema_db';

$mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe'; 

$dir = __DIR__ . '/storage/backups';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$date = date('Y-m-d_H-i-s');
$file = "$dir/backup_$db-$date.sql";  

$cmd = "\"$mysqldump\" -h$host -u$user ";
if ($pass !== '') $cmd .= "-p$pass ";
$cmd .= "$db --single-transaction --quick --lock-tables=false > \"$file\"";

echo "Đang backup vào: $file\n";
exec($cmd, $output, $result);

if ($result === 0 && file_exists($file)) {
    $size = filesize($file);
    echo "=================================================================\n";
    echo "BACKUP THÀNH CÔNG \n";
    echo "File: $file\n";
    echo "Kích thước: " . number_format($size / 1024 / 1024, 2) . " MB\n";
    echo "=================================================================\n";
} else {
    echo "BACKUP THẤT BẠI! Mã lỗi: $result\n";
    print_r($output);
}