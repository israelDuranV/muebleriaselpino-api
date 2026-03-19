<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Filtro extends Model
{
    /**
     * Este modelo no tiene tabla propia, 
     * solo consume el SP sp_consultaFiltros
     */
    
    /**
     * Obtener lista de usuarios activos
     * 
     * @return Collection
     */
    public static function usuarios(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['USUARIOS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de módulos
     * 
     * @return Collection
     */
    public static function modulos(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['MODULOS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de menús
     * 
     * @return Collection
     */
    public static function menus(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['MENUS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de iconos con su clase CSS
     * 
     * @return Collection
     */
    public static function iconos(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['ICONOS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de materiales
     * 
     * @return Collection
     */
    public static function materiales(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['MATERIALES']);
        return collect($resultado);
    }

    /**
     * Obtener lista de terminados
     * 
     * @return Collection
     */
    public static function terminados(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['TERMINADOS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de departamentos
     * 
     * @return Collection
     */
    public static function departamentos(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['DEPARTAMENTOS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de tipos de pago
     * 
     * @return Collection
     */
    public static function tiposPago(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['TIPOPAGO']);
        return collect($resultado);
    }

    /**
     * Obtener lista de perfiles/roles
     * 
     * @return Collection
     */
    public static function perfiles(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['PERFILES']);
        return collect($resultado);
    }

    /**
     * Obtener lista de mueblerías
     * 
     * @return Collection
     */
    public static function mueblerias(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['MUEBLERIAS']);
        return collect($resultado);
    }

    /**
     * Obtener lista de muebles
     * 
     * @return Collection
     */
    public static function muebles(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['MUEBLES']);
        return collect($resultado);
    }

    /**
     * Obtener lista de clientes con nombre completo
     * 
     * @return Collection
     */
    public static function clientes(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['CLIENTES']);
        return collect($resultado);
    }

    /**
     * Obtener lista de direcciones de clientes
     * 
     * @return Collection
     */
    public static function direccionesClientes(): Collection
    {
        $resultado = DB::select('CALL sp_consultaFiltros(?)', ['DIRECCIONCLIENTES']);
        return collect($resultado);
    }

    /**
     * Obtener todos los catálogos en un solo array
     * Útil para inicializar formularios
     * 
     * @param array $catalogos Lista de catálogos a obtener
     * @return array
     */
    public static function varios(array $catalogos): array
    {
        $resultado = [];

        foreach ($catalogos as $catalogo) {
            $metodo = self::mapearCatalogo($catalogo);
            
            if (method_exists(self::class, $metodo)) {
                $resultado[$catalogo] = self::$metodo();
            }
        }

        return $resultado;
    }

    /**
     * Obtener todos los catálogos disponibles
     * 
     * @return array
     */
    public static function todos(): array
    {
        return [
            'usuarios' => self::usuarios(),
            'modulos' => self::modulos(),
            'menus' => self::menus(),
            'iconos' => self::iconos(),
            'materiales' => self::materiales(),
            'terminados' => self::terminados(),
            'departamentos' => self::departamentos(),
            'tipos_pago' => self::tiposPago(),
            'perfiles' => self::perfiles(),
            'mueblerias' => self::mueblerias(),
            'muebles' => self::muebles(),
            'clientes' => self::clientes(),
            'direcciones_clientes' => self::direccionesClientes()
        ];
    }

    /**
     * Convertir para select HTML (id => valor)
     * 
     * @param Collection $items
     * @return array
     */
    public static function paraSelect(Collection $items): array
    {
        return $items->pluck('valor', 'id')->toArray();
    }

    /**
     * Convertir para select2/Vue Select (formato {id, text})
     * 
     * @param Collection $items
     * @return array
     */
    public static function paraSelect2(Collection $items): array
    {
        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->valor
            ];
        })->values()->toArray();
    }

    /**
     * Convertir para React Select (formato {value, label})
     * 
     * @param Collection $items
     * @return array
     */
    public static function paraReactSelect(Collection $items): array
    {
        return $items->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->valor
            ];
        })->values()->toArray();
    }

    /**
     * Buscar un elemento por ID en un catálogo
     * 
     * @param string $catalogo
     * @param int $id
     * @return object|null
     */
    public static function buscarPorId(string $catalogo, int $id)
    {
        $metodo = self::mapearCatalogo($catalogo);
        
        if (!method_exists(self::class, $metodo)) {
            return null;
        }

        $items = self::$metodo();
        return $items->firstWhere('id', $id);
    }

    /**
     * Buscar elementos por texto en un catálogo
     * 
     * @param string $catalogo
     * @param string $busqueda
     * @return Collection
     */
    public static function buscar(string $catalogo, string $busqueda): Collection
    {
        $metodo = self::mapearCatalogo($catalogo);
        
        if (!method_exists(self::class, $metodo)) {
            return collect([]);
        }

        $items = self::$metodo();
        
        return $items->filter(function ($item) use ($busqueda) {
            return stripos($item->valor, $busqueda) !== false;
        });
    }

    /**
     * Mapear nombre de catálogo a método
     * 
     * @param string $catalogo
     * @return string
     */
    private static function mapearCatalogo(string $catalogo): string
    {
        $mapeo = [
            'usuarios' => 'usuarios',
            'modulos' => 'modulos',
            'menus' => 'menus',
            'iconos' => 'iconos',
            'materiales' => 'materiales',
            'terminados' => 'terminados',
            'departamentos' => 'departamentos',
            'tipos_pago' => 'tiposPago',
            'tipospago' => 'tiposPago',
            'perfiles' => 'perfiles',
            'roles' => 'perfiles',
            'mueblerias' => 'mueblerias',
            'muebles' => 'muebles',
            'clientes' => 'clientes',
            'direcciones_clientes' => 'direccionesClientes',
            'direccionesclientes' => 'direccionesClientes'
        ];

        return $mapeo[strtolower($catalogo)] ?? $catalogo;
    }

    /**
     * Cache de catálogos (opcional, para optimizar)
     * Puedes implementar cache con Redis o archivo
     * 
     * @param string $catalogo
     * @param int $minutos
     * @return Collection
     */
    public static function cache(string $catalogo, int $minutos = 60): Collection
    {
        $cacheKey = "filtro_{$catalogo}";
        
        return \Illuminate\Support\Facades\Cache::remember(
            $cacheKey, 
            now()->addMinutes($minutos), 
            function () use ($catalogo) {
                $metodo = self::mapearCatalogo($catalogo);
                return method_exists(self::class, $metodo) 
                    ? self::$metodo() 
                    : collect([]);
            }
        );
    }

    /**
     * Limpiar cache de catálogos
     * 
     * @param string|null $catalogo Si es null, limpia todos
     */
    public static function limpiarCache(?string $catalogo = null): void
    {
        if ($catalogo) {
            \Illuminate\Support\Facades\Cache::forget("filtro_{$catalogo}");
        } else {
            // Limpiar todos los catálogos
            $catalogos = [
                'usuarios', 'modulos', 'menus', 'iconos', 'materiales',
                'terminados', 'departamentos', 'tipos_pago', 'perfiles',
                'mueblerias', 'muebles', 'clientes', 'direcciones_clientes'
            ];
            
            foreach ($catalogos as $cat) {
                \Illuminate\Support\Facades\Cache::forget("filtro_{$cat}");
            }
        }
    }
}