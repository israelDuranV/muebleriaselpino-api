<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuebleFotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fotos = [
            // Mueble 1 - Sofás
            [
                'muebles_id' => 1,
                'url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc',
                'orden' => 1,
                'descripcion' => 'Sofá moderno de sala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 1,
                'url' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e',
                'orden' => 2,
                'descripcion' => 'Vista lateral del sofá',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 1,
                'url' => 'https://images.unsplash.com/photo-1540574163026-643ea20ade25',
                'orden' => 3,
                'descripcion' => 'Sofá con cojines decorativos',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 2 - Mesas de comedor
            [
                'muebles_id' => 2,
                'url' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200',
                'orden' => 1,
                'descripcion' => 'Mesa de comedor de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 2,
                'url' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2',
                'orden' => 2,
                'descripcion' => 'Comedor completo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 2,
                'url' => 'https://images.unsplash.com/photo-1556912172-45b7abe8b7e1',
                'orden' => 3,
                'descripcion' => 'Mesa con sillas',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 3 - Camas
            [
                'muebles_id' => 3,
                'url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85',
                'orden' => 1,
                'descripcion' => 'Cama matrimonial moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 3,
                'url' => 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d',
                'orden' => 2,
                'descripcion' => 'Recámara completa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 3,
                'url' => 'https://images.unsplash.com/photo-1556020685-ae41abfc9365',
                'orden' => 3,
                'descripcion' => 'Cama con cabecera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 4 - Libreros
            [
                'muebles_id' => 4,
                'url' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156',
                'orden' => 1,
                'descripcion' => 'Librero moderno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 4,
                'url' => 'https://images.unsplash.com/photo-1595428773652-c7b5fc2d04bb',
                'orden' => 2,
                'descripcion' => 'Estantería de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 4,
                'url' => 'https://images.unsplash.com/photo-1583623025817-d180a2221d0a',
                'orden' => 3,
                'descripcion' => 'Librero con decoración',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 5 - Sillas
            [
                'muebles_id' => 5,
                'url' => 'https://images.unsplash.com/photo-1503602642458-232111445657',
                'orden' => 1,
                'descripcion' => 'Silla de comedor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 5,
                'url' => 'https://images.unsplash.com/photo-1581539250439-c96689b516dd',
                'orden' => 2,
                'descripcion' => 'Silla tapizada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 5,
                'url' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c',
                'orden' => 3,
                'descripcion' => 'Silla moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 6 - Escritorios
            [
                'muebles_id' => 6,
                'url' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd',
                'orden' => 1,
                'descripcion' => 'Escritorio de oficina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 6,
                'url' => 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c',
                'orden' => 2,
                'descripcion' => 'Escritorio minimalista',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 6,
                'url' => 'https://images.unsplash.com/photo-1595428774655-dbbb5d4ec3e9',
                'orden' => 3,
                'descripcion' => 'Home office',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 7 - Mesas de centro
            [
                'muebles_id' => 7,
                'url' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad0',
                'orden' => 1,
                'descripcion' => 'Mesa de centro de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 7,
                'url' => 'https://images.unsplash.com/photo-1565191999001-551c187427bb',
                'orden' => 2,
                'descripcion' => 'Mesa de sala moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 8 - Cómodas
            [
                'muebles_id' => 8,
                'url' => 'https://images.unsplash.com/photo-1595428774638-6a7dc4eefddc',
                'orden' => 1,
                'descripcion' => 'Cómoda de recámara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 8,
                'url' => 'https://images.unsplash.com/photo-1588046130717-0eb0c9a5e50d',
                'orden' => 2,
                'descripcion' => 'Cajonera de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 9 - Sillones
            [
                'muebles_id' => 9,
                'url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7',
                'orden' => 1,
                'descripcion' => 'Sillón individual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 9,
                'url' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267',
                'orden' => 2,
                'descripcion' => 'Sillón de lectura',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 10 - Armarios
            [
                'muebles_id' => 10,
                'url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64',
                'orden' => 1,
                'descripcion' => 'Armario moderno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 10,
                'url' => 'https://images.unsplash.com/photo-1595428773894-75e6dc5c2c21',
                'orden' => 2,
                'descripcion' => 'Closet organizado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 10,
                'url' => 'https://images.unsplash.com/photo-1600210491892-03d54c0aaf87',
                'orden' => 3,
                'descripcion' => 'Interior del armario',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 11 - Banco
            [
                'muebles_id' => 11,
                'url' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4',
                'orden' => 1,
                'descripcion' => 'Banco de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 12 - Barra
            [
                'muebles_id' => 12,
                'url' => 'https://images.unsplash.com/photo-1572719284919-f210085f4b00',
                'orden' => 1,
                'descripcion' => 'Barra de cocina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Mueble 1 - Sofás
            [
                'muebles_id' => 13,
                'url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc',
                'orden' => 1,
                'descripcion' => 'Sofá moderno de sala',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 14,
                'url' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e',
                'orden' => 2,
                'descripcion' => 'Vista lateral del sofá',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 15,
                'url' => 'https://images.unsplash.com/photo-1540574163026-643ea20ade25',
                'orden' => 3,
                'descripcion' => 'Sofá con cojines decorativos',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 2 - Mesas de comedor
            [
                'muebles_id' => 16,
                'url' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200',
                'orden' => 1,
                'descripcion' => 'Mesa de comedor de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 17,
                'url' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2',
                'orden' => 2,
                'descripcion' => 'Comedor completo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 18,
                'url' => 'https://images.unsplash.com/photo-1556912172-45b7abe8b7e1',
                'orden' => 3,
                'descripcion' => 'Mesa con sillas',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 3 - Camas
            [
                'muebles_id' => 19,
                'url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85',
                'orden' => 1,
                'descripcion' => 'Cama matrimonial moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 20,
                'url' => 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d',
                'orden' => 2,
                'descripcion' => 'Recámara completa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 21,
                'url' => 'https://images.unsplash.com/photo-1556020685-ae41abfc9365',
                'orden' => 3,
                'descripcion' => 'Cama con cabecera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 4 - Libreros
            [
                'muebles_id' => 22,
                'url' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156',
                'orden' => 1,
                'descripcion' => 'Librero moderno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 23,
                'url' => 'https://images.unsplash.com/photo-1595428773652-c7b5fc2d04bb',
                'orden' => 2,
                'descripcion' => 'Estantería de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 24,
                'url' => 'https://images.unsplash.com/photo-1583623025817-d180a2221d0a',
                'orden' => 3,
                'descripcion' => 'Librero con decoración',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 5 - Sillas
            [
                'muebles_id' => 25,
                'url' => 'https://images.unsplash.com/photo-1503602642458-232111445657',
                'orden' => 1,
                'descripcion' => 'Silla de comedor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 26,
                'url' => 'https://images.unsplash.com/photo-1581539250439-c96689b516dd',
                'orden' => 2,
                'descripcion' => 'Silla tapizada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 27,
                'url' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c',
                'orden' => 3,
                'descripcion' => 'Silla moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 6 - Escritorios
            [
                'muebles_id' => 28,
                'url' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd',
                'orden' => 1,
                'descripcion' => 'Escritorio de oficina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 29,
                'url' => 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c',
                'orden' => 2,
                'descripcion' => 'Escritorio minimalista',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 29,
                'url' => 'https://images.unsplash.com/photo-1595428774655-dbbb5d4ec3e9',
                'orden' => 3,
                'descripcion' => 'Home office',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 7 - Mesas de centro
            [
                'muebles_id' => 28,
                'url' => 'https://images.unsplash.com/photo-1532372576444-dda954194ad0',
                'orden' => 1,
                'descripcion' => 'Mesa de centro de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 27,
                'url' => 'https://images.unsplash.com/photo-1565191999001-551c187427bb',
                'orden' => 2,
                'descripcion' => 'Mesa de sala moderna',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 8 - Cómodas
            [
                'muebles_id' => 26,
                'url' => 'https://images.unsplash.com/photo-1595428774638-6a7dc4eefddc',
                'orden' => 1,
                'descripcion' => 'Cómoda de recámara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 25,
                'url' => 'https://images.unsplash.com/photo-1588046130717-0eb0c9a5e50d',
                'orden' => 2,
                'descripcion' => 'Cajonera de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 9 - Sillones
            [
                'muebles_id' => 24,
                'url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7',
                'orden' => 1,
                'descripcion' => 'Sillón individual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 23,
                'url' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267',
                'orden' => 2,
                'descripcion' => 'Sillón de lectura',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 10 - Armarios
            [
                'muebles_id' => 22,
                'url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64',
                'orden' => 1,
                'descripcion' => 'Armario moderno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 21,
                'url' => 'https://images.unsplash.com/photo-1595428773894-75e6dc5c2c21',
                'orden' => 2,
                'descripcion' => 'Closet organizado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'muebles_id' => 20,
                'url' => 'https://images.unsplash.com/photo-1600210491892-03d54c0aaf87',
                'orden' => 3,
                'descripcion' => 'Interior del armario',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 11 - Banco
            [
                'muebles_id' => 19,
                'url' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4',
                'orden' => 1,
                'descripcion' => 'Banco de madera',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mueble 12 - Barra
            [
                'muebles_id' => 18,
                'url' => 'https://images.unsplash.com/photo-1572719284919-f210085f4b00',
                'orden' => 1,
                'descripcion' => 'Barra de cocina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('mueble_fotos')->insert($fotos);
    }
}
