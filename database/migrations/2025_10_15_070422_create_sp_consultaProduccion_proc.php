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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaProduccion');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaProduccion`(IN `_accion` VARCHAR(15),  IN `_mueble` INT(11),                          IN `_usuario` INT(11),
                                       IN `_muebleria` INT(11),
                                       IN `_estatus` INT(11),
                                       IN `_procedencia` INT(11),
                                       IN `_id` INT(11))
BEGIN
	CASE _accion 
		WHEN 'START' THEN
			UPDATE inventario_general 
			SET fecha_comienzo = NOW()
			WHERE inventarios_id=_id;
		WHEN 'END' THEN
			UPDATE inventario_general 
			SET fecha_termino = NOW()
			WHERE inventarios_id=_id;
		WHEN 'GET' THEN
			SELECT 
			i.inventarios_id
			,i.muebles_id
			,pe.descripcion
			,i.pedido_id
			,i.fecha_produccion
      ,pe.fecha_entrega
			,pe.comprobante
			,i.estatus
			,i.usuario_id
			,i.procedencia
			,i.muebleria_id
      ,mu.nombre AS muebleria
	  ,i.cantidad
			,i.fecha_comienzo
			,i.fecha_traspaso
      ,i.fecha_termino
      ,m.nombre AS mueble
			,m.encerado
			,u.name AS usuario
      ,TIMESTAMPDIFF(SECOND,i.fecha_comienzo,i.fecha_termino) AS tiempo
			FROM inventario_general i
			INNER JOIN muebles m ON m.muebles_id = i.muebles_id
			INNER JOIN mueblerias mu ON mu.mueblerias_id = i.muebleria_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN users u ON u.id = pe.usuario_id
			WHERE i.estatus = 1
			ORDER BY i.pedido_id ASC;

		WHEN 'SEND' THEN
				UPDATE inventario_general 
        SET estatus = 2,
						muebleria_id= _muebleria,
            fecha_traspaso=NOW()
				WHERE inventarios_id = _id;

        INSERT INTO inventario_movimientos (muebles_id,pedido_id,codigo_pedido,fecha_produccion,descripcion,fecha_entrega,estatus,usuario_id,procedencia,muebleria_id,fecha_comienzo,fecha_termino,fecha_traspaso,usuario_acepta,aceptado)	
				SELECT 
						muebles_id,pedido_id
						,codigo_pedido
						,fecha_produccion
						,descripcion
						,fecha_entrega
						,estatus
						,usuario_id
						,_procedencia AS procedencia
						,_muebleria AS muebleria_id
						,fecha_comienzo
						,fecha_termino
						,NOW() AS fecha_traspaso
						,0 AS usuario_acepta
						,0 AS aceptado 
				FROM inventario_general 
				WHERE inventarios_id=_id;
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaProduccion");
    }
};
