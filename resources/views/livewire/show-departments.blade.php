<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-slate-900 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-slate-900/60 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl text-center mb-6">
                Empresas <span class="text-indigo-400">en Bolivia</span>
            </h1>
            <p class="mt-4 max-w-2xl text-xl text-slate-300 mx-auto text-center mb-10">
                Directorio de Negocios de Bolivia. Encuentra proveedores y socios estratégicos en un solo lugar.
            </p>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Text Search -->
                        <div class="md:col-span-6 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-indigo-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="block w-full pl-10 bg-white/90 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 text-slate-900 rounded-lg placeholder-slate-500"
                                placeholder="Buscar empresa">
                        </div>

                        <!-- Department Select -->
                        <div class="md:col-span-3">
                            <select wire:model.live="selectedDepartment"
                                class="block w-full pl-3 pr-10 py-2 text-base bg-white/90 border-transparent focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg text-slate-700">
                                <option value="">Departamento</option>
                                @foreach($departamentos as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nombre_publico ?? $dept->nombre_interno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Municipality Select -->
                        <div class="md:col-span-3">
                            <select wire:model.live="selectedMunicipio"
                                class="block w-full pl-3 pr-10 py-2 text-base bg-white/90 border-transparent focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg disabled:bg-white/50 disabled:text-slate-400 text-slate-700"
                                @if(empty($selectedDepartment)) disabled @endif>
                                <option value="">Municipio</option>
                                @if(!empty($selectedDepartment))
                                    @foreach($municipios as $muni)
                                        <option value="{{ $muni->id }}">{{ $muni->nombre_publico ?? $muni->nombre_interno }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if(!empty($search) || !empty($selectedDepartment))
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Resultados de búsqueda</h2>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">
                    {{ count($companies) }} empresas encontradas
                </span>
            </div>

            @if(count($companies) > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
                    @foreach($companies as $company)
                        <div
                            class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 overflow-hidden flex flex-col h-full group">
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <a href="{{ route('company.show', $company->slug) }}" class="block">
                                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-tight mb-2"
                                            title="{{ $company->razon_social }}">
                                            {{ $company->razon_social }}
                                        </h3>
                                    </a>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ $company->tipo_unidad_economica }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4 border-t border-slate-100/50">
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                    <div class="bg-slate-50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">No se encontraron resultados</h3>
                    <p class="mt-1 text-slate-500">Intenta ajustar tus filtros o búsqueda.</p>
                </div>
            @endif
        @endif

        @if(empty($search) && empty($selectedDepartment))
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Explora Empresas por Departamento</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($departamentos as $dept)
                    <a href="{{ route('department.show', $dept->slug) }}"
                        class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-200 overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-24 h-24 text-indigo-600 transform rotate-12" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z" />
                            </svg>
                        </div>
                        <div class="p-8 relative z-10">
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">
                                {{ $dept->nombre_publico ?? $dept->nombre_interno }}
                            </h3>
                            <div
                                class="flex items-center text-sm text-slate-500 group-hover:text-indigo-500 transition-colors font-medium">
                                <span>Empresas en el departamento de {{ $dept->nombre_publico ?? $dept->nombre_interno }}</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>