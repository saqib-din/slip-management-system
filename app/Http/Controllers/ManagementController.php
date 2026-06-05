<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        $totalUsers = User::count();

        return view('pages.admin.management.index', compact(
            'companies',
            'materials',
            'totalUsers'
        ));
    }

    // ── Companies ──────────────────────────────────

    public function storeCompany(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:companies,name']);

        $company = Company::create(['name' => trim($request->name)]);

        return response()->json($company);
    }

    public function updateCompany(Request $request, Company $company)
    {
        $request->validate(['name' => 'required|string|unique:companies,name,' . $company->id]);

        $company->update(['name' => trim($request->name)]);

        return response()->json($company->fresh());
    }

    public function destroyCompany(Company $company)
    {
        $company->delete();

        return response()->json(['success' => true]);
    }

    // ── Materials ──────────────────────────────────

    public function storeMaterial(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:materials,name']);

        $material = Material::create(['name' => trim($request->name)]);

        return response()->json($material);
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate(['name' => 'required|string|unique:materials,name,' . $material->id]);

        $material->update(['name' => trim($request->name)]);

        return response()->json($material->fresh());
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();

        return response()->json(['success' => true]);
    }
}
