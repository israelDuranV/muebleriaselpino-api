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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaInventario');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaInventario`(IN _accion VARCHAR(20), _mueble INT(11)                                          ,_fecha DATE
                                                    ,_tipo VARCHAR(20)
                                                    ,_cantidad INT(3)
                                                    ,_usuario INT(11)
                                                    ,_muebleria INT(11)
, _procedencia INT(11), _comentario VARCHAR(100)
                                                    ,_comienzo VARCHAR(200)
                                                    ,_termino VARCHAR(200), _id INT(11))
BEGIN
	CASE _accion  
		WHEN 'INSERT' THEN
			INSERT INTO inventarios(muebles_id,fecha,tipo,cantidad,usuarios_id,mueblerias_id,comentario,comienzo,termino) 
			VALUES(_mueble,_fecha,_tipo,_cantidad,_usuario,_muebleria,_comentario,_comienzo,_termino);
		WHEN 'GET' THEN
SELECT 
			i.inventarios_id
			,i.muebles_id
			,i.codigo_pedido AS pedido
			,i.fecha_produccion
      ,pe.fecha_entrega
      ,pe.comprobante
			,i.estatus
			,i.usuario_id
			,i.muebleria_id
			,i.fecha_comienzo
			,i.fecha_traspaso AS fecha
			,m.nombre AS mueble
      ,d.name AS departamento
      ,'SALIDA - TRASPASO' AS tipo
			,u.name AS usuario
      ,mu.nombre AS procedencia
			FROM inventario_general i
			INNER JOIN muebles m ON m.muebles_id = i.muebles_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN users u ON u.id = i.usuario_id
			INNER JOIN departamentos d ON d.departamento_id = m.departamento_id
      INNER JOIN mueblerias mu ON i.procedencia = mu.mueblerias_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
      AND i.procedencia = _muebleria
	    AND i.aceptado = 1
UNION 
SELECT 
			i.inventarios_id
			,i.muebles_id
			,i.codigo_pedido AS pedido
			,i.fecha_produccion
      ,pe.fecha_entrega
      ,pe.comprobante
			,i.estatus
			,i.usuario_id
			,i.muebleria_id
			,i.fecha_comienzo
			,i.fecha_traspaso AS fecha
			,m.nombre AS mueble
      ,d.name AS departamento
      ,IF(i.estatus = 2, 'ENTRADA','SALIDA') AS tipo
			,u.name AS usuario
      ,mu.nombre AS procedencia
			FROM inventario_general i
			INNER JOIN muebles m ON m.muebles_id = i.muebles_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN users u ON u.id = i.usuario_id
			INNER JOIN departamentos d ON d.departamento_id = m.departamento_id
      INNER JOIN mueblerias mu ON i.procedencia = mu.mueblerias_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
      AND i.muebleria_id = _muebleria
	    AND i.aceptado = 1
UNION
SELECT 
      'NULL' AS inventarios_id
			,v.muebles_id
			,\"N/A\" AS pedido
			,\"NULL\" AS fecha_produccion
      ,\"\" AS fecha_entrega
      ,\"\" AS comprobante
      ,3 AS estatus
			,u.id
			,v.mueblerias_id AS muebleria_id
			,\"NULL\" AS fecha_comienzo
			,v.fecha_venta AS fecha
			,m.nombre AS mueble
      ,d.name AS departamento
      ,'SALIDA-VENTA' AS tipo
			,u.name AS usuario
      ,mu.nombre AS procedencia
	FROM ventas v
	INNER JOIN users u ON v.usuarios_id = u.id
	INNER JOIN mueblerias mu ON v.mueblerias_id = mu.mueblerias_id
	INNER JOIN muebles m ON v.muebles_id = m.muebles_id
	INNER JOIN departamentos d ON m.departamento_id = d.departamento_id
	WHERE v.mueblerias_id = _muebleria;

		WHEN 'GETINVENTARIO' THEN


DROP TABLE IF EXISTS inventario_gral_tmp;
CREATE TABLE IF NOT EXISTS inventario_gral_tmp AS (
SELECT 
				i.inventarios_id
				,i.muebles_id
				,i.codigo_pedido AS pedido
				,i.estatus
				,i.usuario_id
				,i.procedencia
				,i.muebleria_id
        ,COUNT(*) AS cantidad
				,m.nombre AS mueble
				,d.departamento
				,IF(i.estatus = 2, 'ENTRADA','SALIDA') AS tipo
				,CONCAT(u.nombres,\" \",u.paterno,\" \",u.materno) AS usuario
			FROM muebles m 
			LEFT JOIN inventario_general i ON i.muebles_id = m.muebles_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN usuarios u ON u.usuarios_id = i.usuario_id
			INNER JOIN departamento d ON d.departamento_id = m.departamento_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
      AND i.muebleria_id = _muebleria
      AND i.aceptado = 1
			GROUP BY i.muebles_id
);
SELECT
   IFNULL(MIN(igt.inventarios_id),0) AS inventarios_id
	 ,m.muebles_id
	 ,IFNULL(igt.pedido,0) AS pedido
	 ,IFNULL(igt.estatus,0) AS estatus
	 ,IFNULL(igt.usuario_id,0) AS usuario_id
	 ,IFNULL(igt.procedencia,0) AS procedencia
		,_muebleria AS muebleria_id
		,IFNULL(igt.cantidad,0) AS cantidad
    ,IFNULL(SUM(v.cantidad),0) AS venta
		,m.nombre AS mueble
		,d.name AS departamento
		,IFNULL(igt.tipo,'N/A') tipo
		,IFNULL(igt.usuario,'N/A') usuario 
FROM muebles m
LEFT JOIN inventario_gral_tmp igt ON m.muebles_id = igt.muebles_id
LEFT JOIN ventas v ON m.muebles_id = v.muebles_id AND v.mueblerias_id = _muebleria 
INNER JOIN departamento d ON d.departamento_id = m.departamento_id
GROUP BY m.muebles_id
ORDER BY m.muebles_id ASC;
		WHEN 'GETINVENTARIOCOSTOS' THEN


DROP TABLE IF EXISTS inventario_gral_tmp;
CREATE TABLE IF NOT EXISTS inventario_gral_tmp AS (
SELECT 
				i.inventarios_id
				,i.muebles_id
				,i.codigo_pedido AS pedido
				,i.estatus
				,i.usuario_id
				,i.procedencia
				,i.muebleria_id
        ,COUNT(*) AS cantidad
				,m.nombre AS mueble
				,d.departamento
				,IF(i.estatus = 2, 'ENTRADA','SALIDA') AS tipo
				,CONCAT(u.nombres,\" \",u.paterno,\" \",u.materno) AS usuario
			FROM muebles m 
			LEFT JOIN inventario_general i ON i.muebles_id = m.muebles_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN usuarios u ON u.usuarios_id = i.usuario_id
			INNER JOIN departamento d ON d.departamento_id = m.departamento_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
      AND i.muebleria_id = _muebleria
      AND i.aceptado = 1
			GROUP BY i.muebles_id
);
SELECT
   IFNULL(igt.inventarios_id,0) AS inventarios_id
	 ,m.muebles_id
	 ,IFNULL(igt.pedido,0) AS pedido
	 ,IFNULL(igt.estatus,0) AS estatus
	 ,IFNULL(igt.usuario_id,0) AS usuario_id
	 ,IFNULL(igt.procedencia,0) AS procedencia
		,_muebleria AS muebleria_id
		,IFNULL(igt.cantidad,0) AS cantidad
    ,IFNULL(SUM(v.cantidad),0) AS venta
		,m.nombre AS mueble
    ,m.costo AS precio_costo
		,m.encerado AS precio_venta
		,(m.encerado - m.costo) AS utilidad
		,d.name AS departamento
		,IFNULL(igt.tipo,'N/A') tipo
		,IFNULL(igt.usuario,'N/A') usuario 
FROM muebles m
LEFT JOIN inventario_gral_tmp igt ON m.muebles_id = igt.muebles_id
LEFT JOIN ventas v ON m.muebles_id = v.muebles_id AND v.mueblerias_id = _muebleria 
INNER JOIN departamento d ON d.departamento_id = m.departamento_id
GROUP BY m.muebles_id
ORDER BY m.departamento_id ASC;

	WHEN 'GETACEPTAINVENTARIO' THEN
			SELECT 
				i.inventarios_id
				,i.muebles_id
				,i.codigo_pedido AS pedido
				,i.estatus
				,i.usuario_id
				,i.procedencia
				,i.muebleria_id
				,1 AS cantidad
				,m.nombre AS mueble
				,d.departamento
				,IF(i.estatus = 2, 'ENTRADA','SALIDA') AS tipo
				,CONCAT(u.nombres,\" \",u.paterno,\" \",u.materno) AS usuario
			FROM inventario_general i
			INNER JOIN muebles m ON m.muebles_id = i.muebles_id
			INNER JOIN pedidos pe ON pe.pedidos_id = i.pedido_id
			INNER JOIN usuarios u ON u.usuarios_id = i.usuario_id
			INNER JOIN departamento d ON d.departamento_id = m.departamento_id
      WHERE (i.estatus = 2 OR i.estatus = 3) 
      AND i.muebleria_id = _muebleria
			AND i.aceptado = 0;

	WHEN 'TRASPASO' THEN
    
-- INSERT INTO inventario_movimientos (muebles_id,pedido_id,codigo_pedido,fecha_produccion,descripcion,fecha_entrega,estatus,usuario_id,procedencia
-- 		,muebleria_id,fecha_comienzo,fecha_termino,fecha_traspaso,usuario_acepta,aceptado)	
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
     

     UPDATE inventario_general 
		 SET muebleria_id = _muebleria,
				 procedencia = _procedencia,
				 fecha_traspaso = NOW(),
				 aceptado = 0,
				 usuario_acepta = 0
		 WHERE inventarios_id = _id;

	WHEN 'ACEPTATRASPASO' THEN
     UPDATE inventario_general 
		 SET aceptado = 1,
				 usuario_acepta = _usuario
		 WHERE inventarios_id = _id;
	WHEN 'ELIMINAR' THEN 
		DELETE 
     FROM inventario_general
		WHERE inventarios_id = _id;
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaInventario");
    }
};
