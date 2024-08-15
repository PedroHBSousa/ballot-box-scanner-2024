<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScannerController extends Controller
{
    public function index()
    {
        return view('index', );
    }

    public function dashboard () {

        return view('dashboard', );
    }
    public function store (Request $request) 
    {
       dd($request->all());
    }
}


