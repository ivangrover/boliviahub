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

        $empresasCountPerDept = \Illuminate\Support\Facades\Cache::remember('empresas_count_per_dept', 3600, function () {
            return \Illuminate\Support\Facades\DB::table('empresas')
                ->join('direcciones', 'empresas.direccion_id', '=', 'direcciones.id')
                ->join('municipios', 'direcciones.municipio_id', '=', 'municipios.id')
                ->join('provincias', 'municipios.provincia_id', '=', 'provincias.id')
                ->where('empresas.estado', 'ACTIVO')
                ->groupBy('provincias.departamento_id')
                ->select('provincias.departamento_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->pluck('total', 'departamento_id')
                ->toArray();
        });

        return view('livewire.show-departments', [
            'departamentos' => \App\Models\Departamento::orderBy('nombre_publico')->get(),
            'companies' => $companies,
            'empresasCountPerDept' => $empresasCountPerDept
        ])->layout('layouts.site', [
                    'title' => 'Empresas en Bolivia'
                ]);
    }
}
