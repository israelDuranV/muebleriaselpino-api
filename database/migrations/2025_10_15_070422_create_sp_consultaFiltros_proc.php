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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaFiltros');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaFiltros`(IN _accion VARCHAR(20))
BEGIN
	CASE _accion  
		WHEN 'USUARIOS' THEN
			SELECT usuarios_id AS id,usuario AS valor 
            FROM usuarios
            WHERE estatus = 1;
		WHEN 'MODULOS' THEN
			SELECT modulos_id AS id, modulo AS valor 
            FROM modulos;
		WHEN 'MENUS' THEN
			SELECT Id AS id, item AS valor 
            FROM menu;
	    WHEN 'ICONOS' THEN
			SELECT Id AS id, clase AS icon, descripcion AS valor 
            FROM iconos;
		WHEN 'MATERIALES' THEN
			SELECT materiales_id AS id, material AS valor 
            FROM materiales;
        WHEN 'TERMINADOS' THEN
			SELECT terminado_id AS id, terminado AS valor 
            FROM terminado;
        WHEN 'DEPARTAMENTOS' THEN
			SELECT departamento_id AS id, name AS valor 
            FROM departamentos;
		WHEN 'PERFILES' THEN
			SELECT roles_id AS id, rol AS valor 
            FROM roles;
		WHEN 'MUEBLERIAS' THEN
			SELECT mueblerias_id AS id, nombre AS valor 
            FROM mueblerias;
		WHEN 'MUEBLES' THEN
			SELECT muebles_id AS id, nombre AS valor 
            FROM muebles;
		WHEN 'CLIENTES' THEN
			SELECT clientes_id AS id, CONCAT(nombres,\" \",paterno,\" \",materno) AS valor 
            FROM clientes;
		WHEN 'DIRECCIONCLIENTES' THEN
			SELECT c.clientes_id AS id, 
			CONCAT(d.calle,\" \",d.numero,\" \",d.colonia,\" \",d.municipio,\" \",d.estado,\" \",d.cp) AS valor  
			FROM clientes c
			INNER JOIN direccion d ON c.direccion_id = d.direccion_id;	
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaFiltros");
    }
};
