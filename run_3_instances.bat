@echo off
title TSTH2 3-Instance Runner
echo ============================================================
echo   Menjalankan 3 Instance Laravel TSTH2 Secara Bersamaan
echo ============================================================
echo 1. Pengunjung & Katalog (pengunjung) : http://127.0.0.1:8000
echo 2. Sistem Logbook (logbook)          : http://127.0.0.1:8001
echo 3. Sistem Gudang (gudang)            : http://127.0.0.1:8002
echo ============================================================

start "TSTH2 - Pengunjung & Katalog (Port 8000)" cmd /k "cd /d e:\Del\Semester 7\MAGANG\TSTH2_PART2 && php artisan serve --port=8000"
start "TSTH2 - Logbook Karyawan (Port 8001)" cmd /k "cd /d e:\Del\Semester 7\MAGANG\logbook_karyawan && php artisan serve --port=8001"
start "TSTH2 - Sistem Gudang (Port 8002)" cmd /k "cd /d e:\Del\Semester 7\MAGANG\gudang_tsth2 && php artisan serve --port=8002"

echo Ketiga server telah diluncurkan di window terminal terpisah.
pause
