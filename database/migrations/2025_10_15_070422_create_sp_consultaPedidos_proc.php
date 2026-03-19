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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaPedidos');
		DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaPedidos`(IN `_accion` varchar(15),  IN `_mueble` INT(11), IN `_usuario` INT(11),
IN `_muebleria` INT(11),
IN `_cantidad` INT(5),
IN `_fecha` TIMESTAMP,
IN `_produccion` INT(1),
IN `_pedido` INT(11),
IN `_fecha_produccion` TIMESTAMP,
IN `_estatus` INT(11),
IN `_procedencia` INT(11),
IN `_fecha_comienzo` TIMESTAMP,
IN `_fecha_termino` TIMESTAMP,
IN `_fecha_traspaso` TIMESTAMP, 
IN `_fecha_entrega` TIMESTAMP,
IN `_comprobante` VARCHAR(500) ,
IN `_descripcion` VARCHAR(300), 
IN `_codigo` INT(11),
IN `_id` INT(11))
BEGIN
  DECLARE _idPedido INT DEFAULT 0;
  DECLARE _codigoPedido INT DEFAULT 0;
	CASE _accion 
    WHEN 'GET' THEN
			SELECT 
			p.pedidos_id
			,p.codigo_pedido
			,p.muebles_id
			,p.usuario_id
			,p.mueblerias_id
			,p.cantidad
			,p.fecha 
             ,p.comprobante
			,p.fecha_entrega
			,p.descripcion
			,m.nombre AS mueble
			,u.name AS usuario
			,mb.nombre AS muebleria
			FROM pedidos p
			INNER JOIN muebles m ON m.muebles_id = p.muebles_id
			INNER JOIN users u ON u.id = p.usuario_id
			INNER JOIN mueblerias mb ON mb.mueblerias_id= p.mueblerias_id
			WHERE p.produccion = 0 AND p.cantidad > 0; 
			
  WHEN 'CODIGO' THEN 
    SELECT (MAX(codigo_pedido) + 1) as codigo FROM pedidos ORDER BY codigo_pedido ASC LIMIT 1;

	WHEN 'SAVE' THEN

	
		INSERT INTO pedidos(muebles_id,usuario_id,mueblerias_id,cantidad,cantidad_inicial,fecha,produccion,codigo_pedido,fecha_entrega,comprobante,descripcion)
		VALUES(_mueble,_usuario,_muebleria,_cantidad,_cantidad,NOW(),0,_codigoPedido,_fecha_entrega,_comprobante,_descripcion);
    END CASE;
END");
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaPedidos");
	}
};
