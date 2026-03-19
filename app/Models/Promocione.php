<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Promocione
 * 
 * @property int $reditos_id
 * @property int|null $contado
 * @property int|null $contado_barn
 * @property int|null $mes
 * @property int|null $tres_meses
 * @property int|null $seis_meses
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_final
 * @property string|null $temporada
 *
 * @package App\Models
 */
class Promocione extends Model
{
	protected $table = 'promociones';
	protected $primaryKey = 'promociones_id';
	public $timestamps = false;

	protected $fillable = [
        'contado',
        'mes',
        'tres_meses',
        'seis_meses',
        'fecha_inicio',
        'fecha_final',
        'temporada'
    ];

    protected $casts = [
        'contado' => 'integer',
        'mes' => 'integer',
        'tres_meses' => 'integer',
        'seis_meses' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_final' => 'date'
    ];
	public static function guardarPromocion(array $datos): bool
    {
        DB::statement('CALL sp_consultaPromociones(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'SAVE',
            $datos['contado'] ?? 0,
            $datos['mes'] ?? 0,
            $datos['tres_meses'] ?? 0,
            $datos['seis_meses'] ?? 0,
            $datos['fecha_inicio'],
            $datos['fecha_final'],
            $datos['temporada'] ?? null,
            null // id
        ]);

        return true;
    }

    /**
     * Actualizar una promoción existente
     * 
     * @param int $id
     * @param array $datos
     * @return bool
     */
    public static function actualizarPromocion(int $id, array $datos): bool
    {
        DB::statement('CALL sp_consultaPromociones(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'UPDATE',
            $datos['contado'] ?? 0,
            $datos['mes'] ?? 0,
            $datos['tres_meses'] ?? 0,
            $datos['seis_meses'] ?? 0,
            $datos['fecha_inicio'],
            $datos['fecha_final'],
            $datos['temporada'] ?? null,
            $id
        ]);

        return true;
    }

    /**
     * Eliminar una promoción
     * 
     * @param int $id
     * @return bool
     */
    public static function eliminarPromocion(int $id): bool
    {
        DB::statement('CALL sp_consultaPromociones(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'DELETE',
            null, null, null, null, null, null, null,
            $id
        ]);

        return true;
    }

    /**
     * Obtener la promoción activa actual
     * Si no hay promoción activa, retorna la primera del sistema
     * 
     * @return object|null
     */
    public static function obtenerPromocionActiva()
    {
        $resultado = DB::select('CALL sp_consultaPromociones(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GET',
            null, null, null, null, null, null, null, null
        ]);

        return $resultado[0] ?? null;
    }

    /**
     * Obtener todas las promociones
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function obtenerTodas()
    {
        $resultado = DB::select('CALL sp_consultaPromociones(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'ALL',
            null, null, null, null, null, null, null, null
        ]);

        return collect($resultado);
    }

    /**
     * Scope para promociones activas (usando Eloquent)
     */
    public function scopeActivas($query)
    {
        return $query->where('fecha_inicio', '<=', now())
                     ->where('fecha_final', '>=', now());
    }

    /**
     * Scope para promociones futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha_inicio', '>', now());
    }

    /**
     * Scope para promociones vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->where('fecha_final', '<', now());
    }

    /**
     * Scope por temporada
     */
    public function scopePorTemporada($query, $temporada)
    {
        return $query->where('temporada', $temporada);
    }

    /**
     * Verificar si la promoción está activa
     * 
     * @return bool
     */
    public function estaActiva(): bool
    {
        $ahora = now();
        return $this->fecha_inicio <= $ahora && $this->fecha_final >= $ahora;
    }

    /**
     * Verificar si la promoción es futura
     * 
     * @return bool
     */
    public function esFutura(): bool
    {
        return $this->fecha_inicio > now();
    }

    /**
     * Verificar si la promoción está vencida
     * 
     * @return bool
     */
    public function estaVencida(): bool
    {
        return $this->fecha_final < now();
    }

    /**
     * Obtener el estado de la promoción
     * 
     * @return string
     */
    public function getEstadoAttribute(): string
    {
        if ($this->estaActiva()) {
            return 'activa';
        } elseif ($this->esFutura()) {
            return 'programada';
        } else {
            return 'vencida';
        }
    }

    /**
     * Obtener días restantes de la promoción
     * 
     * @return int|null
     */
    public function getDiasRestantesAttribute(): ?int
    {
        if ($this->estaVencida()) {
            return 0;
        }

        return now()->diffInDays($this->fecha_final, false);
    }

    /**
     * Calcular descuento según tipo de pago
     * 
     * @param string $tipoPago
     * @param float $monto
     * @return array
     */
    public function calcularDescuento(string $tipoPago, float $monto): array
    {
        $descuentoPorcentaje = 0;

        switch (strtolower($tipoPago)) {
            case 'contado':
                $descuentoPorcentaje = $this->contado;
                break;
            case '1mes':
            case 'mes':
                $descuentoPorcentaje = $this->mes;
                break;
            case '3meses':
            case 'tres_meses':
                $descuentoPorcentaje = $this->tres_meses;
                break;
            case '6meses':
            case 'seis_meses':
                $descuentoPorcentaje = $this->seis_meses;
                break;
        }

        $descuentoMonto = ($monto * $descuentoPorcentaje) / 100;
        $montoFinal = $monto - $descuentoMonto;

        return [
            'tipo_pago' => $tipoPago,
            'monto_original' => $monto,
            'descuento_porcentaje' => $descuentoPorcentaje,
            'descuento_monto' => $descuentoMonto,
            'monto_final' => $montoFinal,
            'ahorro' => $descuentoMonto
        ];
    }

    /**
     * Obtener todos los descuentos disponibles para un monto
     * 
     * @param float $monto
     * @return array
     */
    public function obtenerTodosLosDescuentos(float $monto): array
    {
        return [
            'contado' => $this->calcularDescuento('contado', $monto),
            'mes' => $this->calcularDescuento('mes', $monto),
            'tres_meses' => $this->calcularDescuento('tres_meses', $monto),
            'seis_meses' => $this->calcularDescuento('seis_meses', $monto)
        ];
    }

    /**
     * Obtener mejor descuento disponible
     * 
     * @param float $monto
     * @return array
     */
    public function obtenerMejorDescuento(float $monto): array
    {
        $descuentos = [
            $this->calcularDescuento('contado', $monto),
            $this->calcularDescuento('mes', $monto),
            $this->calcularDescuento('tres_meses', $monto),
            $this->calcularDescuento('seis_meses', $monto)
        ];

        return collect($descuentos)->sortByDesc('ahorro')->first();
    }

    /**
     * Obtener duración de la promoción en días
     * 
     * @return int
     */
    public function getDuracionDiasAttribute(): int
    {
        return $this->fecha_inicio->diffInDays($this->fecha_final);
    }

    /**
     * Verificar si la promoción se solapa con otra
     * 
     * @param Carbon $inicio
     * @param Carbon $final
     * @return bool
     */
    public static function haySolapamiento($inicio, $final, $excluirId = null): bool
    {
        $query = self::where(function($q) use ($inicio, $final) {
            $q->whereBetween('fecha_inicio', [$inicio, $final])
              ->orWhereBetween('fecha_final', [$inicio, $final])
              ->orWhere(function($q2) use ($inicio, $final) {
                  $q2->where('fecha_inicio', '<=', $inicio)
                     ->where('fecha_final', '>=', $final);
              });
        });

        if ($excluirId) {
            $query->where('promociones_id', '!=', $excluirId);
        }

        return $query->exists();
    }



    /**
     * Formatear promoción para mostrar
     * 
     * @return array
     */
    public function formatearParaMostrar(): array
    {
        return [
            'id' => $this->promociones_id,
            'temporada' => $this->temporada,
            'estado' => $this->estado,
            'vigencia' => [
                'inicio' => $this->fecha_inicio->format('d/m/Y'),
                'final' => $this->fecha_final->format('d/m/Y'),
                'dias_restantes' => $this->dias_restantes
            ],
            'descuentos' => [
                'contado' => $this->contado . '%',
                'mes' => $this->mes . '%',
                'tres_meses' => $this->tres_meses . '%',
                'seis_meses' => $this->seis_meses . '%'
            ],
            'activa' => $this->estaActiva()
        ];
    }

    /**
     * Crear promoción automática por temporada
     * 
     * @param string $temporada
     * @param array $descuentos
     * @return bool
     */
    public static function crearPromocionTemporada(string $temporada, array $descuentos): bool
    {
        // Fechas sugeridas según temporada
        $fechas = self::getFechasTemporada($temporada);

        return self::guardarPromocion([
            'contado' => $descuentos['contado'] ?? 0,
            'mes' => $descuentos['mes'] ?? 0,
            'tres_meses' => $descuentos['tres_meses'] ?? 0,
            'seis_meses' => $descuentos['seis_meses'] ?? 0,
            'fecha_inicio' => $fechas['inicio'],
            'fecha_final' => $fechas['final'],
            'temporada' => $temporada
        ]);
    }

    /**
     * Obtener fechas sugeridas por temporada
     * 
     * @param string $temporada
     * @return array
     */

}
