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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaClientes');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaClientes`(IN `_accion` varchar(15), 
 IN `_nombres` VARCHAR(40),
 IN `_paterno` VARCHAR(20),
IN `_materno` VARCHAR(20),
  IN `_telefono` VARCHAR(30), IN `_celular` VARCHAR(30),
                                                           IN `_fecha_alta` DATE,
                                                           IN `_fecha_nacimiento` DATE,
                                                           IN `_fotografia` VARCHAR(100),
                                                           IN `_email` VARCHAR(50),
                                                           IN `_muebleria` INT(11),
                                                           IN `_observaciones` VARCHAR(500),
                                                           IN `_calificacion` INT(3),
                                                           IN `_acepta` INT(1),
                                                           IN `_calle` VARCHAR(50),
                                                           IN `_numero` INT(11),
                                                           IN `_colonia` VARCHAR(100),
                                                           IN `_municipio` VARCHAR(100),
                                                           IN `_estado` VARCHAR(100),
                                                           IN `_cp` INT(5),
                                                           IN `_longitud` DOUBLE,
                                                           IN `_latitud` DOUBLE,
                                                           IN `_direccion_id` INT(11),
                                                           IN `_com_domicilio` VARCHAR(120),
                                                           IN `_com_ine` VARCHAR(120), 
IN `_com_croquis` VARCHAR(120),                                                         IN `_id` INT(11))
BEGIN
  DECLARE _direccion INT DEFAULT 0;
	CASE _accion 
    WHEN 'SAVE' THEN
		INSERT INTO direccion(calle,numero,colonia, municipio, estado, cp, longitud,latitud) 
		VALUES(_calle,_numero,_colonia,_municipio,_estado,_cp,_longitud,_latitud);
		SET _direccion = LAST_INSERT_ID();
        
		INSERT INTO clientes(nombres,paterno,materno,tel_local,celular,observaciones,mueblerias_id
        ,fecha_nacimiento,fecha_alta,email,direccion_id,calificacion,acepta_promociones,comprobante_domicilio,comprobante_ine,comprobante_croquis)
        VALUES(_nombres,_paterno,_materno,_telefono,_celular,_observaciones,_muebleria,
        _fecha_nacimiento,_fecha_alta,_email,_direccion,_calificacion,_acepta,_com_domicilio,_com_ine,_com_croquis);

 WHEN 'GETCLIENT' THEN

         SELECT c.clientes_id AS id
         ,c.nombres
         ,c.paterno
         ,c.materno
         ,c.tel_local
         ,c.celular
         ,c.fecha_alta
         ,c.fecha_nacimiento
         ,c.fotografia
		,m.nombre AS muebleria
        ,m.mueblerias_id
        ,d.direccion_id
		 ,d.calle
         ,d.numero
         ,d.colonia
         ,d.municipio
         ,d.estado
         ,d.cp
         ,d.longitud
         ,d.latitud
         ,d.referencia
		 ,c.email
         ,c.observaciones
         ,c.calificacion
         ,c.acepta_promociones AS acepta
		 FROM clientes c
           INNER JOIN mueblerias m ON m.mueblerias_id = c.mueblerias_id
           INNER JOIN direccion d ON d.direccion_id = c.direccion_id
			WHERE c.clientes_id = _id;

	WHEN 'GET' THEN
         SELECT c.clientes_id AS id
         ,c.comprobante_domicilio AS dom
				 ,c.comprobante_ine AS ine
				 ,c.comprobante_croquis AS croquis
         ,c.nombres
         ,c.paterno
         ,c.materno
         ,c.tel_local
         ,c.celular
         ,c.fecha_alta
         ,c.fecha_nacimiento
         ,c.fotografia
		     ,m.nombre AS muebleria
        ,m.mueblerias_id
        ,d.direccion_id
		     ,d.calle
         ,d.numero
         ,d.colonia
         ,d.municipio
         ,d.estado
         ,d.cp
         ,d.longitud
         ,d.latitud
         ,d.referencia
		 ,c.email
         ,c.observaciones
         ,c.calificacion
         ,c.acepta_promociones AS acepta
		 FROM clientes c
           INNER JOIN mueblerias m ON m.mueblerias_id = c.mueblerias_id
           INNER JOIN direccion d ON d.direccion_id = c.direccion_id;
    WHEN 'EDIT' THEN
	    UPDATE direccion
        SET calle=_calle,
			numero=_numero,
            colonia=_colonia, 
            municipio=_municipio, 
            estado=_estado, 
            cp=_cp 
		WHERE direccion_id=_direccion_id;
        
		UPDATE clientes 
        SET nombres=_nombres
			,paterno=_paterno
            ,materno=_materno
            ,tel_local=_telefono
            ,celular=_celular
            ,observaciones=_observaciones
			,fecha_nacimiento=_fecha_nacimiento
            ,fecha_alta=_fecha_alta
            ,email=_email
            ,calificacion=_calificacion
            ,acepta_promociones=_acepta
            WHERE clientes_id=_id;
	WHEN 'DELETE' THEN
		DELETE FROM clientes WHERE clientes_id = _id;
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaClientes");
    }
};
