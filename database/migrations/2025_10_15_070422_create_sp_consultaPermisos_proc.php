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
		DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaPermisos');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaPermisos`(IN `_accion` varchar(15),
 IN `_hash`  varchar(32),  IN `_perfil`  INT(11),   IN `_modulo` INT(11),
  IN `_ver` INT(11), 
                                                              IN `_editar` INT(11), 
                                                              IN `_insertar` INT(11),
                                                              IN `_eliminar` INT(11),
                                                              IN `_id` INT(11))
BEGIN
		CASE _accion  
			WHEN 'AUTH' THEN
				SELECT  
        u.usuarios_id
        ,IFNULL(u.alias,CONCAT(u.nombres,\" \",\" \",u.paterno,\" \",u.materno)) AS usuario_conectado
        ,CONCAT(u.nombres,\" \",\" \",u.paterno,\" \",u.materno) AS nombre_usuario
				,u.usuario
				,u.nombres
        ,u.materno
        ,u.paterno
        ,u.email
        ,r.rol
        ,u.fotografia
        ,u.darkmode
				FROM usuarios u
				INNER JOIN roles r ON r.roles_id = u.roles_id
				WHERE  u.secret = _hash
				AND u.estatus = 1;
     
     WHEN 'MENU' THEN
        SELECT  mu.item AS item_menu
				,ic.clase AS icon_menu
				FROM usuarios u
				-- INNER JOIN fotografia f ON f.fotografia_id = u.fotografia_id
				INNER JOIN roles r ON r.roles_id = u.roles_id
					INNER JOIN permisos p ON p.roles_id = r.roles_id
					INNER JOIN modulos m ON m.modulos_id = p.modulos_id
	-- 				INNER JOIN iconos ico ON ico.id = m.icono
					INNER JOIN menu mu ON mu.id = m.menu_id
					INNER JOIN iconos ic ON ic.id = mu.icon
				WHERE u.secret = _hash GROUP BY item_menu;
                
		WHEN 'TODOSPERMISOS' THEN
			SELECT  
			p.permisos_id as id
			,r.rol AS perfil
			,m.modulo
			,p.editar
			,p.ver
			,p.insertar
			,p.eliminar 
			FROM  roles r 
			INNER JOIN permisos p ON p.roles_id = r.roles_id
			INNER JOIN modulos m ON m.modulos_id = p.modulos_id
      ORDER BY perfil ASC;

	    WHEN 'PORROL' THEN

				SELECT  u.usuario,r.rol,mu.item as item_menu,ic.clase as icon_menu, m.modulo,ico.clase icon_modulo, m.label, m.url,p.editar,p.ver,p.insertar, p.eliminar 
				FROM usuarios u
				INNER JOIN roles r ON r.roles_id = u.roles_id
				INNER JOIN permisos p ON p.roles_id = r.roles_id
				INNER JOIN modulos m ON m.modulos_id = p.modulos_id
				INNER JOIN iconos ico ON ico.id = m.icono
				INNER JOIN menu mu ON mu.id = m.menu_id
				INNER JOIN iconos ic ON ic.id = mu.icon
				WHERE r.roles_id = _rol
				AND u.estatus=1;

	    WHEN 'SAVE' THEN
			SET @existe = IFNULL((SELECT permisos_id FROM permisos WHERE roles_id = _perfil AND modulos_id = _modulo LIMIT 1),0);

			IF @existe = 0 THEN

				INSERT INTO permisos (roles_id,modulos_id,ver,editar,insertar,eliminar) 
        VALUES(_perfil,_modulo,_ver,_editar,_insertar,_eliminar);


			END IF;
		
		WHEN 'UPDATE' THEN
				UPDATE permisos 
                SET roles_id = _perfil,
					modulos_id = _modulo,
                    ver = _ver,
                    editar = _editar,
                    insertar = _insertar,
                    eliminar = _eliminar
                    WHERE permisos_id = _id;
    WHEN 'PORUSUARIO' THEN
				SELECT 
         m.menu_id
				,m.modulos_id
				,m.modulo
        ,p.editar
        ,p.eliminar
        ,p.insertar
        ,p.ver
				
				FROM usuarios u
					INNER JOIN roles r ON r.roles_id = u.roles_id
					INNER JOIN permisos p ON p.roles_id = r.roles_id
					INNER JOIN modulos m ON m.modulos_id = p.modulos_id
					INNER JOIN menu mu ON mu.id = m.menu_id
				WHERE p.ver = 1 
				AND u.usuarios_id = _id
				AND m.modulos_id = _modulo;

        
        WHEN 'DELETE' THEN
				DELETE FROM permisos WHERE permisos_id = _id;
		END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaPermisos");
    }
};
