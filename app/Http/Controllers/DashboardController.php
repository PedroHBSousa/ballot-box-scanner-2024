<?php

namespace App\Http\Controllers;

use App\Charts\Prefeitos;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $chart = new Prefeitos;
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        $chart->dataset('My dataset', 'line', [1, 2, 3, 4]);
        $chart->dataset('My dataset 2', 'line', [4, 3, 2, 1]);
        return view('dashboard', compact('chart'));
    }
}
