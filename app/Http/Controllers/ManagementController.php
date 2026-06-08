<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagementController extends Controller
{
    public function index()
    {
        return Inertia::render('Management/Index', [
            'companies'  => Company::orderBy('name')->get(['id', 'name']),
            'materials'  => Material::orderBy('name')->get(['id', 'name']),
            'totalUsers' => User::count(),
        ]);
    }

    // ── Companies ──────────────────────────────────

    public function storeCompany(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:companies,name']);

        Company::create(['name' => trim($request->name)]);

        return back()->with('success', 'Company added.');
    }

    public function updateCompany(Request $request, Company $company)
    {
        $request->validate(['name' => 'required|string|unique:companies,name,' . $company->id]);

        $company->update(['name' => trim($request->name)]);

        return back()->with('success', 'Company updated.');
    }

    public function destroyCompany(Company $company)
    {
        $company->delete();

        return back()->with('success', 'Company deleted.');
    }

    // ── Materials ──────────────────────────────────

    public function storeMaterial(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:materials,name']);

        Material::create(['name' => trim($request->name)]);

        return back()->with('success', 'Material added.');
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate(['name' => 'required|string|unique:materials,name,' . $material->id]);

        $material->update(['name' => trim($request->name)]);

        return back()->with('success', 'Material updated.');
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();

        return back()->with('success', 'Material deleted.');
    }
}
