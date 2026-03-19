<?php

namespace App\Http\Controllers;

use App\Models\Promocione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromocionController extends Controller
{
    /**
     * Listar todas las Promocione;es
     */
    public function index()
    {
        try {
            $Promociones = Promocione::obtenerTodas();

            return response()->json([
                'success' => true,
                'data' => $Promociones->map(function($promo) {
                    return (new Promocione((array)$promo))->formatearParaMostrar();
                }),
                'total' => $Promociones->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener Promociones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la promoción activa actual
     */
    public function activa()
    {
        try {
            $Promocion = Promocione::obtenerPromocioneActiva();

            if (!$Promocion) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay promoción activa'
                ], 404);
            }

            $promoObj = new Promocione((array)$Promocion);

            return response()->json([
                'success' => true,
                'data' => $promoObj->formatearParaMostrar(),
                'descuentos' => [
                    'contado' => $Promocion->contado,
                    'mes' => $Promocion->mes,
                    'tres_meses' => $Promocion->tres_meses,
                    'seis_meses' => $Promocion->seis_meses
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener promoción activa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva promoción
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contado' => 'required|integer|min:0|max:100',
            'mes' => 'required|integer|min:0|max:100',
            'tres_meses' => 'required|integer|min:0|max:100',
            'seis_meses' => 'required|integer|min:0|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'temporada' => 'nullable|string|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar solapamiento de fechas
            $haySolapamiento = Promocione::haySolapamiento(
                Carbon::parse($request->fecha_inicio),
                Carbon::parse($request->fecha_final)
            );

            if ($haySolapamiento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una promoción en estas fechas'
                ], 422);
            }

            Promocione::guardarPromociones($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Promoción creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una promoción existente
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'contado' => 'required|integer|min:0|max:100',
            'mes' => 'required|integer|min:0|max:100',
            'tres_meses' => 'required|integer|min:0|max:100',
            'seis_meses' => 'required|integer|min:0|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'temporada' => 'nullable|string|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar solapamiento excluyendo esta promoción
            $haySolapamiento = Promocione::haySolapamiento(
                Carbon::parse($request->fecha_inicio),
                Carbon::parse($request->fecha_final),
                $id
            );

            if ($haySolapamiento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las fechas se solapan con otra promoción'
                ], 422);
            }

            Promocione::actualizarPromocione($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Promoción actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una promoción
     */
    public function destroy($id)
    {
        try {
            Promocione::eliminarPromocion($id);

            return response()->json([
                'success' => true,
                'message' => 'Promoción eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular descuento para un monto específico
     */
    public function calcularDescuento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monto' => 'required|numeric|min:0',
            'tipo_pago' => 'required|in:contado,mes,tres_meses,seis_meses'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $Promocione = Promocione::obtenerPromocionActiva();

            if (!$Promocione) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay promoción activa'
                ], 404);
            }

            $promoObj = new Promocione((array)$Promocione);
            $descuento = $promoObj->calcularDescuento(
                $request->tipo_pago,
                $request->monto
            );

            return response()->json([
                'success' => true,
                'data' => $descuento,
                'Promociones' => $Promocione->temporada
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular descuento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los descuentos para un monto
     */
    public function todosLosDescuentos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monto' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $Promocione = Promocione::obtenerPromocionesActiva();

            if (!$Promocione) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay promoción activa',
                    'data' => []
                ]);
            }

            $promoObj = new Promocione((array)$Promocione);
            $descuentos = $promoObj->obtenerTodosLosDescuentos($request->monto);

            return response()->json([
                'success' => true,
                'data' => $descuentos,
                'Promociones' => [
                    'temporada' => $Promocione->temporada,
                    'vigente_hasta' => Carbon::parse($Promocione->fecha_final)->format('d/m/Y')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular descuentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el mejor descuento disponible
     */
    public function mejorDescuento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'monto' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $Promocione = Promocione::obtenerPromocionesActiva();

            if (!$Promocione) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay promoción activa'
                ], 404);
            }

            $promoObj = new Promocione((array)$Promocione);
            $mejorDescuento = $promoObj->obtenerMejorDescuento($request->monto);

            return response()->json([
                'success' => true,
                'data' => $mejorDescuento,
                'recomendacion' => "Ahorra \${$mejorDescuento['ahorro']} pagando {$mejorDescuento['tipo_pago']}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular mejor descuento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar Promociones por estado
     */
    public function porEstado($estado)
    {
        try {
            $query = Promocione::query();

            switch ($estado) {
                case 'activas':
                    $Promociones = $query->activas()->get();
                    break;
                case 'futuras':
                    $Promociones = $query->futuras()->get();
                    break;
                case 'vencidas':
                    $Promociones = $query->vencidas()->get();
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Estado no válido. Use: activas, futuras, vencidas'
                    ], 422);
            }

            return response()->json([
                'success' => true,
                'estado' => $estado,
                'data' => $Promociones->map(function($promo) {
                    return $promo->formatearParaMostrar();
                }),
                'total' => $Promociones->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener Promociones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear promoción por temporada
     */
    public function crearPorTemporada(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temporada' => 'required|in:Verano,Invierno,Primavera,Otoño,Navidad,Especial',
            'descuentos' => 'required|array',
            'descuentos.contado' => 'required|integer|min:0|max:100',
            'descuentos.mes' => 'required|integer|min:0|max:100',
            'descuentos.tres_meses' => 'required|integer|min:0|max:100',
            'descuentos.seis_meses' => 'required|integer|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Promocione::crearPromocionesTemporada(
                $request->temporada,
                $request->descuentos
            );

            return response()->json([
                'success' => true,
                'message' => "Promoción de {$request->temporada} creada exitosamente"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener temporadas disponibles
     */
    public function temporadas()
    {
        return response()->json([
            'success' => true,
            'temporadas' => Promocione::getTemporadas()
        ]);
    }

    /**
     * Validar si hay solapamiento de fechas
     */
    public function validarFechas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'excluir_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $haySolapamiento = Promocione::haySolapamiento(
                Carbon::parse($request->fecha_inicio),
                Carbon::parse($request->fecha_final),
                $request->excluir_id
            );

            return response()->json([
                'success' => true,
                'hay_solapamiento' => $haySolapamiento,
                'disponible' => !$haySolapamiento
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar fechas: ' . $e->getMessage()
            ], 500);
        }
    }
}