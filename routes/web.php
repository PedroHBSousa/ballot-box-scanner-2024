<?php

use App\Http\Controllers\InsertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\ChartController;

Route::get('/qrcodescanner', [ScannerController::class, 'qrcodescanner'])->name('qrcodescanner');
Route::get('/dashboard', [ScannerController::class, 'dashboard']);
Route::post('/store', [ScannerController::class, 'store'])->name('store');
Route::post('/qrcodescanner/clear', [ScannerController::class, 'clearQRCodes'])->name('qrcodes.clear');
Route::get('/insert', [InsertController::class, 'insert']);
Route::get('/get-chart-data/{filter}', [ChartController::class, 'getChartData']);