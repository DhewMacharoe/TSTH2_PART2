@echo off
title TSTH2 Multi-Service Runner (4 Layanan Terpadu)
color 0A

echo =====================================================================
echo          TSTH2 ECOSYSTEM - MENJALANKAN 4 LAYANAN TERPADU
echo =====================================================================
echo.
echo 1. Web Pengunjung & CMS Admin : http://127.0.0.1:8000 (Subdomain: tsth2.id)
echo 2. Web Logbook Karyawan       : http://127.0.0.1:8001 (Subdomain: logbook.tsth2.id)
echo 3. Web Warehouse Gudang       : http://127.0.0.1:8002 (Subdomain: warehouse.tsth2.id)
echo 4. Web Katalog Flora Herbal   : http://127.0.0.1:8003 (Subdomain: katalog.tsth2.id)
echo.
echo =====================================================================
echo.

echo [1/4] Menjalankan Server Port 8000 (Web Pengunjung & Admin CMS)...
start "TSTH2 - Pengunjung (Port 8000)" cmd /k "cd /d "%~dp0pengunjung" && php artisan serve --port=8000"

echo [2/4] Menjalankan Server Port 8001 (Logbook Karyawan)...
start "TSTH2 - Logbook (Port 8001)" cmd /k "cd /d "%~dp0logbook" && php artisan serve --port=8001"

echo [3/4] Menjalankan Server Port 8002 (Warehouse Gudang & Lab)...
start "TSTH2 - Warehouse (Port 8002)" cmd /k "cd /d "%~dp0warehouse" && php artisan serve --port=8002"

echo [4/4] Menjalankan Server Port 8003 (Katalog Flora Herbal)...
start "TSTH2 - Katalog (Port 8003)" cmd /k "cd /d "%~dp0katalog" && php artisan serve --port=8003"

echo.
echo =====================================================================
echo  Semua (4) server web berhasil diluncurkan di terminal terpisah!
echo.
echo  PILIHAN AKSES CEPAT DI BROWSER:
echo  [1] Buka http://127.0.0.1:8000 (Web Pengunjung)
echo  [2] Buka http://127.0.0.1:8001 (Logbook Karyawan)
echo  [3] Buka http://127.0.0.1:8002 (Warehouse Gudang)
echo  [4] Buka http://127.0.0.1:8003 (Katalog Tanaman)
echo  [5] Buka KEEMPAT-EMPATNYA sekaligus di browser
echo  [0] Keluar (server tetap berjalan)
echo =====================================================================
set /p opt="Pilih opsi buka browser (1/2/3/4/5/0): "

if "%opt%"=="1" start http://127.0.0.1:8000
if "%opt%"=="2" start http://127.0.0.1:8001
if "%opt%"=="3" start http://127.0.0.1:8002
if "%opt%"=="4" start http://127.0.0.1:8003
if "%opt%"=="5" (
    start http://127.0.0.1:8000
    start http://127.0.0.1:8001
    start http://127.0.0.1:8002
    start http://127.0.0.1:8003
)

echo.
echo Selesai. Jangan tutup window terminal server yang sedang berjalan.
pause
