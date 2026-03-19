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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_restaurarContrasenia');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_restaurarContrasenia`(IN _accion VARCHAR(30),
																  IN _dato VARCHAR(100))
BEGIN
	CASE _accion  
	 WHEN 'EMAIL' THEN
		SELECT COUNT(*) AS total 
        FROM usuarios 
        WHERE email= _dato;
	   
     WHEN 'HASH' THEN
		SELECT COUNT(*) AS total 
        FROM forgot_password 
        WHERE hash = _dato;
	 WHEN 'FORGOT' THEN
		INSERT INTO forgot_password(hash,fecha) 
        VALUES(_dato,NOW());
	 END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_restaurarContrasenia");
    }
};
