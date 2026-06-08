<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Material;
use App\Models\User;
use App\Models\Slip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $totalSlips    = Slip::count();
        $totalCompany  = Company::count();
        $totalMaterial = Material::count();

        return Inertia::render('Dashboard', compact(
            'totalUsers',
            'totalSlips',
            'totalCompany',
            'totalMaterial'
        ));
    }
}
