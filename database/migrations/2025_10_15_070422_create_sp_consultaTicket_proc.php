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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaTicket');

        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaTicket`(IN `codigo` int)
BEGIN
SELECT 
				`v`.`codigo_ventas` AS `codigo`,
				`mu`.`nombre` AS `muebleria`,
			    `v`.`cantidad`, 
                `me`.`nombre` AS `mueble`,
				CONCAT(`c`.`materno`,
						' ',
						`c`.`paterno`,
						' ',
						`c`.`nombres`) AS `cliente`,
				CONCAT(`u`.`materno`,
						' ',
						`u`.`paterno`,
						' ',
						`u`.`nombres`) AS `vendedor`,
				`v`.`precio`,
                `v`.`primer_abono`,
                			IFNULL((SELECT 
								SUM(`abonos`.`pago`)
							FROM
								`abonos`
							WHERE
								(`abonos`.`codigo_ventas` = `v`.`codigo_ventas`)),
						0) AS `pago`,
				`v`.`forma_pago`,
				`v`.`tipo_pago`,
				`v`.`color`,
				`v`.`fecha_venta`,
				`v`.`fecha_entrega`,
				`v`.`comision`
			FROM
				((((`ventas` `v`
				JOIN `mueblerias` `mu` ON ((`mu`.`mueblerias_id` = `v`.`mueblerias_id`)))
				JOIN `muebles` `me` ON ((`me`.`muebles_id` = `v`.`muebles_id`)))
				JOIN `usuarios` `u` ON ((`u`.`usuarios_id` = `v`.`usuarios_id`)))
				JOIN `clientes` `c` ON ((`c`.`clientes_id` = `v`.`clientes_id`)))
			WHERE
				v.codigo_ventas=codigo;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaTicket");
    }
};
