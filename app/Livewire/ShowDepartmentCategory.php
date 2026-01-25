<?php

namespace App\Livewire;

use Livewire\Component;

class ShowDepartmentCategory extends Component
{
    public $department;
    public $category;
    public $search = '';

    public function mount($dept_slug, $cat_slug)
    {
        $this->department = \App\Models\Departamento::where('slug', $dept_slug)->firstOrFail();
        $this->category = \App\Models\Categoria::where('slug', $cat_slug)->where('estado', 1)->firstOrFail();
    }

    public function render()
    {
        $companies = \App\Models\Empresa::with(['direccion.municipio', 'rubros'])
            ->where('estado', 'ACTIVO')
            ->whereHas('direccion.municipio.provincia.departamento', function ($q) {
                $q->where('departamentos.id', $this->department->id);
            })
            ->whereHas('categorias', function ($q) {
                $q->where('categorias.id', $this->category->id);
            })
            ->when($this->search, function ($q) {
                $q->where('razon_social', 'like', '%' . $this->search . '%')
                    ->orWhere('nit', 'like', '%' . $this->search . '%');
            })
            ->paginate(36);

        return view('livewire.show-department-category', [
            'companies' => $companies
        ])->layout('layouts.site', [
                    'title' => $this->category->nombre . ' en ' . $this->department->nombre_publico ?? $this->department->nombre_interno
                ]);
    }
}
