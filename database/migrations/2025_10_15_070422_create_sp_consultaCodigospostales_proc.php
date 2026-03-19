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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaCodigospostales');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaCodigospostales`(IN var_accion VARCHAR(50),IN var_codigo INT(6))
BEGIN
  	CASE var_accion  
		WHEN 'SEARCH' THEN
        	SELECT * 
            FROM codigos_postales 
            WHERE codigoPostal = var_codigo;
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaCodigospostales");
    }
};
