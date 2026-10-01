```bat
@echo off
title Alibaton Construction Inc. - Payroll System

echo =================================================
echo   ALIBATON CONSTRUCTION INC.
echo   Payroll and Benefits System
echo =================================================
echo.
echo Starting Laravel Microservices...
echo.

REM =================================================
REM START EMPLOYEE SERVICE
REM =================================================
echo [1/8] Starting Employee Service - Port 8001...
start "Employee Service - 8001" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\employee-service && php artisan serve --host=127.0.0.1 --port=8001"

timeout /t 3 /nobreak >nul

REM =================================================
REM START PAYROLL SERVICE
REM =================================================
echo [2/8] Starting Payroll Service - Port 8002...
start "Payroll Service - 8002" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\payroll-service && php artisan serve --host=127.0.0.1 --port=8002"

timeout /t 3 /nobreak >nul

REM =================================================
REM START BENEFITS SERVICE
REM =================================================
echo [3/8] Starting Benefits Service - Port 8003...
start "Benefits Service - 8003" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\benefits-service && php artisan serve --host=127.0.0.1 --port=8003"

timeout /t 3 /nobreak >nul

REM =================================================
REM START CLAIMS SERVICE
REM =================================================
echo [4/8] Starting Claims Service - Port 8004...
start "Claims Service - 8004" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\claims-service && php artisan serve --host=127.0.0.1 --port=8004"

timeout /t 3 /nobreak >nul

REM =================================================
REM START COMPENSATION SERVICE
REM =================================================
echo [5/8] Starting Compensation Service - Port 8005...
start "Compensation Service - 8005" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\compensation-service && php artisan serve --host=127.0.0.1 --port=8005"

timeout /t 3 /nobreak >nul

REM =================================================
REM START ANALYTICS SERVICE
REM =================================================
echo [6/8] Starting Analytics Service - Port 8006...
start "Analytics Service - 8006" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\analytics-service && php artisan serve --host=127.0.0.1 --port=8006"

timeout /t 3 /nobreak >nul

REM =================================================
REM START API GATEWAY
REM =================================================
echo [7/8] Starting API Gateway - Port 8000...
start "API Gateway - 8000" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\api-gateway && php artisan serve --host=127.0.0.1 --port=8000"

timeout /t 5 /nobreak >nul

REM =================================================
REM START FRONTEND
REM =================================================
echo [8/8] Starting Frontend - Port 9000...
start "Frontend - 9000" cmd /k "cd /d C:\xampp\htdocs\payroll-microservices\frontend && php artisan serve --host=127.0.0.1 --port=9000"

timeout /t 5 /nobreak >nul

echo.
echo =================================================
echo   ALL SERVICES HAVE BEEN STARTED
echo =================================================
echo.
echo   Frontend:
echo   http://127.0.0.1:9000
echo.
echo   API Gateway:
echo   http://127.0.0.1:8000
echo.
echo   Employee Service:
echo   http://127.0.0.1:8001
echo.
echo   Payroll Service:
echo   http://127.0.0.1:8002
echo.
echo   Benefits Service:
echo   http://127.0.0.1:8003
echo.
echo   Claims Service:
echo   http://127.0.0.1:8004
echo.
echo   Compensation Service:
echo   http://127.0.0.1:8005
echo.
echo   Analytics Service:
echo   http://127.0.0.1:8006
echo.
echo =================================================
echo   Opening Payroll System...
echo =================================================
echo.

start http://127.0.0.1:9000

exit
```
