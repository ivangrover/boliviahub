<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-500">
                            <svg class="flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-slate-300" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-slate-500">Departamento de
                                {{ $department->nombre_publico ?? $department->nombre_interno }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                        Empresas en el Departamento de <span
                            class="text-indigo-600">{{ $department->nombre_publico ?? $department->nombre_interno }}</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar empresa"
                        class="block w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="sm:w-64">
                    <select wire:model.live="selectedMunicipio"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos los Municipios</option>
                        @foreach($municipios as $muni)
                            <option value="{{ $muni->id }}">{{ $muni->nombre_publico ?? $muni->nombre_interno }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Empresas por Categoría</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('department.category.show', ['dept_slug' => $department->slug, 'cat_slug' => $category->slug]) }}"
                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-white border border-slate-200 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-colors duration-200 shadow-sm">
                            {{ $category->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($companies->count() > 0)
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Negocios en el Departamento de
                {{ $department->nombre_publico ?? $department->nombre_interno }}
            </h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($companies as $company)
                    <div
                        class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col h-full group hover:-translate-y-1">
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <a href="{{ route('company.show', $company->slug) }}" class="block">
                                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-tight mb-2"
                                        title="{{ $company->razon_social }}">
                                        {{ $company->razon_social }}
                                    </h3>
                                </a>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $company->tipo_unidad_economica }}
                                </span>
                            </div>

                            <div class="mt-auto space-y-4">
                                <div class="flex items-center text-sm text-slate-500">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    @if($company->direccion && $company->direccion->municipio)
                                        <a href="{{ route('municipio.show', ['departamento_slug' => $department->slug, 'slug' => $company->direccion->municipio->slug]) }}"
                                            class="hover:text-indigo-600 hover:underline transition-colors block truncate">
                                            {{ $company->direccion->municipio->nombre_interno }}
                                        </a>
                                    @else
                                        <span class="italic text-slate-400">Sin ubicación</span>
                                    @endif
                                </div>

                                @if($company->rubros->isNotEmpty())
                                    <div class="pt-3 border-t border-slate-100 flex flex-wrap gap-2">
                                        @foreach($company->rubros->take(2) as $rubro)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                                {{ Str::limit($rubro->descripcion, 50) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $companies->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="bg-slate-50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900">No se encontraron empresas</h3>
                <p class="mt-1 text-slate-500">Intenta ajustar tus filtros de búsqueda.</p>
            </div>
        @endif
    </div>
</div>