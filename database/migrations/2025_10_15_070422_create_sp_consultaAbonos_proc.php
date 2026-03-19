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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaAbonos');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaAbonos`(IN `var_accion` VARCHAR(15), IN `var_venta` INT(11), IN `var_abono` INT(20),IN `var_fecha` DATE,IN `var_usuario` INT(11),IN `var_codigo` INT)
BEGIN
	CASE var_accion 
		WHEN 'GETTODAY' THEN

      SELECT 
			ap.abonos_id AS id,
			CONCAT(u.paterno,\" \",u.materno,\" \",u.nombres) AS nombre_usuario,
			v.muebles_id,
			m.nombre AS mueble,
			CONCAT(c.paterno,\" \",c.materno,\" \",c.nombres) AS nombre_cliente,
      ap.pago,
      ap.fecha
			FROM abonos_programados ap 
			INNER JOIN ventas v ON v.ventas_id = ap.ventas_id
			INNER JOIN usuarios u ON u.usuarios_id = ap.usuarios_id
      INNER JOIN clientes c ON c.clientes_id = v.clientes_id
			INNER JOIN muebles m ON m.muebles_id = v.muebles_id
			WHERE ap.codigo_ventas = var_codigo;

		WHEN 'GETPROGRAMADOS' THEN

			SELECT 
			ap.abonos_id AS id,
			CONCAT(u.paterno,\" \",u.materno,\" \",u.nombres) AS nombre_usuario,
			v.muebles_id,
			m.nombre AS mueble,
			CONCAT(c.paterno,\" \",c.materno,\" \",c.nombres) AS nombre_cliente,
      ap.pago,
      ap.fecha
			FROM abonos_programados ap 
			INNER JOIN ventas v ON v.codigo_ventas = ap.codigo_ventas
			INNER JOIN usuarios u ON u.usuarios_id = ap.usuarios_id
      INNER JOIN clientes c ON c.clientes_id = v.clientes_id
			INNER JOIN muebles m ON m.muebles_id = v.muebles_id
			WHERE ap.codigo_ventas = var_codigo;

    WHEN 'GET' THEN
				SELECT
				a.abonos_id AS id
				,CONCAT(u.materno,' ',u.paterno,' ',u.nombres) AS vendedor
				,(SELECT GROUP_CONCAT(CONCAT(v.cantidad,\" - \",mu.nombre)) AS mueble  
					FROM ventas v
					INNER JOIN muebles mu ON mu.muebles_id = v.muebles_id
					WHERE codigo_ventas = a.codigo_ventas) AS muebles
				,(SELECT MAX(fecha_venta)  
					FROM ventas v
					WHERE codigo_ventas = a.codigo_ventas) AS fechaVenta
				,(SELECT DISTINCT(CONCAT(c.materno,' ',c.paterno,' ',c.nombres)) AS cliente
					FROM ventas v 
					INNER JOIN clientes c ON v.clientes_id = c.clientes_id
				WHERE codigo_ventas=a.codigo_ventas) AS cliente
				,a.fecha
				,a.pago
				,a.codigo_ventas AS codigo
				FROM abonos a
				INNER JOIN usuarios u ON u.usuarios_id = a.usuarios_id
				WHERE a.codigo_ventas=var_codigo;


   WHEN 'SAVEPROGRAMADOS' THEN
			INSERT INTO abonos_programados (usuarios_id, fecha, pago,codigo_ventas) 
      VALUES (var_usuario,var_fecha,var_abono,var_codigo);
   WHEN 'SAVE' THEN
			INSERT INTO abonos (ventas_id, usuarios_id, fecha, pago,codigo_ventas) 
      VALUES (var_venta,var_usuario,NOW(),var_abono,var_codigo);


			UPDATE abonos_programados 			
			SET pagado = 1, 
          pago = var_abono
			WHERE codigo_ventas = var_codigo
			AND DATE(fecha) = DATE(NOW());
 WHEN 'DELETE' THEN
			DELETE FROM abonos WHERE codigo_ventas = var_codigo;

	 END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaAbonos");
    }
};
