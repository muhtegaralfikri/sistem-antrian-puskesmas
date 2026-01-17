@echo off
REM Sistem Antrian Puskesmas - Local Development Server
REM

echo ========================================
echo  Sistem Antrian Puskesmas
echo  Starting Development Server...
echo ========================================
echo.

REM Set PHP path
set PHP_PATH=D:\php-portable\php.exe
set PROJECT_DIR=D:\github_project\sistem-antrian-puskesmas

echo PHP: %PHP_PATH%
echo Project: %PROJECT_DIR%
echo.

REM Start WebSocket Server in background
echo [1/2] Starting WebSocket Server on port 8080...
start /B "" "%PHP_PATH%" "%PROJECT_DIR%\spark" websocket:start
timeout /t 2 /nobreak >nul

REM Start PHP Built-in Server
echo [2/2] Starting Web Server on http://localhost:8000
echo.
echo ========================================
echo  SERVER READY!
echo ========================================
echo  Kiosk:    http://localhost:8000/kiosk
echo  Display:  http://localhost:8000/display
echo  Dashboard: http://localhost:8000/dashboard
echo  Admin:    http://localhost:8000/admin
echo  Login:    http://localhost:8000/auth/login
echo.
echo  Username: admin
echo  Password: admin123
echo ========================================
echo.
echo Press Ctrl+C to stop all servers
echo.

"%PHP_PATH%" -S localhost:8000 -t "%PROJECT_DIR%\public"
