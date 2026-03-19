<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('==================================');
        $this->command->info('Creando catálogos...');
        $this->command->info('==================================');
        $this->command->info('');

        // 1. Crear Terminados
        $this->crearTerminados();
        
        // 2. Crear Materiales
        $this->crearMateriales();
        
        // 3. Crear Departamentos
        $this->crearDepartamentos();
        
        // 4. Crear Bancos
        $this->crearBancos();

        $this->command->info('');
        $this->command->info('==================================');
        $this->command->info('¡Catálogos creados exitosamente!');
        $this->command->info('==================================');
        $this->command->info('');
    }

    /**
     * Crear terminados
     */
    private function crearTerminados(): void
    {
        $terminados = [
            [
                'terminado' => 'Barnizado',
                'descripcion' => 'Acabado barnizado brillante',
            ],
            [
                'terminado' => 'Natural',
                'descripcion' => 'Sin acabado, tratamiento básico',
            ],
            [
                'terminado' => 'Lacado',
                'descripcion' => 'Acabado lacado mate',
            ],
            [
                'terminado' => 'Teñido',
                'descripcion' => 'Madera teñida',
            ],
            [
                'terminado' => 'Encerado',
                'descripcion' => 'Acabado encerado tradicional',
            ],
            [
                'terminado' => 'Patinado',
                'descripcion' => 'Acabado envejecido rústico',
            ],
            [
                'terminado' => 'Satinado',
                'descripcion' => 'Acabado satinado semi-mate',
            ],
        ];

        foreach ($terminados as $terminado) {
            DB::table('terminado')->insert($terminado);
        }

        $this->command->info('✓ ' . count($terminados) . ' Terminados creados');
    }

    /**
     * Crear materiales
     */
    private function crearMateriales(): void
    {
        $materiales = [
            [
                'material' => 'Roble',
                'descripcion' => 'Madera de roble de alta calidad',
            ],
            [
                'material' => 'Cedro',
                'descripcion' => 'Madera de cedro aromática',
            ],
            [
                'material' => 'Pino',
                'descripcion' => 'Madera de pino económica',
            ],
            [
                'material' => 'Caoba',
                'descripcion' => 'Madera de caoba premium',
            ],
            [
                'material' => 'Nogal',
                'descripcion' => 'Madera de nogal oscura',
            ],
            [
                'material' => 'Encino',
                'descripcion' => 'Madera de encino fuerte',
            ],
            [
                'material' => 'Maple',
                'descripcion' => 'Madera de maple clara',
            ],
            [
                'material' => 'Fresno',
                'descripcion' => 'Madera de fresno flexible',
            ],
            [
                'material' => 'Mezquite',
                'descripcion' => 'Madera de mezquite mexicana',
            ],
            [
                'material' => 'MDF',
                'descripcion' => 'Tablero de fibra media',
            ],
        ];

        foreach ($materiales as $material) {
            DB::table('materiales')->insert($material);
        }

        $this->command->info('✓ ' . count($materiales) . ' Materiales creados');
    }

    /**
     * Crear departamentos
     */
    private function crearDepartamentos(): void
    {
        $departamentos = [
            [
                'name' => 'Sala',
                'descripcion' => 'Muebles para sala de estar',
            ],
            [
                'name' => 'Comedor',
                'descripcion' => 'Muebles para comedor',
            ],
            [
                'name' => 'Recámara',
                'descripcion' => 'Muebles para dormitorio',
            ],
            [
                'name' => 'Oficina',
                'descripcion' => 'Muebles para oficina',
            ],
            [
                'name' => 'Cocina',
                'descripcion' => 'Muebles para cocina',
            ],
            [
                'name' => 'Baño',
                'descripcion' => 'Muebles para baño',
            ],
            [
                'name' => 'Auxiliares',
                'descripcion' => 'Muebles auxiliares y decorativos',
            ],
            [
                'name' => 'Infantil',
                'descripcion' => 'Muebles para niños',
            ],
            [
                'name' => 'Exterior',
                'descripcion' => 'Muebles para exteriores',
            ],
            [
                'name' => 'Comercial',
                'descripcion' => 'Muebles para comercios',
            ],
        ];

        foreach ($departamentos as $departamento) {
            DB::table('departamentos')->insert($departamento);
        }

        $this->command->info('✓ ' . count($departamentos) . ' Departamentos creados');
    }

    /**
     * Crear bancos
     */
    private function crearBancos(): void
    {
        $bancos = [
            [
                'banco' => 'BBVA',
                'descripcion' => 'Banco Bilbao Vizcaya Argentaria',
            ],
            [
                'banco' => 'Santander',
                'descripcion' => 'Banco Santander México',
            ],
            [
                'banco' => 'Banamex',
                'descripcion' => 'Banco Nacional de México',
            ],
            [
                'banco' => 'Banorte',
                'descripcion' => 'Banco del Norte',
            ],
            [
                'banco' => 'HSBC',
                'descripcion' => 'HSBC México',
            ],
            [
                'banco' => 'Scotiabank',
                'descripcion' => 'Scotiabank Inverlat',
            ],
            [
                'banco' => 'Inbursa',
                'descripcion' => 'Banco Inbursa',
            ],
            [
                'banco' => 'Azteca',
                'descripcion' => 'Banco Azteca',
            ],
        ];

        foreach ($bancos as $banco) {
            DB::table('bancos')->insert($banco);
        }

        $this->command->info('✓ ' . count($bancos) . ' Bancos creados');
    }
}