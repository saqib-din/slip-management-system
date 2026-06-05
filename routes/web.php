<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SlipController;
use App\Http\Controllers\ManagementController;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/slips/datatable', [SlipController::class, 'datatable'])->name('slips.datatable');
    Route::get('/slips/export', [SlipController::class, 'export'])->name('slips.export');
    Route::get('/slips/{id}/pdf',    [SlipController::class, 'pdf'])->name('slips.pdf');
    // Full resource (includes show, index, store, update, destroy)
    Route::resource('slips', SlipController::class)->except(['create', 'edit']);

    Route::get('/management', [ManagementController::class, 'index'])->name('management.index');

    // Companies
    Route::post('/management/companies', [ManagementController::class, 'storeCompany'])->name('companies.store');
    Route::put('/management/companies/{company}', [ManagementController::class, 'updateCompany'])->name('companies.update');
    Route::delete('/management/companies/{company}', [ManagementController::class, 'destroyCompany'])->name('companies.destroy');

    // Materials
    Route::post('/management/materials', [ManagementController::class, 'storeMaterial'])->name('materials.store');
    Route::put('/management/materials/{material}', [ManagementController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('/management/materials/{material}', [ManagementController::class, 'destroyMaterial'])->name('materials.destroy');

    Route::get('/users',                  [UserController::class, 'index'])->name('users.index');
    Route::post('/users',                 [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit-data', [UserController::class, 'editData'])->name('users.editData');
    Route::put('/users/{user}',           [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',        [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle',   [UserController::class, 'toggle'])->name('admin.users.toggle');
});

require __DIR__ . '/auth.php';
