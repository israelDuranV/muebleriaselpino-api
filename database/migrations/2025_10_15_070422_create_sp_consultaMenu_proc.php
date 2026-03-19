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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaMenu');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaMenu`(IN `_accion` varchar(15) ,IN `_varIdUsuario`  int(11),IN `_varIdMenu`  int(11))
BEGIN
	CASE _accion  
			WHEN 'MENU' THEN
				SELECT  
                mu.id
				,mu.item AS item_menu
				,mu.icon AS icon_menu
				,mu.url_menu
			
				FROM usuarios u
					INNER JOIN roles r ON r.roles_id = u.roles_id
					INNER JOIN permisos p ON p.roles_id = r.roles_id
					INNER JOIN modulos m ON m.modulos_id = p.modulos_id
					INNER JOIN menu mu ON mu.id = m.menu_id
				WHERE p.ver = 1 
           AND u.usuarios_id = _varIdUsuario
					 GROUP BY item_menu;

		WHEN 'SUBMENU' THEN
				SELECT 
                m.menu_id
				,m.modulo
				,m.icono
				,m.label
				,m.url
				,m.description
				FROM usuarios u
					INNER JOIN roles r ON r.roles_id = u.roles_id
					INNER JOIN permisos p ON p.roles_id = r.roles_id
					INNER JOIN modulos m ON m.modulos_id = p.modulos_id
					INNER JOIN menu mu ON mu.id = m.menu_id
				WHERE p.ver = 1 
				  AND u.usuarios_id = _varIdUsuario
				  AND mu.Id = _varIdMenu;
    END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaMenu");
    }
};
