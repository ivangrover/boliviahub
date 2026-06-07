<?php

namespace App\Livewire;

use Livewire\Component;

class ShowDepartments extends Component
{
    public $search = '';
    public $selectedDepartment = '';
    public $selectedMunicipio = '';
    public $municipios = [];

    // Properties for category exploration
    public $selectedCategory = null;
    public $selectedDeptForCategory = '';
    public $selectedMuniForCategory = '';
    public $municipiosForCategory = [];
    public $departamentosForCategory = [];

    public function updatedSelectedDepartment($value)
    {
        $this->municipios = \App\Models\Municipio::whereHas('provincia.departamento', function ($q) use ($value) {
            $q->where('id', $value);
        })->orderBy('nombre_publico')->get();

        $this->selectedMunicipio = '';
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = \App\Models\Categoria::find($categoryId);
        $this->selectedDeptForCategory = '';
        $this->selectedMuniForCategory = '';
        $this->municipiosForCategory = [];

        if ($this->selectedCategory) {
            $this->departamentosForCategory = \App\Models\Departamento::whereHas('provincias.municipios.direcciones.empresas', function ($q) {
                $q->where('empresas.estado', 'ACTIVO')
                  ->whereHas('categorias', function ($catQuery) {
                      $catQuery->where('categorias.id', $this->selectedCategory->id);
                  });
            })->orderBy('nombre_publico')->get();
        } else {
            $this->departamentosForCategory = [];
        }
    }

    public function closeCategoryModal()
    {
        $this->selectedCategory = null;
        $this->selectedDeptForCategory = '';
        $this->selectedMuniForCategory = '';
        $this->municipiosForCategory = [];
        $this->departamentosForCategory = [];
    }

    public function updatedSelectedDeptForCategory($value)
    {
        if ($value && $this->selectedCategory) {
            $this->municipiosForCategory = \App\Models\Municipio::whereHas('provincia.departamento', function ($q) use ($value) {
                $q->where('id', $value);
            })
            ->whereHas('direcciones.empresas', function ($q) {
                $q->where('empresas.estado', 'ACTIVO')
                  ->whereHas('categorias', function ($catQuery) {
                      $catQuery->where('categorias.id', $this->selectedCategory->id);
                  });
            })
            ->orderBy('nombre_publico')->get();
        } else {
            $this->municipiosForCategory = [];
        }
        $this->selectedMuniForCategory = '';
    }

    public function exploreCategory()
    {
        if (!$this->selectedCategory || !$this->selectedDeptForCategory) {
            return;
        }

        $dept = \App\Models\Departamento::find($this->selectedDeptForCategory);

        if ($this->selectedMuniForCategory) {
            $muni = \App\Models\Municipio::find($this->selectedMuniForCategory);
            return redirect()->route('municipio.category.show', [
                'departamento_slug' => $dept->slug,
                'municipio_slug' => $muni->slug,
                'slug' => $this->selectedCategory->slug
            ]);
        }

        return redirect()->route('department.category.show', [
            'departamento_slug' => $dept->slug,
            'slug' => $this->selectedCategory->slug
        ]);
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

        $totalEmpresas = \Illuminate\Support\Facades\Cache::remember('total_empresas_count', 3600, function () {
            return \App\Models\Empresa::where('estado', 'ACTIVO')->count();
        });

        $totalMunicipios = \Illuminate\Support\Facades\Cache::remember('total_municipios_count', 3600, function () {
            return \App\Models\Municipio::count();
        });

        $categories = \Illuminate\Support\Facades\Cache::remember('all_categories', 3600, function () {
            return \App\Models\Categoria::where('estado', 1)->orderBy('nombre')->get();
        });

        return view('livewire.show-departments', [
            'departamentos' => \App\Models\Departamento::orderBy('nombre_publico')->get(),
            'companies' => $companies,
            'empresasCountPerDept' => $empresasCountPerDept,
            'totalEmpresas' => $totalEmpresas,
            'totalMunicipios' => $totalMunicipios,
            'categories' => $categories,
        ])->layout('layouts.site', [
                    'title' => 'BoliviaHub | Directorio de Empresas y Negocios en Bolivia',
                    'description' => 'Encuentra proveedores, socios estratégicos y empresas en Bolivia. Más de 67.000 negocios registrados por departamento y municipio. Busca gratis en el directorio empresarial más completo del país.'
                ]);
    }
}
