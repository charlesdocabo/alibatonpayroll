@echo off
title Alibaton Construction Inc. - Stop Payroll System

echo ================================================
echo   ALIBATON CONSTRUCTION INC.
echo   Payroll and Benefits System
echo ================================================
echo.
echo Stopping Laravel Microservices...
echo.

taskkill /FI "WINDOWTITLE eq Employee Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Payroll Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Benefits Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Claims Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Compensation Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Analytics Service*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq API Gateway*" /T /F >nul 2>&1
taskkill /FI "WINDOWTITLE eq Frontend*" /T /F >nul 2>&1

echo.
echo ================================================
echo   PAYROLL SYSTEM STOPPED
echo ================================================
echo.
pause