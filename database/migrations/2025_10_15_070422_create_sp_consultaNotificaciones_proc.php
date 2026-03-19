<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaNotificaciones');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaNotificaciones`(IN `var_idMuebleria` int)
BEGIN
SELECT 
			ap.fecha
			,CONCAT(c.paterno,\" \",c.materno,\" \",c.nombres) AS nombre_cliente
			,CONCAT(\"Debe realizar su pago de: \",ap.pago) AS mensaje1
			,CONCAT(\" por el mueble: \",m.nombre) AS mensaje2
			FROM abonos_programados ap 
			INNER JOIN ventas v ON v.codigo_ventas = ap.codigo_ventas
			INNER JOIN usuarios u ON u.usuarios_id = ap.usuarios_id
      INNER JOIN clientes c ON c.clientes_id = v.clientes_id
			INNER JOIN muebles m ON m.muebles_id = v.muebles_id
			INNER JOIN mueblerias mu ON mu.mueblerias_id = v.mueblerias_id
			WHERE DATE(ap.fecha) = DATE(NOW()) 
			AND mu.mueblerias_id= var_idMuebleria;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaNotificaciones");
    }
};
