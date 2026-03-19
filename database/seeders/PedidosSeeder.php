<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan los datos necesarios
        $this->verificarDependencias();

        // Obtener IDs disponibles
        $muebles = DB::table('muebles')->pluck('muebles_id')->toArray();
        $usuarios = DB::table('users')->pluck('id')->toArray();
        $mueblerias = DB::table('mueblerias')->pluck('mueblerias_id')->toArray();

        if (empty($muebles) || empty($usuarios) || empty($mueblerias)) {
            $this->command->error('No hay muebles, usuarios o mueblerías para crear pedidos');
            return;
        }

        $pedidos = [];
        $codigoPedido = 1000; // Código inicial

        // ========== PEDIDOS PENDIENTES (produccion = 0) ==========
        
        // Pedido 1 - Sofás para Mueblería Central
        $pedidos[] = [
            'muebles_id' => $muebles[0] ?? 1, // Sofá
            'usuario_id' => $usuarios[0] ?? 1,
            'mueblerias_id' => $mueblerias[0] ?? 1,
            'cantidad' => 15,
            'cantidad_inicial' => 15,
            'fecha' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'descripcion' => 'Pedido urgente para temporada alta',
            'fecha_entrega' => Carbon::now()->addDays(20)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-001.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 2 - Mesas de comedor
        $pedidos[] = [
            'muebles_id' => $muebles[4] ?? 5, // Mesa de comedor
            'usuario_id' => $usuarios[1] ?? 2,
            'mueblerias_id' => $mueblerias[1] ?? 2,
            'cantidad' => 8,
            'cantidad_inicial' => 8,
            'fecha' => Carbon::now()->subDays(8)->format('Y-m-d'),
            'descripcion' => 'Cliente corporativo, entrega programada',
            'fecha_entrega' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-002.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 3 - Sillas para oficina
        $pedidos[] = [
            'muebles_id' => $muebles[13] ?? 14, // Silla ejecutiva
            'usuario_id' => $usuarios[2] ?? 3,
            'mueblerias_id' => $mueblerias[2] ?? 3,
            'cantidad' => 25,
            'cantidad_inicial' => 25,
            'fecha' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'descripcion' => 'Pedido para nueva sucursal',
            'fecha_entrega' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => null,
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 4 - Camas matrimoniales
        $pedidos[] = [
            'muebles_id' => $muebles[8] ?? 9, // Cama matrimonial
            'usuario_id' => $usuarios[0] ?? 1,
            'mueblerias_id' => $mueblerias[3] ?? 4,
            'cantidad' => 10,
            'cantidad_inicial' => 10,
            'fecha' => Carbon::now()->subDays(12)->format('Y-m-d'),
            'descripcion' => 'Pedido especial con acabado premium',
            'fecha_entrega' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-003.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 5 - Escritorios ejecutivos
        $pedidos[] = [
            'muebles_id' => $muebles[12] ?? 13, // Escritorio ejecutivo
            'usuario_id' => $usuarios[3] ?? 4,
            'mueblerias_id' => $mueblerias[0] ?? 1,
            'cantidad' => 12,
            'cantidad_inicial' => 12,
            'fecha' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'descripcion' => 'Para proyecto de remodelación de oficinas',
            'fecha_entrega' => Carbon::now()->addDays(18)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-004.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 6 - Libreros
        $pedidos[] = [
            'muebles_id' => $muebles[14] ?? 15, // Librero
            'usuario_id' => $usuarios[1] ?? 2,
            'mueblerias_id' => $mueblerias[4] ?? 5,
            'cantidad' => 18,
            'cantidad_inicial' => 18,
            'fecha' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'descripcion' => 'Pedido para escuela privada',
            'fecha_entrega' => Carbon::now()->addDays(35)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => null,
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 7 - Muebles de cocina
        $pedidos[] = [
            'muebles_id' => $muebles[16] ?? 17, // Alacena
            'usuario_id' => $usuarios[2] ?? 3,
            'mueblerias_id' => $mueblerias[1] ?? 2,
            'cantidad' => 14,
            'cantidad_inicial' => 14,
            'fecha' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'descripcion' => 'Pedido mayoreo con descuento aplicado',
            'fecha_entrega' => Carbon::now()->addDays(22)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-005.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 8 - Muebles infantiles
        $pedidos[] = [
            'muebles_id' => $muebles[26] ?? 27, // Cama infantil
            'usuario_id' => $usuarios[4] ?? 5,
            'mueblerias_id' => $mueblerias[2] ?? 3,
            'cantidad' => 20,
            'cantidad_inicial' => 20,
            'fecha' => Carbon::now()->subDays(4)->format('Y-m-d'),
            'descripcion' => 'Pedido para guardería nueva',
            'fecha_entrega' => Carbon::now()->addDays(28)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-006.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // ========== PEDIDOS EN PRODUCCIÓN (produccion = 1) ==========

        // Pedido 9 - En producción
        $pedidos[] = [
            'muebles_id' => $muebles[1] ?? 2, // Mesa de centro
            'usuario_id' => $usuarios[0] ?? 1,
            'mueblerias_id' => $mueblerias[0] ?? 1,
            'cantidad' => 5,
            'cantidad_inicial' => 12,
            'fecha' => Carbon::now()->subDays(25)->format('Y-m-d'),
            'descripcion' => 'En proceso de fabricación - 7 unidades completadas',
            'fecha_entrega' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'produccion' => 1,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-007.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 10 - En producción
        $pedidos[] = [
            'muebles_id' => $muebles[9] ?? 10, // Buró
            'usuario_id' => $usuarios[1] ?? 2,
            'mueblerias_id' => $mueblerias[3] ?? 4,
            'cantidad' => 8,
            'cantidad_inicial' => 15,
            'fecha' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'descripcion' => 'Producción avanzada al 60%',
            'fecha_entrega' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'produccion' => 1,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-008.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // ========== PEDIDOS COMPLETADOS (produccion = 1, cantidad = 0) ==========

        // Pedido 11 - Completado
        $pedidos[] = [
            'muebles_id' => $muebles[5] ?? 6, // Sillas de comedor
            'usuario_id' => $usuarios[2] ?? 3,
            'mueblerias_id' => $mueblerias[1] ?? 2,
            'cantidad' => 0,
            'cantidad_inicial' => 20,
            'fecha' => Carbon::now()->subDays(45)->format('Y-m-d'),
            'descripcion' => 'Pedido completado y entregado',
            'fecha_entrega' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'produccion' => 1,
            'id_venta' => 1001,
            'comprobante' => 'COMP-2024-009.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 12 - Completado
        $pedidos[] = [
            'muebles_id' => $muebles[10] ?? 11, // Ropero
            'usuario_id' => $usuarios[0] ?? 1,
            'mueblerias_id' => $mueblerias[2] ?? 3,
            'cantidad' => 0,
            'cantidad_inicial' => 6,
            'fecha' => Carbon::now()->subDays(40)->format('Y-m-d'),
            'descripcion' => 'Entrega realizada satisfactoriamente',
            'fecha_entrega' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'produccion' => 1,
            'id_venta' => 1002,
            'comprobante' => 'COMP-2024-010.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 13 - Pedido pequeño pendiente
        $pedidos[] = [
            'muebles_id' => $muebles[22] ?? 23, // Banco rústico
            'usuario_id' => $usuarios[3] ?? 4,
            'mueblerias_id' => $mueblerias[4] ?? 5,
            'cantidad' => 30,
            'cantidad_inicial' => 30,
            'fecha' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'descripcion' => 'Pedido para decoración de restaurante',
            'fecha_entrega' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => null,
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 14 - Pedido express
        $pedidos[] = [
            'muebles_id' => $muebles[17] ?? 18, // Mesa de cocina
            'usuario_id' => $usuarios[1] ?? 2,
            'mueblerias_id' => $mueblerias[0] ?? 1,
            'cantidad' => 6,
            'cantidad_inicial' => 6,
            'fecha' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'descripcion' => 'Pedido urgente - producción prioritaria',
            'fecha_entrega' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-011.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Pedido 15 - Pedido grande
        $pedidos[] = [
            'muebles_id' => $muebles[20] ?? 21, // Gabinete de baño
            'usuario_id' => $usuarios[4] ?? 5,
            'mueblerias_id' => $mueblerias[3] ?? 4,
            'cantidad' => 35,
            'cantidad_inicial' => 35,
            'fecha' => Carbon::now()->subDays(9)->format('Y-m-d'),
            'descripcion' => 'Pedido para complejo residencial',
            'fecha_entrega' => Carbon::now()->addDays(40)->format('Y-m-d'),
            'produccion' => 0,
            'id_venta' => null,
            'comprobante' => 'COMP-2024-012.pdf',
            'codigo_pedido' => $codigoPedido++,
        ];

        // Insertar pedidos
        foreach ($pedidos as $pedido) {
            DB::table('pedidos')->insert($pedido);
        }

        $this->command->info('✓ ' . count($pedidos) . ' pedidos creados exitosamente!');
        $this->command->info('');
        $this->mostrarResumen();
    }

    /**
     * Verificar que existan las tablas necesarias
     */
    private function verificarDependencias(): void
    {
        $muebles = DB::table('muebles')->count();
        if ($muebles === 0) {
            $this->command->warn('⚠️  La tabla "muebles" está vacía. Ejecuta primero: php artisan db:seed --class=MueblesSeeder');
        }

        $usuarios = DB::table('users')->count();
        if ($usuarios === 0) {
            $this->command->warn('⚠️  La tabla "users" está vacía. Ejecuta primero: php artisan db:seed --class=UsersSeeder');
        }

        $mueblerias = DB::table('mueblerias')->count();
        if ($mueblerias === 0) {
            $this->command->warn('⚠️  La tabla "mueblerias" está vacía. Ejecuta primero: php artisan db:seed --class=MuebleriasSeeder');
        }
    }

    /**
     * Mostrar resumen de pedidos creados
     */
    private function mostrarResumen(): void
    {
        $this->command->info('=== RESUMEN DE PEDIDOS CREADOS ===');
        $this->command->info('');

        // Pedidos por estado
        $pendientes = DB::table('pedidos')->where('produccion', 0)->count();
        $enProduccion = DB::table('pedidos')
            ->where('produccion', 1)
            ->where('cantidad', '>', 0)
            ->count();
        $completados = DB::table('pedidos')
            ->where('produccion', 1)
            ->where('cantidad', 0)
            ->count();

        $this->command->info("• Pedidos pendientes: {$pendientes}");
        $this->command->info("• Pedidos en producción: {$enProduccion}");
        $this->command->info("• Pedidos completados: {$completados}");
        $this->command->info('');

        // Cantidad total
        $cantidadTotal = DB::table('pedidos')->sum('cantidad');
        $cantidadInicial = DB::table('pedidos')->sum('cantidad_inicial');
        
        $this->command->info("Cantidad pendiente de producción: {$cantidadTotal} unidades");
        $this->command->info("Cantidad total solicitada: {$cantidadInicial} unidades");
        $this->command->info("Unidades completadas: " . ($cantidadInicial - $cantidadTotal) . " unidades");
        $this->command->info('');

        // Pedidos por mueblería
        $this->command->info('Pedidos por mueblería:');
        $porMuebleria = DB::table('pedidos')
            ->join('mueblerias', 'pedidos.mueblerias_id', '=', 'mueblerias.mueblerias_id')
            ->select('mueblerias.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('mueblerias.nombre')
            ->get();

        foreach ($porMuebleria as $item) {
            $this->command->info("  - {$item->nombre}: {$item->total} pedidos");
        }
        $this->command->info('');
    }
}