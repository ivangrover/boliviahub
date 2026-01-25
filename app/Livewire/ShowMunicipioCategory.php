<?php

namespace App\Livewire;

use Livewire\Component;

class ShowMunicipioCategory extends Component
{
    public $municipio;
    public $category;
    public $search = '';

    public function mount($muni_slug, $cat_slug)
    {
        $this->municipio = \App\Models\Municipio::where('slug', $muni_slug)->firstOrFail();
        $this->category = \App\Models\Categoria::where('slug', $cat_slug)->where('estado', 1)->firstOrFail();
    }

    public function render()
    {
        $companies = \App\Models\Empresa::with(['direccion.municipio', 'rubros'])
            ->where('estado', 'ACTIVO')
            ->whereHas('direccion.municipio', function ($q) {
                $q->where('municipios.id', $this->municipio->id);
            })
            ->whereHas('categorias', function ($q) {
                $q->where('categorias.id', $this->category->id);
            })
            ->when($this->search, function ($q) {
                $q->where('razon_social', 'like', '%' . $this->search . '%')
                    ->orWhere('nit', 'like', '%' . $this->search . '%');
            })
            ->paginate(36);

        return view('livewire.show-municipio-category', [
            'companies' => $companies
        ])->layout('layouts.site', [
                    'title' => $this->category->nombre . ' en ' . $this->municipio->nombre_publico ?? $this->municipio->nombre_interno
                ]);
    }
}
