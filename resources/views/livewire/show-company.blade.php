<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Breadcrumb / Back Navigation -->
        <nav class="flex mb-8 items-center text-sm text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Inicio</a>
            <svg class="h-5 w-5 mx-2 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd" />
            </svg>
            @if($company->direccion && $company->direccion->municipio && $company->direccion->municipio->provincia && $company->direccion->municipio->provincia->departamento)
                <a href="{{ route('department.show', $company->direccion->municipio->provincia->departamento->slug) }}"
                    class="hover:text-indigo-600 transition-colors">
                    {{ $company->direccion->municipio->provincia->departamento->nombre_publico ?? $company->direccion->municipio->provincia->departamento->nombre_interno }}
                </a>
                <svg class="h-5 w-5 mx-2 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <a href="{{ route('municipio.show', ['departamento_slug' => $company->direccion->municipio->provincia->departamento->slug, 'slug' => $company->direccion->municipio->slug]) }}"
                    class="hover:text-indigo-600 transition-colors">
                    {{ $company->direccion->municipio->nombre_publico ?? $company->direccion->municipio->nombre_interno }}
                </a>
                <svg class="h-5 w-5 mx-2 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            @endif
            <span
                class="text-slate-900 font-medium truncate max-w-xs">{{ Str::limit($company->razon_social, 30) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content: Company Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-8">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl mb-2">
                                    {{ $company->razon_social }}
                                </h1>
                                <p class="text-lg text-indigo-600 font-medium">
                                    {{ $company->tipo_unidad_economica }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location Info -->
                            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                                <h3
                                    class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ubicación
                                </h3>
                                @if($company->direccion)
                                    <div class="space-y-4">
                                        @if($company->direccion->municipio->provincia)
                                            <div>
                                                <span
                                                    class="block text-xs font-medium text-slate-500 uppercase">Provincia</span>
                                                {{ $company->direccion->municipio->provincia->nombre_publico ?? $company->direccion->municipio->provincia->nombre_interno }}
                                            </div>
                                        @endif

                                        @if($company->direccion->municipio)
                                            <div>
                                                <span
                                                    class="block text-xs font-medium text-slate-500 uppercase">Municipio</span>
                                                <a href="{{ route('municipio.show', ['departamento_slug' => $company->direccion->municipio->provincia->departamento->slug, 'slug' => $company->direccion->municipio->slug]) }}"
                                                    class="text-base text-indigo-600 hover:text-indigo-700 hover:underline font-medium">
                                                    {{ $company->direccion->municipio->nombre_interno }}
                                                </a>
                                            </div>
                                        @endif
                                        <div>
                                            <span
                                                class="block text-xs font-medium text-slate-500 uppercase">Dirección</span>
                                            <p class="text-base text-slate-700 mt-0.5">
                                                {{ $company->direccion->nombre_via ?? '' }}
                                                {{ $company->direccion->numero_domicilio ? 'Nro. ' . $company->direccion->numero_domicilio : '' }}
                                                @if($company->direccion->edificio || $company->direccion->piso)
                                                    <br>
                                                    <span class="text-slate-500 text-sm">
                                                        {{ $company->direccion->edificio ? 'Ed. ' . $company->direccion->edificio : '' }}
                                                        {{ $company->direccion->piso ? 'Piso ' . $company->direccion->piso : '' }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs font-medium text-slate-500 uppercase">Geolocalización</span>
                                            <p class="text-base text-slate-700 mt-0.5">
                                                @if($company->direccion->latitud && $company->direccion->longitud)
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $company->direccion->latitud }},{{ $company->direccion->longitud }}"
                                                        target="_blank"
                                                        class="text-indigo-600 hover:text-indigo-700 hover:underline flex items-center mb-1">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        Ver en Google Maps
                                                    </a>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 italic">No hay información de dirección disponible.</p>
                                @endif
                            </div>

                            <!-- Additional Info -->
                            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                                <h3
                                    class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Información Legal
                                </h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Número de
                                            identificación tributaria (NIT)</dt>
                                        <dd class="text-base font-mono font-medium text-slate-900 mt-0.5">
                                            {{ $company->nit ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Matrícula de Comercio
                                        </dt>
                                        <dd class="text-base font-mono text-slate-900 mt-0.5">
                                            {{ $company->matricula ?? 'N/A' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Contacts Section -->
                        @if($company->contactos->isNotEmpty())
                            <div class="mt-8 border-t border-slate-100 pt-8">
                                <h3 class="text-lg font-bold text-slate-900 mb-6">Información de Contacto</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($company->contactos as $contacto)
                                        @if ($contacto->tipo_contacto == 'TELEFONO')
                                            <div
                                                class="bg-white border border-slate-200 rounded-xl p-5 hover:border-indigo-300 transition-colors">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="font-bold text-slate-900">{{ $contacto->tipo_contacto }}</p>
                                                    <!-- Icon placeholder based on type could go here -->
                                                </div>

                                                @if($contacto->detalles->isNotEmpty())
                                                    <div class="space-y-2">
                                                        @foreach($contacto->detalles as $detalle)
                                                            <div class="flex items-center text-sm group">
                                                                <span
                                                                    class="text-slate-700 font-medium select-all">{{ $detalle->valor }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Categories & Rubros -->
                        <div class="mt-8 border-t border-slate-100 pt-8">
                            @if($company->rubros->isNotEmpty())
                                <div class="mb-8">
                                    <h3
                                        class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Actividades / Rubros
                                    </h3>
                                    @foreach($company->rubros as $rubro)
                                        <div class="p-4 rounded-lg text-sm bg-slate-100 text-slate-700 border border-slate-200">
                                            {!! $rubro->descripcion_publico ?? $rubro->descripcion !!}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($company->categorias->isNotEmpty())
                                <div>
                                    <h3
                                        class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Categorías
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($company->categorias as $categoria)
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors cursor-default border border-indigo-100">
                                                {{ $categoria->nombre }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sidebar: Related Companies -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Empresas Relacionadas
                    </h2>

                    @if($relatedCompanies->count() > 0)
                        <div class="space-y-4">
                            @foreach($relatedCompanies as $related)
                                <a href="{{ route('company.show', $related->slug) }}"
                                    class="block group p-4 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all duration-200">
                                    <h4
                                        class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                                        {{ $related->razon_social }}
                                    </h4>
                                    <div class="mt-2 flex items-center text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $related->direccion->municipio->nombre_interno ?? 'Ubicación no disponible' }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-slate-500">No se encontraron empresas relacionadas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>