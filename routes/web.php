<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\ShowDepartments::class)->name('home');
Route::get('/empresas/{slug}', \App\Livewire\ShowDepartmentCompanies::class)->name('department.show');
Route::get('/empresas/{departamento_slug}/{slug}', \App\Livewire\ShowMunicipioCompanies::class)->name('municipio.show');
Route::get('/empresa/{slug}', \App\Livewire\ShowCompany::class)->name('company.show');

Route::get('/categoria/{departamento_slug}/{slug}', \App\Livewire\ShowDepartmentCategory::class)->name('department.category.show');
Route::get('/departamento/{departamento_slug}/categoria/{slug}', function ($departamento_slug, $slug) {
    return redirect()->route('department.category.show', [
        'departamento_slug' => $departamento_slug,
        'slug' => $slug,
    ], 301);
});

Route::get('/categoria/{departamento_slug}/{municipio_slug}/{slug}', \App\Livewire\ShowMunicipioCategory::class)->name('municipio.category.show');
Route::get('/municipio/{municipio_slug}/categoria/{slug}', function ($municipio_slug, $slug) {
    $municipio = \App\Models\Municipio::where('slug', $municipio_slug)->firstOrFail();
    $departamento_slug = $municipio->provincia->departamento->slug;

    return redirect()->route('municipio.category.show', [
        'departamento_slug' => $departamento_slug,
        'municipio_slug' => $municipio_slug,
        'slug' => $slug,
    ], 301);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
