<?php

use App\Http\Controllers\InsertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\ChartController;

Route::get('/qrcodescanner', [ScannerController::class, 'qrcodescanner'])->name('qrcodescanner');
Route::post('/qrcodescanner/clear', [ScannerController::class, 'clearQRCodes'])->name('qrcodes.clear');

Route::get('/dashboard', [ScannerController::class, 'dashboard']);
Route::post('/store', [ScannerController::class, 'store'])->name('store');

Route::get('/insert', [InsertController::class, 'insert'])->name('insert');
Route::post('/insert/data', [InsertController::class, 'insertdata'])->name('insert.data');

Route::get ('/api/chart-data/{filter}', [ChartController::class , 'getChartData']);