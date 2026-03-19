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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaAsistencias');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaAsistencias`(IN `_accion` VARCHAR(50), IN `_empleado` int(11), IN `_muebleria` INT(11),IN `_latitud` VARCHAR(100), IN `_longitud` VARCHAR(100),IN `_tipo` VARCHAR(30), IN `_inicio` DATE, IN `_final` DATE)
BEGIN
SET @existe = 0;
	CASE _accion  
		WHEN 'SAVE' THEN

 			SET @existe_entrada = IFNULL((SELECT asistencias_id 
														FROM asistencias 
														WHERE dia = DATE(NOW()) 
														AND tipo = 'ENTRADA' LIMIT 1),0);
 			SET @existe_salida  = IFNULL((SELECT asistencias_id 
														FROM asistencias 
														WHERE dia = DATE(NOW()) 
														AND tipo = 'SALIDA' LIMIT 1),0);

		IF (@existe_entrada = 0 AND @existe_salida = 0)THEN
         
			INSERT INTO asistencias(empleado,muebleria,latitud,longitud,tipo,dia,fecha) 
			VALUES(_empleado,_muebleria,_latitud,_longitud,\"ENTRADA\",NOW(),NOW());

		END IF;
		IF (@existe_salida = 0 AND  @existe_entrada > 0) THEN 

    	INSERT INTO asistencias(empleado,muebleria,latitud,longitud,tipo,dia,fecha) 
			VALUES(_empleado,_muebleria,_latitud,_longitud,\"SALIDA\",NOW(),NOW());

		END IF;
		WHEN 'GET' THEN
				SELECT 
				a.asistencias_id AS id
				,CONCAT(u.paterno,' ', u.materno,' ',u.nombres) AS nombre_completo
				,m.mueblerias_id
				,m.nombre
				,d.latitud AS latitud_sucursal
				,d.longitud AS longitud_sucursal
				,a.asistencias_id
				,a.tipo
				,DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha 
				,DATE_FORMAT(a.fecha,'%H:%i:%s') AS hora
				,a.latitud AS latitud_asistencia
				,a.longitud AS longitud_asistencia
				,DISTANCE_BETWEEN(d.latitud, d.longitud,a.latitud, a.longitud)as distancia 

				FROM asistencias a  
				INNER JOIN mueblerias m ON m.mueblerias_id = a.muebleria
				INNER JOIN direccion d ON m.direccion_id = d.direccion_id
				INNER JOIN usuarios u ON u.usuarios_id = a.empleado;
				-- SELECT * FROM asistencias ORDER BY nombre_completo ASC;
		WHEN 'GETFORDATE' THEN
		SELECT 
				a.asistencias_id AS id
				,CONCAT(u.paterno,' ', u.materno,' ',u.nombres) AS nombre_completo
				,m.mueblerias_id
				,m.nombre
				,d.latitud AS latitud_sucursal
				,d.longitud AS longitud_sucursal
				,a.asistencias_id
				,a.tipo
				,DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha 
				,DATE_FORMAT(a.fecha,'%H:%i:%s') AS hora
				,a.latitud AS latitud_asistencia
				,a.longitud AS longitud_asistencia
				,DISTANCE_BETWEEN(d.latitud, d.longitud,a.latitud, a.longitud)as distancia 

				FROM asistencias a  
				INNER JOIN mueblerias m ON m.mueblerias_id = a.muebleria
				INNER JOIN direccion d ON m.direccion_id = d.direccion_id
				INNER JOIN usuarios u ON u.usuarios_id = a.empleado
        WHERE a.fecha BETWEEN _inicio AND _final
        ORDER BY a.fecha DESC;
	WHEN 'ASISTENCIA' THEN

				SELECT 
				a.asistencias_id
				, a.tipo
				, a.fecha
				, a.dia
				, a.latitud
				, a.longitud
				, a.empleado
				,CONCAT(u.nombres,\" \",\" \",u.paterno,\" \",u.materno) AS nombre_usuario
				, a.muebleria 
        FROM asistencias a
				INNER JOIN usuarios u ON u.usuarios_id = a.empleado
				WHERE 
				empleado = _empleado
				AND (tipo = 'ENTRADA' OR tipo='SALIDA')
				AND muebleria = _muebleria
				AND	dia = DATE(NOW())
				LIMIT 2;

	 END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaAsistencias");
    }
};
