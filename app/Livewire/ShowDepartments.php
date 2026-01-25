<?php

namespace App\Livewire;

use Livewire\Component;

class ShowDepartments extends Component
{
    public $search = '';
    public $selectedDepartment = '';
    public $selectedMunicipio = '';
    public $municipios = [];

    public function updatedSelectedDepartment($value)
    {
        $this->municipios = \App\Models\Municipio::whereHas('provincia.departamento', function ($q) use ($value) {
            $q->where('id', $value);
        })->orderBy('nombre_publico')->get();

        $this->selectedMunicipio = '';
    }

    public function render()
    {
        $companies = [];

        if (!empty($this->search) || !empty($this->selectedDepartment)) {
            $companies = \App\Models\Empresa::with(['direccion.municipio'])
                ->where('estado', 'ACTIVO')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('razon_social', 'like', '%' . $this->search . '%')
                            ->orWhere('nit', 'like', '%' . $this->search . '%')
                            ->orWhereHas('categorias', function ($q) {
                                $q->where('nombre', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('rubros', function ($q) {
                                $q->where('descripcion', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->selectedDepartment, function ($query) {
                    $query->whereHas('direccion.municipio.provincia.departamento', function ($q) {
                        $q->where('id', $this->selectedDepartment);
                    });
                })
                ->when($this->selectedMunicipio, function ($query) {
                    $query->whereHas('direccion.municipio', function ($q) {
                        $q->where('id', $this->selectedMunicipio);
                    });
                })
                ->limit(20)
                ->get();
        }

        return view('livewire.show-departments', [
            'departamentos' => \App\Models\Departamento::orderBy('nombre_publico')->get(),
            'companies' => $companies
        ])->layout('layouts.site', [
                    'title' => 'Empresas en Bolivia'
                ]);
    }
}
