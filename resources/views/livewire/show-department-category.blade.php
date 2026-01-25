<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-center">
            <div>
                <nav class="flex mb-2 text-sm text-slate-500">
                    <a href="{{ route('department.show', $department->slug) }}"
                        class="hover:text-indigo-600 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al departamento de {{ $department->nombre_publico ?? $department->nombre_interno }}
                    </a>
                </nav>
                <h1 class="text-3xl font-bold text-slate-900">
                    {{ $category->nombre }} en {{ $department->nombre_publico ?? $department->nombre_interno }}
                </h1>
            </div>
            <div class="mt-4 sm:mt-0 w-full sm:w-auto relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar empresa..."
                    class="block w-full sm:w-64 pl-10 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        @if($companies->count() > 0)
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
                                        <a href="{{ route('municipio.show', $company->direccion->municipio->slug) }}"
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
                <h3 class="text-lg font-medium text-slate-900">No hay empresas</h3>
                <p class="mt-1 text-slate-500">No se encontraron empresas de esta categoría en este departamento.</p>
            </div>
        @endif
    </div>
</div>