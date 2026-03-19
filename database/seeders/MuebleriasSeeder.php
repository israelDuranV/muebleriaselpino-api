<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuebleriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mueblerias = [
            [
                'nombre' => 'San Salvador',
                'tipo' => 'Mayorista',
                'direccion_id' => 1,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'San Pablo',
                'tipo' => 'Minorista',
                'direccion_id' => 2,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Milpa Alta',
                'tipo' => 'Mixto',
                'direccion_id' => 3,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Santa Cecilia',
                'tipo' => 'Mayorista',
                'direccion_id' => 4,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Xochimilco',
                'tipo' => 'Minorista',
                'direccion_id' => 5,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'San Francisco',
                'tipo' => 'Mayorista',
                'direccion_id' => 6,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'La Esquina del Mueble',
                'tipo' => 'Minorista',
                'direccion_id' => 7,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Mueblería Económica',
                'tipo' => 'Mixto',
                'direccion_id' => 8,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Diseño y Decoración',
                'tipo' => 'Minorista',
                'direccion_id' => 9,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Muebles El Roble',
                'tipo' => 'Mayorista',
                'direccion_id' => 10,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Sucursal Sur',
                'tipo' => 'Minorista',
                'direccion_id' => 11,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Mueblería del Centro',
                'tipo' => 'Mixto',
                'direccion_id' => 12,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Almacén de Muebles',
                'tipo' => 'Mayorista',
                'direccion_id' => 13,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Muebles Express',
                'tipo' => 'Minorista',
                'direccion_id' => 14,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
            [
                'nombre' => 'Innovación en Muebles',
                'tipo' => 'Mixto',
                'direccion_id' => 15,
                'fotografia_id' => null,
                'estatus' => 1,
            ],
        ];

        foreach ($mueblerias as $muebleria) {
            DB::table('mueblerias')->insert($muebleria);
        }

        $this->command->info('15 mueblerías creadas exitosamente!');
    }
}