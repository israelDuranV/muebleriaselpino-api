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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaPerfiles');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaPerfiles`(IN _accion VARCHAR(20),_perfil VARCHAR(50),_descripcion VARCHAR(30),_id INT(11))
BEGIN
	CASE _accion  
		WHEN 'GET' THEN
        
        SELECT p.roles_id AS id,
		p.rol AS perfil,
        p.descripcion 
        FROM roles p
        ORDER BY p.roles_id;
        
	WHEN 'INSERT' THEN

		INSERT INTO roles(rol,descripcion) 
        VALUES(_perfil,_descripcion);

    WHEN 'UPDATE' THEN
	
		UPDATE roles 
		SET rol =_perfil,
		  	descripcion = _descripcion
		WHERE roles_id = _id; 
    
    WHEN 'DELETE' THEN
		DELETE FROM roles WHERE roles_id = _id; 
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaPerfiles");
    }
};
