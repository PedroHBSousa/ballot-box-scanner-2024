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
Route::get('/enter-manually/search', [InsertController::class, 'getSecao'])->name('getSecao');
Route::get('/enter-manually/{vereadorId}', [InsertController::class, 'getVereador'])->name('getVereador');
Route::post('/enter-manually/data', [InsertController::class, 'insertdata'])->name('insert.data');

Route::get('/data/{filter}', [DataController::class, 'getData'])->name('getData');

Route::get('/get-bairros', [DataController::class, 'getBairros'])->name('getBairros');
Route::get('/data/bairros/{bairro_id}', [DataController::class, 'getDadosBairro'])->name('getDadosBairro');

Route::get('/get-localidades', [DataController::class, 'getLocalidades'])->name('getLocalidades');
Route::get('/data/localidades/{localidade_id}', [DataController::class, 'getDadosEscola'])->name('getDadosEscola');

Route::get('/get-regioes', [DataController::class, 'getRegioes'])->name('getRegioes');
Route::get('/data/regioes/{regiao}', [DataController::class, 'getDadosRegiao'])->name('getDadosRegiao');
