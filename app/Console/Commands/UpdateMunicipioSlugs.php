<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

use App\Models\Municipio;

class UpdateMunicipioSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-municipio-slugs {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando slugs de municipios...');
        $this->newLine();

        Municipio::with('provincia')->chunk(100, function ($municipios) {
            foreach ($municipios as $municipio) {

                $oldSlug =  Str::slug($municipio->provincia->nombre_interno . '-' . $municipio->nombre_interno);

                $newSlug = Str::slug(
                    $municipio->nombre_interno . '-' .
                    substr(md5($municipio->provincia->nombre_interno), 0, 3)
                );

                // Si no cambia, no hacemos nada
                if ($oldSlug === $newSlug) {
                    return;
                }

                $this->line(
                    "Redirect 301 /municipio/{$oldSlug} /empresas/{$municipio->provincia->departamento->slug}/{$newSlug}"
                );

                // Si no es dry-run, guardamos
                if (!$this->option('dry-run')) {
                    $municipio->slug = $newSlug;
                    $municipio->save();
                }
            }
        });

        $this->newLine();
        $this->info('Proceso finalizado.');
    }
}
