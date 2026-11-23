@echo off
setlocal enabledelayedexpansion
title TSTH2 Ecosystem Runner - 4 Layanan Terpadu
color 0A

:: Deteksi Direktori Script
set "ROOT=%~dp0"

:: Deteksi PHP Executable (Fallback otomatis ke XAMPP jika belum ada di System PATH)
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\xampp\php\php.exe" (
        set "PATH=C:\xampp\php;!PATH!"
        echo [*] PHP terdeteksi di C:\xampp\php dan otomatis ditambahkan ke sesi ini.
    ) else (
        echo [!] PERINGATAN: PHP tidak ditemukan di PATH maupun di C:\xampp\php.
    )
)

:MENU_START
cls
echo =====================================================================
echo          TSTH2 ECOSYSTEM - PENGELOLA 4 LAYANAN TERPADU
echo =====================================================================
echo.
echo   Folder Kerja : %ROOT%
echo.
echo   Daftar Layanan:
echo   [1] Web Pengunjung & CMS Admin : http://127.0.0.1:8000 (tsth2.id)
echo   [2] Web Logbook Karyawan       : http://127.0.0.1:8001 (logbook.tsth2.id)
echo   [3] Web Warehouse Gudang & Lab : http://127.0.0.1:8002 (warehouse.tsth2.id)
echo   [4] Web Katalog Flora Herbal   : http://127.0.0.1:8003 (katalog.tsth2.id)
echo.
echo =====================================================================
echo.
echo  PILIHAN TINDAKAN:
echo   [S] JALANKAN SEMUA (4) SERVER WEB SEKARANG
echo   [K] Hentikan (Kill) semua proses server PHP
echo   [R] Restart semua server (Hentikan lalu jalankan ulang)
echo.
echo  PINTASAN BUKA DI BROWSER:
echo   [1] Buka http://127.0.0.1:8000 (Web Pengunjung)
echo   [2] Buka http://127.0.0.1:8001 (Logbook Karyawan)
echo   [3] Buka http://127.0.0.1:8002 (Warehouse Gudang)
echo   [4] Buka http://127.0.0.1:8003 (Katalog Tanaman)
echo   [5] Buka KEEMPAT-EMPATNYA sekaligus di browser
echo   [0] Keluar (Window server tetap berjalan)
echo.
echo =====================================================================
set "opt="
set /p opt="Pilih opsi menu [S/K/R/1/2/3/4/5/0]: "

if /i "!opt!"=="S" goto RUN_SERVERS
if /i "!opt!"=="K" goto KILL_SERVERS
if /i "!opt!"=="R" goto RESTART_SERVERS

if "!opt!"=="1" (
    start http://127.0.0.1:8000
    goto MENU_START
)
if "!opt!"=="2" (
    start http://127.0.0.1:8001
    goto MENU_START
)
if "!opt!"=="3" (
    start http://127.0.0.1:8002
    goto MENU_START
)
if "!opt!"=="4" (
    start http://127.0.0.1:8003
    goto MENU_START
)
if "!opt!"=="5" (
    start http://127.0.0.1:8000
    start http://127.0.0.1:8001
    start http://127.0.0.1:8002
    start http://127.0.0.1:8003
    goto MENU_START
)
if "!opt!"=="0" goto EXIT_PROMPT

echo Pilihan tidak valid. Silakan ulangi.
timeout /t 2 >nul
goto MENU_START

:RUN_SERVERS
echo.
echo =====================================================================
echo  Memulai peluncuran 4 server web TSTH2...
echo =====================================================================

:: Server 1: Pengunjung (Port 8000)
echo [1/4] Menjalankan Server Port 8000 (Pengunjung & CMS Admin)...
start "TSTH2 - Pengunjung (Port 8000)" /D "%ROOT%pengunjung" cmd /k "title TSTH2 - Pengunjung (Port 8000) & color 0B & php artisan serve --port=8000"

:: Server 2: Logbook (Port 8001)
echo [2/4] Menjalankan Server Port 8001 (Logbook Karyawan)...
start "TSTH2 - Logbook (Port 8001)" /D "%ROOT%logbook" cmd /k "title TSTH2 - Logbook (Port 8001) & color 0A & php artisan serve --port=8001"

:: Server 3: Warehouse (Port 8002)
echo [3/4] Menjalankan Server Port 8002 (Warehouse Gudang & Lab)...
start "TSTH2 - Warehouse (Port 8002)" /D "%ROOT%warehouse" cmd /k "title TSTH2 - Warehouse (Port 8002) & color 0E & php artisan serve --port=8002"

:: Server 4: Katalog (Port 8003)
echo [4/4] Menjalankan Server Port 8003 (Katalog Flora Herbal)...
start "TSTH2 - Katalog (Port 8003)" /D "%ROOT%katalog" cmd /k "title TSTH2 - Katalog (Port 8003) & color 0D & php artisan serve --port=8003"

echo.
echo [V] Keempat server web berhasil diluncurkan di terminal terpisah!
echo.
set "open_browser="
set /p open_browser="Apakah ingin langsung membuka ke-4 web di browser? (Y/N): "
if /i "!open_browser!"=="Y" (
    start http://127.0.0.1:8000
    start http://127.0.0.1:8001
    start http://127.0.0.1:8002
    start http://127.0.0.1:8003
)
goto MENU_START

:KILL_SERVERS
echo.
echo [!] Menghentikan seluruh proses server PHP...
taskkill /f /im php.exe >nul 2>&1
echo [V] Semua server PHP berhasil dihentikan.
timeout /t 2 >nul
goto MENU_START

:RESTART_SERVERS
echo.
echo [!] Menghentikan server lama...
taskkill /f /im php.exe >nul 2>&1
timeout /t 1 >nul
goto RUN_SERVERS

:EXIT_PROMPT
echo.
echo Selesai. Server tetap berjalan di jendela terminal masing-masing.
timeout /t 2 >nul
exit
