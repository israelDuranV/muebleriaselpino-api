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
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_consultaModulos');
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultaModulos`(IN _accion VARCHAR(20), _menu INT(11),_modulo VARCHAR(20),_icono VARCHAR(30),_label VARCHAR(50),_url VARCHAR(35),_id INT(11))
BEGIN
	CASE _accion  
		WHEN 'MODULOS' THEN
        SELECT mo.modulos_id AS id
				,mu.item
                ,mo.modulo
                ,mo.label
                ,mo.url
                ,mo.icono 
        FROM modulos mo
        INNER JOIN menu mu ON mu.id = mo.menu_id
        ORDER BY mo.modulo;
	WHEN 'MODULOSINSERT' THEN

		INSERT INTO modulos(menu_id,modulo,icono,label,url) 
        VALUES(_menu,_modulo,_icono,_label,_url);

    WHEN 'MODULOSUPDATE' THEN
			UPDATE modulos 
					SET menu_id = _menu,
				modulo = _modulo,
							icono = _icono,
							label = _label,
							url = _url
					WHERE modulos_id = _id; 
    WHEN 'MODULOSDELETE' THEN
		DELETE FROM modulos WHERE modulos_id = _id; 
	END CASE;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_consultaModulos");
    }
};
