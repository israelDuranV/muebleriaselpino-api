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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaMueblerias');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaMueblerias`(IN `_accion` VARCHAR(50)
,`_nombre` VARCHAR(100),`_usuario` INT(11),`_estatus` INT(1)                 ,`_calle` VARCHAR(50)
                                                                ,`_numero` INT(10)
                                                                ,`_colonia` VARCHAR(50)
                                                                ,`_municipio` VARCHAR(50)
                                                                ,`_estado` VARCHAR(50)
                                                                ,`_cp` INT(5)
                                                                ,`_longitud` DOUBLE
                                                                ,`_latitud` DOUBLE
                                                                ,`_tipo` VARCHAR(15)
, `_referencia` VARCHAR(200) , `_direccion_id` INT(11), `_id` INT(11))
BEGIN
DECLARE _direccion INT DEFAULT 0;
	CASE _accion 
        WHEN 'INSERT' THEN
			INSERT 
				INTO direccion(
					calle
					,numero
					,colonia
					,municipio
					,estado
					,cp
					,longitud
					,latitud
					,referencia) 
				VALUES(
					_calle
					,_numero
					,_colonia
					,_municipio
					,_estado
					,_cp
					,_longitud
					,_latitud
					,_referencia
				);
				SET _direccion = LAST_INSERT_ID();
           
				INSERT INTO mueblerias(
					nombre
					,tipo
					,direccion_id
					,estatus
					)
          VALUES(
				_nombre
				,_tipo
				,_direccion
				,1
				);
    WHEN 'EDIT' THEN
       			UPDATE direccion
						SET 
							calle=_calle
						  ,numero=_numero
							,colonia=_colonia
							,municipio=_municipio
							,estado=_estado
							,cp=_cp
							,longitud=_longitud
							,latitud=_latitud
							,referencia=_referencia
					  WHERE direccion_id =_direccion_id;
 
            UPDATE mueblerias
						SET
							nombre=_nombre
							,tipo=_tipo
						 
						WHERE mueblerias_id = _id;
		WHEN 'GET' THEN
			SELECT * FROM mueblerias m 
      INNER JOIN direccion d ON d.direccion_id = m.direccion_id
			WHERE m.estatus = 1;
		WHEN 'DELETE' THEN
			UPDATE mueblerias 
			SET estatus = 0
			WHERE mueblerias_id = _id;
		WHEN 'ASIGNAR' THEN
           INSERT INTO asignacion_muebleria(muebleria,usuario,estatus) VALUES(_id,_usuario,_estatus);
		WHEN 'ASIGNADAS' THEN

			SELECT m.mueblerias_id as id,m.nombre as muebleria 
			FROM asignacion_muebleria am
			INNER JOIN mueblerias m ON am.muebleria = m.mueblerias_id
			WHERE am.usuario = _usuario
			AND m.estatus=1
			AND am.estatus=1
      ORDER BY m.mueblerias_id DESC;

		WHEN 'ASIGNADASID' THEN

			SELECT m.mueblerias_id as id,m.nombre as muebleria 
      FROM asignacion_muebleria am
			INNER JOIN mueblerias m ON am.muebleria = m.mueblerias_id
			WHERE am.usuario = _usuario
			AND am.estatus=1
      AND m.mueblerias_id=_id;

	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaMueblerias");
    }
};
