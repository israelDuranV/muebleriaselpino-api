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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaMuebles');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaMuebles`(IN `_accion` VARCHAR(100), IN `_terminado` INT(11),IN `_stock` int (5),IN `_sincera` int(12),IN `_observacion`  VARCHAR(1000),`_nombre` VARCHAR(150),`_material` int(11),`_fotografia` VARCHAR(2000),`_encerado` int(12),`_departamento` int(11),`_costo` int(12),`_barniz` int(12),`_id` int(11))
BEGIN
  DECLARE _actualizar INT DEFAULT 0;
CASE _accion  
WHEN 'GETDEPARTAMENTO' THEN

  SELECT
		m.muebles_id AS id
		,te.terminado
		,m.stock
		,m.sincera
		,m.observacion
		,m.nombre
		,mt.material
		,COALESCE((
        SELECT GROUP_CONCAT(url SEPARATOR ', ')
        FROM mueble_fotos mf
        WHERE mf.mueble_id = m.muebles_id
    ), '') AS fotografia
		,m.encerado
		,de.name AS muebleria
		,m.costo
		,m.barniz
	FROM muebles m
  INNER JOIN materiales mt ON mt.materiales_id = m.materiales_id
	INNER JOIN terminado te ON te.terminado_id = m.terminado_id
	INNER JOIN departamentos de ON de.departamento_id = m.departamento_id
	WHERE m.departamento_id = _departamento;
	
WHEN 'GETMUEBLE' THEN
	SELECT
	m.muebles_id AS id
	,te.terminado
	,m.stock
	,m.sincera
	,m.observacion
	,m.nombre
	,mt.material
	, COALESCE((
        SELECT GROUP_CONCAT(url SEPARATOR ', ')
        FROM mueble_fotos mf
        WHERE mf.mueble_id = m.muebles_id
    ), '') AS fotografia
	,m.encerado
  ,de.descripcion
	,de.name AS departamento
	,m.costo
	,m.barniz
	FROM muebles m
	INNER JOIN materiales mt ON m.materiales_id = m.materiales_id
	INNER JOIN terminado te ON te.terminado_id = m.terminado_id
	INNER JOIN departamentos de ON de.departamento_id = m.departamento_id
	WHERE m.muebles_id = _id;
               
WHEN 'GETMUEBLES' THEN

SELECT  m.muebles_id AS id
,te.terminado
,m.sincera
,m.nombre
,mt.material
, COALESCE((
        SELECT GROUP_CONCAT(url SEPARATOR ', ')
        FROM mueble_fotos mf
        WHERE mf.muebles_id = m.muebles_id
    ), '') AS fotografia
, m.encerado
,de.name AS departamento
,m.costo
,m.barniz
FROM muebles m
INNER JOIN materiales mt ON m.materiales_id = mt.materiales_id
INNER JOIN departamentos de ON m.departamento_id = de.departamento_id
INNER JOIN terminado te ON m.terminado_id = te.terminado_id;

WHEN 'INSERTMUEBLES' THEN
INSERT INTO muebles(
terminado_id
,stock
,sincera
,observacion
,nombre
,materiales_id
,fotografia
,encerado
,departamento_id
,costo
,barniz)
VALUES(_terminado
  ,_stock
                ,_sincera
                ,_observacion
                ,_nombre
                ,_material
                ,_fotografia
                ,_encerado
                ,_departamento
                ,_costo
                ,_barniz
                );
WHEN 'UPDATE' THEN
		IF _terminado > 0 THEN
			UPDATE muebles
			SET terminado_id =_terminado
			WHERE muebles_id = _id;
		END IF;

		IF _stock > 0 THEN
			UPDATE muebles
			SET stock=_stock
			WHERE muebles_id =_id;    
		END IF;
		IF _sincera > 0 THEN

      UPDATE muebles
			SET sincera = _sincera
			WHERE muebles_id = _id;
 
		END IF;
		IF _observacion != \"\" THEN
			
			UPDATE muebles
			SET observacion = _observacion
			WHERE muebles_id = _id;
    END IF;

IF _nombre != \"\" THEN
   
UPDATE muebles
SET nombre = _nombre
        WHERE muebles_id =_id;

END IF;
IF _material > 0 THEN
UPDATE muebles
SET materiales_id = _material
WHERE muebles_id =_id;
END IF;
IF _fotografia != \"\" THEN
	UPDATE muebles 
	SET fotografia=IF(fotografia='',_fotografia, CONCAT(fotografia, ',',_fotografia)) 
	WHERE muebles_id=_id;
/*
   IF(SELECT fotografia FROM muebles WHERE muebles_id = _id) = NULL THEN
			SET _actualizar = 1; 
	 END IF;
   IF( _actualizar > 0) THEN
		UPDATE muebles
				SET fotografia = _fotografia
				WHERE muebles_id =_id;
		ELSE
				UPDATE muebles
				SET fotografia = CONCAT(fotografia,\",\",_fotografia)
				WHERE muebles_id =_id;
		END IF;
*/
END IF;
IF _encerado > 0 THEN
	UPDATE muebles
	SET encerado = _encerado
	WHERE muebles_id =_id;
END IF;
IF _departamento > 0 THEN
	UPDATE muebles
	SET departamento_id = _departamento
  WHERE muebles_id = _id;
  END IF;
IF _costo > 0 THEN
   UPDATE muebles
	 SET costo =_costo
   WHERE muebles_id =_id;
END IF;
IF _barniz > 0 THEN

   UPDATE muebles
		SET barniz = _barniz
   WHERE muebles_id =_id;
END IF;
IF _encerado > 0 THEN
		UPDATE muebles
		SET encerado = _encerado
		WHERE muebles_id = _id;
END IF;

WHEN 'DELETE' THEN

	DELETE FROM muebles WHERE muebles_id =_id;  

WHEN 'DELETEFOTO' THEN
	
	UPDATE muebles SET fotografia = REPLACE ( fotografia, _fotografia, '' );  
  
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaMuebles");
    }
};
