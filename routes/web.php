<?php

use App\Http\Controllers\InsertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\DataController;

Route::get('/qrcodescanner', [ScannerController::class, 'qrcodescanner'])->name('qrcodescanner');
Route::post('/qrcodescanner/clear', [ScannerController::class, 'clearQRCodes'])->name('qrcodes.clear');

Route::get('/', [ScannerController::class, 'dashboard']);
Route::post('/store', [ScannerController::class, 'store'])->name('store');

Route::get('/enter-manually', [InsertController::class, 'insert'])->name('insert');
Route::post('/enter-manually/data', [InsertController::class, 'insertdata'])->name('insert.data');

Route::get('/data/{filter}', [DataController::class, 'getData'])->name('getData');
