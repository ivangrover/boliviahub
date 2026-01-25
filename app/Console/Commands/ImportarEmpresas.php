<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Str;
use App\Models\{Empresa, Direccion, Municipio, Provincia, Departamento, Rubro, Contacto, DescripcionContacto};

class ImportarEmpresas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:importar-empresas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa empresas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = parse_ini_file(base_path('config.ini'));

        $filtro = $config['filtro'];
        $tipoFiltro = $config['tipoFiltro'];
        $limite = $config['limite'];
        $pagina = (int) $config['pagina'];

        $baseUrl = "https://servicios.seprec.gob.bo/api";
        $buscarUrl = "$baseUrl/empresas/buscarEmpresas?filtro=$filtro&tipoFiltro=$tipoFiltro&limite=$limite&pagina=$pagina";

        $response = file_get_contents($buscarUrl);
        $data = json_decode($response, true);

        if (!$data['finalizado'] || !isset($data['datos']['filas'])) {
            $this->error("Error al obtener la lista de empresas.");
            return 1;
        }

        foreach ($data['datos']['filas'] as $empresa) {
            $empresaId = $empresa['id'];
            $establecimientoId = $empresa['idEstablecimiento'];

            if ($empresa['codEstadoActualizacion']['nombre'] === 'MATRICULA NO RENOVADA') {
                if ($e = Empresa::find($empresaId)) {
                    $e->update([
                        'estado_actualizacion' => $empresa['codEstadoActualizacion']['nombre'],
                    ]);
                }
                continue;
            }

            $detalleUrl = "$baseUrl/empresas/informacionBasicaEmpresa/$empresaId/establecimiento/$establecimientoId";
            $detalleResp = file_get_contents($detalleUrl);
            $detalle = json_decode($detalleResp, true);

            if (!$detalle['finalizado']) {
                $this->error("Error al obtener el detalle de la empresa $empresaId");
                continue;
            }

            $d = $detalle['datos'];

            //dump($d);

            // Departamento
            if (!empty($d['direccion']['codDepartamento'])) {
                $dep = Departamento::updateOrCreate(
                    ['id' => $d['direccion']['codDepartamento']['id']],
                    [
                        'nombre_interno' => $d['direccion']['codDepartamento']['nombre'],
                        'slug' => Str::slug($d['direccion']['codDepartamento']['nombre'])
                    ]
                );
            }

            // Provincia
            if (!empty($d['direccion']['codProvincia'])) {
                if ($d['direccion']['codProvincia']['nombre'] == 'CERCADO (COCHABAMBA)') {
                    $d['direccion']['codProvincia']['nombre'] = 'CERCADO';
                }

                $prov = Provincia::updateOrCreate(
                    ['id' => $d['direccion']['codProvincia']['id']],
                    [
                        'nombre_interno' => $d['direccion']['codProvincia']['nombre'],
                        'slug' => Str::slug($d['direccion']['codDepartamento']['nombre'] . '-' . $d['direccion']['codProvincia']['nombre']),
                        'departamento_id' => $dep->id
                    ]
                );
            }

            // Municipio
            if (!empty($d['direccion']['codMunicipio'])) {
                $mun = Municipio::updateOrCreate(
                    ['id' => $d['direccion']['codMunicipio']['id']],
                    [
                        'nombre_interno' => $d['direccion']['codMunicipio']['nombre'],
                        'nombre_externo' => Str::title(
                            mb_strtolower($d['direccion']['codMunicipio']['nombre'], 'UTF-8')
                        ),
                        'slug' => Str::slug($d['direccion']['codProvincia']['nombre'] . '-' . $d['direccion']['codMunicipio']['nombre']),
                        'provincia_id' => $prov->id
                    ]
                );
            }

            if (isset($mun)) {
                // Dirección
                $dir = Direccion::updateOrCreate(
                    ['id' => $d['direccion']['id']],
                    [
                        'nombre_via' => $d['direccion']['nombreVia'],
                        'numero_domicilio' => $d['direccion']['numeroDomicilio'],
                        'edificio' => $d['direccion']['edificio'],
                        'piso' => $d['direccion']['piso'],
                        'latitud' => $d['direccion']['latitud'],
                        'longitud' => $d['direccion']['longitud'],
                        'municipio_id' => $mun->id
                    ]
                );

                // Empresa
                $emp = Empresa::updateOrCreate(
                    ['id' => $d['id']],
                    [
                        'nit' => $d['nit'],
                        'matricula' => $d['matricula'],
                        'matricula_anterior' => $d['matriculaAnterior'],
                        'razon_social' => trim($d['razonSocial']),
                        'estado' => $d['estado'],
                        'estado_actualizacion' => $d['codEstadoActualizacion']['nombre'],
                        'tipo_unidad_economica' => $d['codTipoUnidadEconomica']['nombre'],
                        'establecimiento_id' => $establecimientoId,
                        'mes_cierre_gestion' => $d['mesCierreGestion'],
                        'ultima_actualizacion' => $d['ultimoAnioActualizacion'],
                        'slug' => Str::slug($d['razonSocial'] . '-' . Str::random(3)),
                        'direccion_id' => $dir->id
                    ]
                );

                // Rubros
                $emp->rubros()->delete();
                foreach ($d['objetos_sociales'] as $obj) {
                    $emp->rubros()->create(['descripcion' => $obj['objetoSocial']]);
                }

                // Contactos
                $emp->contactos()->delete();
                foreach ($d['contactos'] as $c) {
                    $contacto = $emp->contactos()->create([
                        'id' => $c['id'],
                        'tipo_contacto' => $c['tipoContacto'],
                        'estado' => $c['estado'],
                        'persona' => $c['persona'] ?? null
                    ]);

                    foreach ($c['descripcion'] as $desc) {
                        $valor = $desc['numero'] ?? ($desc['correo'] ?? null);
                        if ($valor) {
                            $contacto->detalles()->create([
                                'tipo' => $desc['tipo'],
                                'valor' => $valor
                            ]);
                        }
                    }
                }
                $this->info("Empresa importada: {$emp->razon_social}");
            }
            sleep(10);
        }

        $config['pagina'] += 1;
        $this->updateIniFile(base_path('config.ini'), $config);

        $this->info("[" . now()->format('Y-m-d H:i:s') . "] Proceso finalizado.");
        return 0;
    }

    private function updateIniFile($path, $params)
    {
        $content = "";
        foreach ($params as $key => $value) {
            $content .= "$key = $value\n";
        }
        file_put_contents($path, $content);
    }
}
