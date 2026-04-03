<?php

namespace App\Livewire;

use Livewire\Component;

class ShowMunicipioCompanies extends Component
{
    public $municipio;
    public $search = '';

    public function mount($departamento_slug, $slug)
    {
        $this->municipio = \App\Models\Municipio::where('slug', $slug)
            ->whereHas('provincia.departamento', function ($q) use ($departamento_slug) {
                $q->where('slug', $departamento_slug);
            })
            ->firstOrFail();
    }

    public function render()
    {
        $companies = \App\Models\Empresa::with(['direccion.municipio', 'rubros'])
            ->where('estado', 'ACTIVO')
            ->whereHas('direccion.municipio', function ($q) {
                $q->where('municipios.id', $this->municipio->id);
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
                    ->whereHas('direccion.municipio', function ($q2) {
                        $q2->where('municipios.id', $this->municipio->id);
                    });
            })
            ->orderBy('nombre')
            ->whereNotNull('slug')
            ->get();

        return view('livewire.show-municipio-companies', [
            'companies' => $companies,
            'categories' => $categories
        ])->layout('layouts.site', [
                    'title' => 'Empresas en el municipio de ' . ($this->municipio->nombre_publico ?? $this->municipio->nombre_interno)
                ]);
    }
}
