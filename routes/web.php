<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScannerController;

Route::get('/', function () {return view('/index');});
Route::get('/dashboard', [ScannerController::class, 'dashboard']);

