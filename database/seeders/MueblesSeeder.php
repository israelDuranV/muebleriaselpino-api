<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MueblesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que existan los catálogos necesarios
        $this->verificarCatalogos();

        $now = Carbon::now();

        $muebles = [
            // ========== MUEBLES DE SALA ==========
            [
                'nombre' => 'Sofá Ejecutivo 3 Plazas',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 1, // Sala
                'sincera' => 120,
                'encerado' => 60,
                'costo' => 8500,
                'barniz' => 40,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Mesa de Centro Moderna',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 1, // Sala
                'sincera' => 80,
                'encerado' => 40,
                'costo' => 3500,
                'barniz' => 30,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Sillón Individual Reclinable',
                'materiales_id' => 1, // Roble
                'terminado_id' => 2, // Natural
                'departamento_id' => 1, // Sala
                'sincera' => 90,
                'encerado' => 50,
                'costo' => 4200,
                'barniz' => 25,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Mueble para TV Modular',
                'materiales_id' => 3, // Pino
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 1, // Sala
                'sincera' => 100,
                'encerado' => 45,
                'costo' => 5800,
                'barniz' => 35,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES DE COMEDOR ==========
            [
                'nombre' => 'Mesa de Comedor 6 Personas',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 2, // Comedor
                'sincera' => 150,
                'encerado' => 70,
                'costo' => 9800,
                'barniz' => 50,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Sillas de Comedor (Set de 6)',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 2, // Comedor
                'sincera' => 60,
                'encerado' => 30,
                'costo' => 2400,
                'barniz' => 20,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Vitrina para Cristalería',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 2, // Comedor
                'sincera' => 110,
                'encerado' => 55,
                'costo' => 7500,
                'barniz' => 40,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Buffet con Cajones',
                'materiales_id' => 3, // Pino
                'terminado_id' => 2, // Natural
                'departamento_id' => 2, // Comedor
                'sincera' => 95,
                'encerado' => 48,
                'costo' => 5200,
                'barniz' => 30,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES DE RECÁMARA ==========
            [
                'nombre' => 'Cama Matrimonial King Size',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 3, // Recámara
                'sincera' => 180,
                'encerado' => 80,
                'costo' => 12500,
                'barniz' => 60,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Buró con 3 Cajones',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 3, // Recámara
                'sincera' => 50,
                'encerado' => 25,
                'costo' => 2800,
                'barniz' => 18,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Ropero 4 Puertas',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 3, // Recámara
                'sincera' => 200,
                'encerado' => 90,
                'costo' => 15800,
                'barniz' => 70,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Tocador con Espejo',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 2, // Natural
                'departamento_id' => 3, // Recámara
                'sincera' => 85,
                'encerado' => 42,
                'costo' => 6800,
                'barniz' => 35,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES DE OFICINA ==========
            [
                'nombre' => 'Escritorio Ejecutivo L',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 4, // Oficina
                'sincera' => 140,
                'encerado' => 65,
                'costo' => 9200,
                'barniz' => 45,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Silla Ejecutiva Ergonómica',
                'materiales_id' => 1, // Roble
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 4, // Oficina
                'sincera' => 70,
                'encerado' => 35,
                'costo' => 3500,
                'barniz' => 22,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Librero 5 Niveles',
                'materiales_id' => 3, // Pino
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 4, // Oficina
                'sincera' => 95,
                'encerado' => 48,
                'costo' => 4800,
                'barniz' => 28,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Archivero Vertical 4 Gavetas',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 2, // Natural
                'departamento_id' => 4, // Oficina
                'sincera' => 80,
                'encerado' => 40,
                'costo' => 5500,
                'barniz' => 30,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES DE COCINA ==========
            [
                'nombre' => 'Alacena Modular 3 Puertas',
                'materiales_id' => 3, // Pino
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 5, // Cocina
                'sincera' => 100,
                'encerado' => 50,
                'costo' => 6200,
                'barniz' => 35,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Mesa de Cocina Plegable',
                'materiales_id' => 3, // Pino
                'terminado_id' => 2, // Natural
                'departamento_id' => 5, // Cocina
                'sincera' => 60,
                'encerado' => 30,
                'costo' => 2800,
                'barniz' => 18,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Carrito Auxiliar con Ruedas',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 2, // Natural
                'departamento_id' => 5, // Cocina
                'sincera' => 45,
                'encerado' => 22,
                'costo' => 1800,
                'barniz' => 12,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES DE BAÑO ==========
            [
                'nombre' => 'Gabinete de Baño con Espejo',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 6, // Baño
                'sincera' => 75,
                'encerado' => 38,
                'costo' => 4200,
                'barniz' => 25,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Mueble bajo Lavabo',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 6, // Baño
                'sincera' => 65,
                'encerado' => 32,
                'costo' => 3800,
                'barniz' => 22,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES AUXILIARES ==========
            [
                'nombre' => 'Banco de Madera Rústico',
                'materiales_id' => 1, // Roble
                'terminado_id' => 2, // Natural
                'departamento_id' => 7, // Auxiliares
                'sincera' => 40,
                'encerado' => 20,
                'costo' => 1200,
                'barniz' => 10,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Perchero de Pie',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 7, // Auxiliares
                'sincera' => 35,
                'encerado' => 18,
                'costo' => 980,
                'barniz' => 8,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Escalera Decorativa',
                'materiales_id' => 3, // Pino
                'terminado_id' => 2, // Natural
                'departamento_id' => 7, // Auxiliares
                'sincera' => 30,
                'encerado' => 15,
                'costo' => 750,
                'barniz' => 6,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Revistero de Pared',
                'materiales_id' => 2, // Cedro
                'terminado_id' => 1, // Barnizado
                'departamento_id' => 7, // Auxiliares
                'sincera' => 25,
                'encerado' => 12,
                'costo' => 650,
                'barniz' => 5,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // ========== MUEBLES INFANTILES ==========
            [
                'nombre' => 'Cama Individual Infantil',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 8, // Infantil
                'sincera' => 90,
                'encerado' => 45,
                'costo' => 5800,
                'barniz' => 30,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Escritorio Infantil con Silla',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 8, // Infantil
                'sincera' => 55,
                'encerado' => 28,
                'costo' => 3200,
                'barniz' => 18,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Cómoda Infantil 4 Cajones',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 8, // Infantil
                'sincera' => 70,
                'encerado' => 35,
                'costo' => 4500,
                'barniz' => 25,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Librero Infantil con Juguetero',
                'materiales_id' => 3, // Pino
                'terminado_id' => 3, // Lacado
                'departamento_id' => 8, // Infantil
                'sincera' => 65,
                'encerado' => 32,
                'costo' => 3800,
                'barniz' => 20,
                'fotografia' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insertar muebles
        foreach ($muebles as $mueble) {
            DB::table('muebles')->insert($mueble);
        }

        $this->command->info('✓ 30 muebles creados exitosamente!');
        $this->command->info('');
        $this->mostrarResumen();
    }

    /**
     * Verificar que existan los catálogos necesarios
     */
    private function verificarCatalogos(): void
    {
        // Verificar terminados
        $terminados = DB::table('terminado')->count();
        if ($terminados === 0) {
            $this->command->warn('⚠️  La tabla "terminado" está vacía. Ejecuta primero: php artisan db:seed --class=CatalogosSeeder');
        }

        // Verificar materiales
        $materiales = DB::table('materiales')->count();
        if ($materiales === 0) {
            $this->command->warn('⚠️  La tabla "materiales" está vacía. Ejecuta primero: php artisan db:seed --class=CatalogosSeeder');
        }

        // Verificar departamentos
        $departamentos = DB::table('departamentos')->count();
        if ($departamentos === 0) {
            $this->command->warn('⚠️  La tabla "departamento" está vacía. Ejecuta primero: php artisan db:seed --class=CatalogosSeeder');
        }
    }

    /**
     * Mostrar resumen de muebles creados
     */
    private function mostrarResumen(): void
    {
        $this->command->info('=== RESUMEN DE MUEBLES CREADOS ===');
        $this->command->info('');

        $resumen = DB::table('muebles')
            ->join('departamentos', 'muebles.departamento_id', '=', 'departamentos.departamento_id')
            ->select('departamentos.name', DB::raw('COUNT(*) as total'))
            ->groupBy('departamentos.name')
            ->get();

        foreach ($resumen as $item) {
            $this->command->info("• {$item->name}: {$item->total} muebles");
        }

        $this->command->info('');
        $costoTotal = DB::table('muebles')->sum('costo');
        $this->command->info("Valor total de muebles: $" . number_format($costoTotal, 2));
        $this->command->info('');
    }
}