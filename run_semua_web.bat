@echo off
title TSTH2 Multi-Service Runner (Semua Web)
color 0A

echo =====================================================================
echo          TSTH2 ECOSYSTEM - MENJALANKAN SEMUA LAYANAN WEB
echo =====================================================================
echo.
echo 1. Web Utama & Katalog Flora  : http://127.0.0.1:8000
echo    - Subdomain 1: tsth2.id         (Web Pengunjung & CMS Admin)
echo    - Subdomain 2: katalog.tsth2.id (Katalog Koleksi Tanaman Flora)
echo.
echo 2. Web Logbook Karyawan       : http://127.0.0.1:8001
echo    - Subdomain 4: logbook.tsth2.id (Sistem Presensi & Logbook)
echo.
echo 3. Web Warehouse Terpadu      : http://127.0.0.1:8002
echo    - Subdomain 3: warehouse.tsth2.id (Gudang GA & Lab RdI)
echo.
echo =====================================================================
echo.

echo [1/3] Menjalankan Server Port 8000 (Pengunjung & Katalog Flora)...
start "TSTH2 - Pengunjung & Katalog (Port 8000)" cmd /k "cd /d "%~dp0TSTH2_PART2" && php artisan serve --port=8000"

echo [2/3] Menjalankan Server Port 8001 (Logbook Karyawan)...
start "TSTH2 - Logbook Karyawan (Port 8001)" cmd /k "cd /d "%~dp0logbook_karyawan" && php artisan serve --port=8001"

echo [3/3] Menjalankan Server Port 8002 (Warehouse Gudang & Lab)...
start "TSTH2 - Warehouse Terpadu (Port 8002)" cmd /k "cd /d "%~dp0gudang_tsth2" && php artisan serve --port=8002"

echo.
echo =====================================================================
echo  Semua (3) server web berhasil diluncurkan di terminal terpisah!
echo.
echo  PILIHAN AKSES CEPAT DI BROWSER:
echo  [1] Buka http://127.0.0.1:8000 (Pengunjung & Katalog)
echo  [2] Buka http://127.0.0.1:8001 (Logbook)
echo  [3] Buka http://127.0.0.1:8002 (Warehouse)
echo  [4] Buka KETIGA-TIGANYA sekaligus di browser
echo  [0] Keluar dari installer ini (server tetap jalan)
echo =====================================================================
set /p opt="Pilih opsi buka browser (1/2/3/4/0): "

if "%opt%"=="1" start http://127.0.0.1:8000
if "%opt%"=="2" start http://127.0.0.1:8001
if "%opt%"=="3" start http://127.0.0.1:8002
if "%opt%"=="4" (
    start http://127.0.0.1:8000
    start http://127.0.0.1:8001
    start http://127.0.0.1:8002
)

echo.
echo Selesai. Jangan tutup window terminal server yang sedang berjalan.
pause

