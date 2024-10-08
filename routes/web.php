<?php

use App\Http\Controllers\InsertController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RelatorioController;
use App\Http\Middleware\CustomAuthMiddleware;

Route::get('/qrcodescanner', [ScannerController::class, 'qrcodescanner'])->name('qrcodescanner');
Route::post('/qrcodescanner/clear', [ScannerController::class, 'clearQRCodes'])->name('qrcodes.clear');
Route::get('/dashboard', [DataController::class, 'dashboard'])->middleware(CustomAuthMiddleware::class)->name('dashboard');
Route::post('/store', [ScannerController::class, 'store'])->name('store');
Route::get('/enter-manually', [InsertController::class, 'insert'])->name('insert');
Route::get('/enter-manually/search', [InsertController::class, 'getSecao'])->name('getSecao');
Route::get('/enter-manually/{vereadorId}', [InsertController::class, 'getVereador'])->name('getVereador');
Route::post('/enter-manually/data', [InsertController::class, 'insertdata'])->name('insert.data');
Route::get('/secoesrestantes', [InsertController::class, 'getSecoesRestantes'])->name('getSecoesRestantes');
Route::get('/data/{filter}', [DataController::class, 'getData'])->name('getData');
Route::get('/get-bairros', [DataController::class, 'getBairros'])->name('getBairros');
Route::get('/data/bairros/{bairro_id}', [DataController::class, 'getDadosBairro'])->name('getDadosBairro');
Route::get('/get-localidades', [DataController::class, 'getLocalidades'])->name('getLocalidades');
Route::get('/data/localidades/{localidade_id}', [DataController::class, 'getDadosEscola'])->name('getDadosEscola');
Route::get('/get-regioes', [DataController::class, 'getRegioes'])->name('getRegioes');
Route::get('/data/regioes/{regiao}', [DataController::class, 'getDadosRegiao'])->name('getDadosRegiao');
Route::get('/get-partidos', [DataController::class, 'getPartidos'])->name('getPartidos');
Route::get('/data/partidos/{partido}', [DataController::class, 'getVereadoresPorPartido'])->name('getVereadoresPorPartido');
Route::get('/buscar-vereador', [DataController::class, 'getVereador'])->name('buscar.vereador');
Route::get('/main-menu', [MenuController::class, 'menu'])->name('menu');
Route::get('/buscar-por-partido', [DataController::class, 'buscarPartido'])->name('buscarPartido');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/atualizar-dados', [Datacontroller::class, 'atualizarDados']);
Route::get('/relatorios', [RelatorioController::class, 'relatorio'])->name('relatorio');
Route::get('/relatorio-vereador', [RelatorioController::class, 'relatorioVereador'])->name('relatorioVereador');
Route::get('/relatorio-busca', [RelatorioController::class, 'getVereador'])->name('relatorio.busca');
