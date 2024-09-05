<?php

namespace App\Http\Controllers;

use App\Charts\Prefeitos;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function dashboard()
    {
        // Replace this with your actual data retrieval logic
        $data = [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'data' => [65, 59, 80, 81, 56],
        ];
        return view('dashboard', compact('data'));
    }
}
