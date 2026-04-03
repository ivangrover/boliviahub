<?php

namespace App\Livewire;

use Livewire\Component;

class ShowDepartmentCompanies extends Component
{
    public $department;
    public $search = '';
    public $selectedMunicipio = '';

    public function mount($slug)
    {
        $this->department = \App\Models\Departamento::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $companies = \App\Models\Empresa::with(['direccion.municipio', 'rubros'])
            ->where('estado', 'ACTIVO')
            ->whereHas('direccion.municipio.provincia.departamento', function ($q) {
                $q->where('departamentos.id', $this->department->id);
            })
            ->when($this->selectedMunicipio, function ($q) {
                $q->whereHas('direccion.municipio', function ($q2) {
                    $q2->where('municipios.id', $this->selectedMunicipio);
                });
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('razon_social', 'like', '%' . $this->search . '%')
                        ->orWhere('nit', 'like', '%' . $this->search . '%')
                        ->orWhereHas('categorias', function ($q2) {
                            $q2->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('rubros', function ($q3) {
                            $q3->where('descripcion', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->paginate(36);

        $categories = \App\Models\Categoria::where('estado', 1)
            ->whereHas('empresas', function ($q) {
                $q->where('estado', 'ACTIVO')
                    ->whereHas('direccion.municipio.provincia.departamento', function ($q2) {
                        $q2->where('departamentos.id', $this->department->id);
                    });
            })
            ->orderBy('nombre')
            ->whereNotNull('slug')
            ->get();

        $municipios = \App\Models\Municipio::whereHas('provincia.departamento', function ($q) {
            $q->where('id', $this->department->id);
        })->orderBy('nombre_publico')->get();

        return view('livewire.show-department-companies', [
            'companies' => $companies,
            'categories' => $categories,
            'municipios' => $municipios
        ])->layout('layouts.site', [
                    'title' => 'Empresas en el Departamento de ' . ($this->department->nombre_publico ?? $this->department->nombre_interno)
                ]);
    }
}
