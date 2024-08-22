<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\ChartController;

Route::get('/qrcodescanner', [ScannerController::class, 'qrcodescanner'])->name('qrcodescanner');
Route::get('/dashboard', [ScannerController::class, 'dashboard']);
Route::post('/store', [ScannerController::class, 'store'])->name('store');
Route::post('/qrcodescanner/clear', [ScannerController::class, 'clearQRCodes'])->name('qrcodes.clear');
Route::get ('/api/chart-data/{filter}', [ChartController::class , 'getChartData']);