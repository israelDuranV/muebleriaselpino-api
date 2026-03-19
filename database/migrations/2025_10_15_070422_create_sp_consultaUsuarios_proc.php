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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaUsuarios');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaUsuarios`(
	IN `_accion` varchar(15),
	IN `_usuario` VARCHAR(30),
	IN `_secret` VARCHAR(32),
	IN `_nombres` VARCHAR(40),
	IN `_paterno` VARCHAR(20),
	IN `_materno` VARCHAR(20),
	IN `_telefono` INT(10),
	IN `_nacimiento` DATE,
	IN `_sueldo` INT(10),
	IN `_nss` VARCHAR(15),
	IN `_curp` VARCHAR(30),
	IN `_cartilla` VARCHAR(9),
	IN `_licencia` VARCHAR(50),
	IN `_rfc` VARCHAR(16),
	IN `_estudios` VARCHAR(60),
	IN `_fecha_alta` DATE,
	IN `_fotografia` VARCHAR(100),
	IN `_email` VARCHAR(50),
	IN `_muebleria` VARCHAR(50),
	IN `_calle` VARCHAR(100),
	IN `_numero` INT(11),
	IN `_colonia` VARCHAR(100),
	IN `_municipio` VARCHAR(100),
	IN `_estado` VARCHAR(100),
	IN `_cp` INT(5),
	IN `_longitud` DOUBLE,
	IN `_latitud` DOUBLE,
	IN `_perfil` INT(11),
	IN `_idDireccion` INT(11),
	IN `_alias` VARCHAR(50),
	IN `_darkmode` INT(1),
	IN `_sobremi` VARCHAR(500),
	IN `_id` INT(11)
)
BEGIN
    DECLARE id_array_local VARCHAR(1000);
    DECLARE start_pos SMALLINT;
    DECLARE comma_pos SMALLINT;
    DECLARE current_id VARCHAR(1000);
    DECLARE end_loop TINYINT;
		DECLARE usuario_id INT;

DECLARE _direccion INT DEFAULT 0;

	CASE _accion 
    WHEN 'SAVEPERFIL' THEN
		   UPDATE usuarios 
		SET 
				darkmode = _darkmode,
        alias    = _alias,
        email    = _email,
        sobremi  = _sobremi	
		WHERE usuarios_id = _id;
	WHEN 'CHANGEPASSWORD' THEN
		UPDATE usuarios 
		SET 
		secret= _secret	
		WHERE usuario = _usuario;
        
    WHEN 'SAVE' THEN
			INSERT INTO direccion(calle,numero,colonia, municipio, estado, cp, longitud,latitud) 
			VALUES(_calle,_numero,_colonia,_municipio,_estado,_cp,_longitud,_latitud);
			SET _direccion = LAST_INSERT_ID();
    
    INSERT INTO usuarios(usuario
                        ,secret
                        ,nombres
                        ,paterno
                        ,materno
                        ,fecha_nacimiento
                        ,telefono
                        ,sueldo
                        ,nss
                        ,curp
                        ,cartilla
                        ,licencia
                        ,rfc
                        ,estudios
                        ,fecha_alta
                        ,fotografia
                        ,email
                        ,mueblerias
												,estatus
                        ,direccion_id
                        ,roles_id) values(_usuario,_secret
                        ,_nombres,_paterno
                        ,_materno
                        ,_nacimiento
                        ,_telefono
                        ,_sueldo,_nss,_curp
                        ,_cartilla,_licencia
                        ,_rfc,_estudios
                        ,_fecha_alta
                        ,_fotografia
                        ,_email
                        ,_muebleria
												,1
                        ,_direccion
                        ,_perfil);
						

		SET usuario_id = LAST_INSERT_ID();
    SET id_array_local = _muebleria;
    SET start_pos = 1;
    SET comma_pos = locate(',', id_array_local);

    REPEAT
        IF comma_pos > 0 THEN
            SET current_id = substring(id_array_local, start_pos, comma_pos - start_pos);
            SET end_loop = 0;
        ELSE
            SET current_id = substring(id_array_local, start_pos);
            SET end_loop = 1;
        END IF;
							INSERT INTO asignacion_muebleria(muebleria,usuario,estatus) VALUES(current_id,usuario_id,1);
       IF end_loop = 0 THEN
            SET id_array_local = substring(id_array_local, comma_pos + 1);
            SET comma_pos = locate(',', id_array_local);
        END IF;
    UNTIL end_loop = 1

    END REPEAT;

	WHEN 'GET' THEN
     SELECT 
				u.usuarios_id AS id
				,GROUP_CONCAT(ms.nombre) AS mueblerias
				,u.usuario
				,u.nombres
				,u.paterno
				,u.materno
				,u.telefono
				,u.fecha_alta
				,u.fotografia
        ,u.sueldo
				,u.curp
				,u.rfc
				,u.nss
				,u.cartilla
				,u.licencia
				,u.estudios
        ,u.fecha_nacimiento AS nacimiento
				,u.direccion_id
				,d.calle
				,d.numero
				,d.colonia
				,d.municipio
        ,d.estado
				,d.cp
				,d.longitud
				,d.latitud
				,d.referencia
				,u.roles_id
				,r.rol AS perfil
				,u.email
				,u.comentario
         FROM usuarios u
				 LEFT JOIN asignacion_muebleria am ON am.usuario = u.usuarios_id
				 INNER JOIN mueblerias ms ON ms.mueblerias_id = am.muebleria
         INNER JOIN direccion d ON d.direccion_id = u.direccion_id
         INNER JOIN roles r ON r.roles_id = u.roles_id
				 WHERE u.estatus = 1
				 GROUP BY u.usuarios_id
				 ORDER BY r.rol ASC;

	WHEN 'GETUSUARIO' THEN

     SELECT 
				u.usuarios_id AS id
				,GROUP_CONCAT(ms.nombre) AS mueblerias
        ,u.alias
        ,CONCAT(u.nombres,\" \",\" \",u.paterno,\" \",u.materno) AS nombre_usuario
				,u.usuario
				,u.telefono
				,u.fecha_alta
				,u.fotografia
				,u.estudios
        ,u.fecha_nacimiento AS nacimiento
				,u.direccion_id
				,CONCAT(d.calle,\", #\",d.numero,\", \",d.colonia,\", \",d.municipio,\", \",d.estado,\", \",d.cp,\" \") AS usuario_direccion
        ,u.sobremi
				,u.darkmode
				,d.longitud
				,d.latitud
				,d.referencia
				,r.rol AS perfil
				,u.email
				,u.comentario
         FROM usuarios u
				 LEFT JOIN asignacion_muebleria am ON am.usuario = u.usuarios_id
				 INNER JOIN mueblerias ms ON ms.mueblerias_id = am.muebleria
         INNER JOIN direccion d ON d.direccion_id = u.direccion_id
         INNER JOIN roles r ON r.roles_id = u.roles_id
				 WHERE u.estatus = 1
				 AND  u.usuarios_id = _id;

	  WHEN 'UPDATE' THEN

    UPDATE usuarios 
		SET 
        usuario = _usuario,
        nombres = _nombres,
        paterno = _paterno,
        materno = _materno,
        telefono = _telefono,
        fecha_nacimiento = _nacimiento,
        sueldo = _sueldo,
        nss = _nss,
        curp = _curp,
        cartilla = _cartilla,
        licencia = _licencia,
        rfc = _rfc,
        estudios = _estudios,
        fecha_alta = _fecha_alta,
        email = _email,
        mueblerias_id = _muebleria	
		WHERE usuarios_id = _id;

	 IF CHAR_LENGTH(_fotografia) > 0 THEN 

					UPDATE usuarios 
					SET 
					fotografia = _fotografia 
					WHERE usuarios_id = _id;		

   END IF;

   DELETE FROM asignacion_muebleria WHERE usuario = _id;

	 SET id_array_local = _muebleria;
	 SET start_pos = 1;
	 SET comma_pos = locate(',', id_array_local);

		REPEAT
						IF comma_pos > 0 THEN
								SET current_id = substring(id_array_local, start_pos, comma_pos - start_pos);
								SET end_loop = 0;
						ELSE
								SET current_id = substring(id_array_local, start_pos);
								SET end_loop = 1;
						END IF;
									INSERT INTO asignacion_muebleria(muebleria,usuario,estatus) VALUES(current_id,_id,1);
					 IF end_loop = 0 THEN
								SET id_array_local = substring(id_array_local, comma_pos + 1);
								SET comma_pos = locate(',', id_array_local);
						END IF;
				UNTIL end_loop = 1

    END REPEAT;

        
		UPDATE direccion 
			SET   calle = _calle
						,numero = _numero
						,colonia = _colonia
						,municipio = _municipio
						,estado = _estado
						,	cp = _cp
			WHERE direccion_id = _idDireccion;
    
	WHEN 'DELETE' THEN
		UPDATE usuarios 
		SET estatus = 0
    WHERE usuarios_id = _id;
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaUsuarios");
    }
};
