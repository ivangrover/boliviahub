<?php

namespace App\Livewire;

use Livewire\Component;

class ShowCompany extends Component
{
    public $company;
    public $relatedCompanies;

    public function mount($slug)
    {
        $this->company = \App\Models\Empresa::with(['direccion.municipio.provincia.departamento', 'rubros', 'categorias', 'contactos.detalles'])
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = $this->company->categorias->pluck('id')->toArray();

        $this->relatedCompanies = \App\Models\Empresa::with(['direccion.municipio'])
            ->where('estado', 'ACTIVO')
            ->where('empresas.id', '!=', $this->company->id)
            ->whereHas('categorias', function ($q) use ($categoryIds) {
                $q->whereIn('categorias.id', $categoryIds);
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.show-company')
            ->layout('layouts.site', [
                'title' => $this->company->razon_social
            ]);
    }
}
