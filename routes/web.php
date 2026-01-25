<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\ShowDepartments::class)->name('home');
Route::get('/departamento/{slug}', \App\Livewire\ShowDepartmentCompanies::class)->name('department.show');
Route::get('/municipio/{slug}', \App\Livewire\ShowMunicipioCompanies::class)->name('municipio.show');
Route::get('/empresa/{slug}', \App\Livewire\ShowCompany::class)->name('company.show');
Route::get('/departamento/{dept_slug}/categoria/{cat_slug}', \App\Livewire\ShowDepartmentCategory::class)->name('department.category.show');
Route::get('/municipio/{muni_slug}/categoria/{cat_slug}', \App\Livewire\ShowMunicipioCategory::class)->name('municipio.category.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
