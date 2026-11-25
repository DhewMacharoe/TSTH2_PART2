@echo off
title TSTH2 Ecosystem Runner - 4 Layanan Web
color 0A

:: 1. Resolusi Base Directory yang aman untuk path berspasi
for %%I in ("%~dp0.") do set "BASE_DIR=%%~fI"

:: 2. Deteksi PHP Executable (Fallback ke XAMPP jika belum ada di System PATH)
where php >nul 2>&1
if %ERRORLEVEL% EQU 0 goto CHECK_MYSQL

if exist "C:\xampp\php\php.exe" (
    set "PATH=C:\xampp\php;%PATH%"
    echo [*] Menemukan PHP di C:\xampp\php - ditambahkan ke sesi ini.
    goto CHECK_MYSQL
)

if exist "D:\xampp\php\php.exe" (
    set "PATH=D:\xampp\php;%PATH%"
    echo [*] Menemukan PHP di D:\xampp\php - ditambahkan ke sesi ini.
    goto CHECK_MYSQL
)

echo [!] PERINGATAN: PHP tidak ditemukan di PATH maupun di C:\xampp\php.
echo     Pastikan XAMPP atau PHP sudah terinstal pada komputer ini.
echo.

:: 3. Deteksi dan jalankan MySQL jika belum aktif
:CHECK_MYSQL
netstat -ano | findstr :3306 >nul 2>&1
if %ERRORLEVEL% EQU 0 goto START_SERVERS

echo [*] MySQL Port 3306 belum aktif. Mencoba menyalakan MySQL XAMPP...
if exist "C:\xampp\mysql\bin\mysqld.exe" (
    start "MySQL Daemon" /min "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
    ping 127.0.0.1 -n 3 >nul
    goto START_SERVERS
)

if exist "C:\xampp\mysql_start.bat" (
    start "MySQL" /min cmd /c "C:\xampp\mysql_start.bat"
    ping 127.0.0.1 -n 3 >nul
    goto START_SERVERS
)

:: 4. Jalankan keempat server web secara otomatis
:START_SERVERS
echo =====================================================================
echo          MENJALANKAN KEEMPAT (4) LAYANAN WEB TSTH2...
echo =====================================================================
echo.

echo [*] Menjalankan Web Pengunjung (Port 8000)...
start "TSTH2 [Port 8000] - Web Pengunjung" /D "%BASE_DIR%\pengunjung" cmd /k "php artisan serve --host=127.0.0.1 --port=8000"

echo [*] Menjalankan Web Logbook (Port 8001)...
start "TSTH2 [Port 8001] - Web Logbook" /D "%BASE_DIR%\logbook" cmd /k "php artisan serve --host=127.0.0.1 --port=8001"

echo [*] Menjalankan Web Warehouse (Port 8002)...
start "TSTH2 [Port 8002] - Web Warehouse" /D "%BASE_DIR%\warehouse" cmd /k "php artisan serve --host=127.0.0.1 --port=8002"

echo [*] Menjalankan Web Katalog (Port 8003)...
start "TSTH2 [Port 8003] - Web Katalog" /D "%BASE_DIR%\katalog" cmd /k "php artisan serve --host=127.0.0.1 --port=8003"

echo.
echo [V] Keempat server web berhasil dijalankan!
ping 127.0.0.1 -n 3 >nul

:MENU_START
cls
echo =====================================================================
echo          TSTH2 ECOSYSTEM - 4 LAYANAN WEB TELAH BERJALAN
echo =====================================================================
echo.
echo   Folder Kerja : %BASE_DIR%
echo.
echo   Daftar Layanan:
echo   [1] Web Pengunjung (Port 8000)  : http://127.0.0.1:8000
echo   [2] Web Logbook Karyawan (8001) : http://127.0.0.1:8001
echo   [3] Web Warehouse Gudang (8002) : http://127.0.0.1:8002
echo   [4] Web Katalog Flora (8003)    : http://127.0.0.1:8003
echo.
echo =====================================================================
echo   PINTASAN BUKA DI BROWSER:
echo   [B] Buka KEEMPAT web sekaligus di browser
echo   [1] Buka Web Pengunjung (Port 8000)
echo   [2] Buka Web Logbook (Port 8001)
echo   [3] Buka Web Warehouse (Port 8002)
echo   [4] Buka Web Katalog (Port 8003)
echo.
echo   MANAJEMEN SERVER:
echo   [R] Restart semua server (Kill lalu jalankan ulang)
echo   [K] Hentikan (Kill) semua proses PHP server
echo   [0] Keluar (Window server tetap berjalan di latar belakang)
echo =====================================================================
echo.

set "CHOICE="
set /p "CHOICE=Pilih menu [B/1/2/3/4/R/K/0]: "
if not defined CHOICE goto EXIT_SCRIPT

set "C=%CHOICE: =%"
if /i "%C%"=="B" goto OPEN_ALL
if /i "%C%"=="1" goto OPEN_1
if /i "%C%"=="2" goto OPEN_2
if /i "%C%"=="3" goto OPEN_3
if /i "%C%"=="4" goto OPEN_4
if /i "%C%"=="R" goto RESTART_SERVERS
if /i "%C%"=="K" goto KILL_SERVERS
if /i "%C%"=="0" goto EXIT_SCRIPT
if /i "%C%"=="Q" goto EXIT_SCRIPT

echo [!] Pilihan tidak valid.
ping 127.0.0.1 -n 2 >nul
goto MENU_START

:OPEN_ALL
start http://127.0.0.1:8000
start http://127.0.0.1:8001
start http://127.0.0.1:8002
start http://127.0.0.1:8003
goto MENU_START

:OPEN_1
start http://127.0.0.1:8000
goto MENU_START

:OPEN_2
start http://127.0.0.1:8001
goto MENU_START

:OPEN_3
start http://127.0.0.1:8002
goto MENU_START

:OPEN_4
start http://127.0.0.1:8003
goto MENU_START

:KILL_SERVERS
echo.
echo [*] Menghentikan semua proses server PHP...
taskkill /F /IM php.exe /T >nul 2>&1
echo [V] Semua server PHP berhasil dihentikan.
ping 127.0.0.1 -n 3 >nul
goto MENU_START

:RESTART_SERVERS
echo.
echo [*] Menghentikan semua server terlebih dahulu...
taskkill /F /IM php.exe /T >nul 2>&1
ping 127.0.0.1 -n 2 >nul
goto START_SERVERS

:EXIT_SCRIPT
echo.
echo Keluar dari runner. Window server tetap aktif.
exit /b 0
