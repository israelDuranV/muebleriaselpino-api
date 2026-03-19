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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaPromociones');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaPromociones`(IN `_accion` varchar (20), IN `_contado` INT(8),IN `_mes` INT(8),IN `_tresmeses` INT(8), IN `_seismeses` INT(8),IN `_inicio` DATE,IN `_final` DATE,IN `_temporada` VARCHAR(15), IN `_id` INT(11))
BEGIN
DECLARE Codigo FLOAT;
CASE _accion 
WHEN 'SAVE' THEN 
		INSERT INTO 
		promociones(
			contado
			,mes
			,tres_meses
			,seis_meses
			,fecha_inicio
			,fecha_final
			,temporada
		)	
		VALUES(
			_contado
			,_mes
			,_tresmeses
			,_seismeses
			,_inicio
			,_final
			,_temporada
		);
WHEN 'UPDATE' THEN 
		UPDATE promociones 
		SET 
			contado = _contado
			,mes = _mes
			,tres_meses = _tresmeses
			,seis_meses=seis_meses
			,fecha_inicio=_inicio
			,fecha_final=_final
			,temporada = _temporada 
			WHERE promociones_id = _id;
WHEN 'DELETE' THEN 
		DELETE FROM promociones WHERE promociones_id = _id;
 WHEN 'GET' THEN 
		
  SET Codigo = (SELECT COUNT(*) FROM promociones WHERE fecha_inicio <= NOW() AND fecha_final >=NOW());

IF (Codigo = 0) THEN 

		SELECT * FROM promociones WHERE 1 ORDER BY _id ASC LIMIT 1;

ELSE 

		SELECT * 
		FROM promociones 
		WHERE fecha_inicio <= NOW() 
		AND fecha_final >=NOW()
		LIMIT 1;

END IF;
WHEN 'ALL' THEN 
		SELECT * 	FROM promociones;

END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaPromociones");
    }
};
