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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaVenta');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaVenta`(
	IN `_accion` VARCHAR(30),
	IN `var_empleado` INT(11),
	IN `var_cliente` INT(11),
	IN `var_muebleria` INT(11),
	IN `var_precio` INT(11),
	IN `var_terminado` VARCHAR(20),
	IN `var_cantidad` INT(5),
	IN `var_color` VARCHAR(100),
	IN `var_tipopago` VARCHAR(100),
	IN `var_formapago` VARCHAR(100),
	IN `var_entrega` DATE,
	IN `var_comision` INT(6),
	IN `var_idMueble` INT(11),
	IN `var_pago` INT(20),
	IN `_fechaInicio` DATE,
	IN `_fechaFinal` DATE,
	IN `var_id` INT(11),
	IN `var_codigo` INT(11)
)
BEGIN
DECLARE _venta INT DEFAULT 0;
DECLARE _muebleInventario INT DEFAULT 0;
DECLARE codigo_pedido INT DEFAULT 0;
DECLARE codigo_venta INT DEFAULT 0;
DECLARE existe_abonos INT DEFAULT 0;

CASE _accion  
    WHEN 'CODIGO' THEN 
   SELECT (MAX(codigo_ventas) + 1) AS codigo FROM ventas ORDER BY codigo_ventas ASC LIMIT 1;

		WHEN 'SAVE' THEN

 -- SET codigo_venta = (SELECT (MAX(codigo_ventas) + 1) AS codigo FROM ventas ORDER BY codigo_ventas ASC LIMIT 1);

			INSERT INTO ventas (
				mueblerias_id
				,muebles_id
				,usuarios_id
				,clientes_id
				,forma_pago
				,tipo_pago
				,cantidad
				,color
				,precio
        ,primer_abono
				,pagado
				,fecha_venta
				,comision
				,fecha_entrega
        ,codigo_ventas        
) 
				VALUES(
				var_muebleria
				,var_idMueble
				,var_empleado
				,var_cliente
				,var_formapago
				,var_tipopago
				,var_cantidad
				,var_color
				,var_precio
				,var_pago
				,IF(var_formapago,1,0)
				,NOW()
				,var_comision
				,var_entrega
				,var_codigo
				);
		SET _venta = LAST_INSERT_ID();

 SET existe_abonos = (SELECT COUNT(1) FROM abonos WHERE codigo_ventas = var_codigo); 
		 IF (existe_abonos = 0) THEN
				INSERT INTO 
						abonos(
							ventas_id
							,usuarios_id
							,fecha
							,pago
							,codigo_ventas
							) 
							VALUES(
							_venta
							,var_empleado
							,NOW()
							,var_pago
							,var_codigo
							);
		END IF;
		IF (existe_abonos > 0) THEN
				UPDATE abonos 
				SET pago = pago + var_pago
				WHERE codigo_ventas = var_codigo;
		END IF;
    
		WHEN 'REPORTE' THEN
       SELECT * FROM view_ventas;

   WHEN 'REPORTEFORDATE' THEN

	SELECT 
				`v`.`codigo_ventas` AS `id`,
				`v`.`mueblerias_id` AS `mueblerias_id`,
				`u`.`usuarios_id` AS `usuarios_id`,
				`c`.`clientes_id` AS `clientes_id`,
				`mu`.`nombre` AS `muebleria`,
				GROUP_CONCAT(CONCAT(`v`.`cantidad`, ' - ', `me`.`nombre`)
					SEPARATOR ',') AS `mueble`,
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
				SUM(`v`.`precio`) AS `precio`,
				(SELECT 
						COUNT(1)
					FROM
						`abonos`
					WHERE
						(`abonos`.`codigo_ventas` = `v`.`codigo_ventas`)) AS `numero_abonos`,
				IFNULL((SELECT 
								SUM(`abonos`.`pago`)
							FROM
								`abonos`
							WHERE
								(`abonos`.`codigo_ventas` = `v`.`codigo_ventas`)),
						0) AS `abonos`,
				(`v`.`precio` - IFNULL((SELECT 
								SUM(`abonos`.`pago`)
							FROM
								`abonos`
							WHERE
								(`abonos`.`codigo_ventas` = `v`.`codigo_ventas`)),
						0)) AS `resta`,
				`v`.`forma_pago` AS `forma_pago`,
				`v`.`tipo_pago` AS `tipo_pago`,
				SUM(`v`.`cantidad`) AS `cantidad`,
				`v`.`color` AS `color`,
				`v`.`fecha_venta` AS `fecha_venta`,
				`v`.`fecha_entrega` AS `fecha_entrega`,
				`v`.`comision` AS `comision`
			FROM
				((((`ventas` `v`
				JOIN `mueblerias` `mu` ON ((`mu`.`mueblerias_id` = `v`.`mueblerias_id`)))
				JOIN `muebles` `me` ON ((`me`.`muebles_id` = `v`.`muebles_id`)))
				JOIN `usuarios` `u` ON ((`u`.`usuarios_id` = `v`.`usuarios_id`)))
				JOIN `clientes` `c` ON ((`c`.`clientes_id` = `v`.`clientes_id`)))
			WHERE
				(`v`.`fecha_venta` > '2022-01-01') AND (`v`.`fecha_venta` BETWEEN _fechaInicio AND _fechaFinal)
			GROUP BY `v`.`codigo_ventas`
			ORDER BY `v`.`ventas_id` DESC;

		WHEN 'INVENTARIOXMUEBLE' THEN
			SELECT 
				i.muebleria_id
				,mu.nombre
				,COUNT(*) - (SELECT COUNT(*) FROM ventas v 
											WHERE v.muebles_id = m.muebles_id AND 
                        v.mueblerias_id = i.muebleria_id
				) AS cantidad
			FROM inventario_general i
			INNER JOIN muebles m ON m.muebles_id = i.muebles_id
			INNER JOIN mueblerias mu ON mu.mueblerias_id = i.muebleria_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
			AND m.muebles_id = var_idMueble
			GROUP BY i.muebleria_id;

	WHEN 'DELETE' THEN
     DELETE FROM ventas WHERE codigo_ventas = var_id;
  
END CASE;

END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaVenta");
    }
};
