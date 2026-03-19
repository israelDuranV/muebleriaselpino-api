<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    /**
     * Formatos de entrada permitidos
     */
    private array $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];

    /**
     * Calidad WebP (1-100)
     */
    private int $webpQuality = 82;

    /**
     * Ancho máximo en px — null para no redimensionar
     */
    private int|null $maxWidth = 1920;

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'imagen'    => 'required|file|mimes:jpg,jpeg,png|max:10240', // max 10MB
            'carpeta'   => 'nullable|string|max:100',   // subcarpeta opcional
            'nombre'    => 'nullable|string|max:100',   // nombre personalizado opcional
        ]);

        try {
            $archivo = $request->file('imagen');

            // ── 2. Nombre del archivo ────────────────────────────────────────
            if ($request->filled('nombre')) {
                // Limpiar el nombre que viene del request
                $nombre = Str::slug($request->input('nombre'));
            } else {
                // Generar nombre único basado en timestamp + random
                $nombre = time() . '_' . Str::random(10);
            }

            $nombreArchivo = $nombre . '.webp';

            // ── 3. Carpeta destino ───────────────────────────────────────────
            $subcarpeta = $request->filled('carpeta')
                ? Str::slug($request->input('carpeta'))
                : 'general';

            $carpetaRelativa = "imagenes/{$subcarpeta}";
            $carpetaAbsoluta = storage_path("app/public/{$carpetaRelativa}");

            if (!file_exists($carpetaAbsoluta)) {
                mkdir($carpetaAbsoluta, 0755, true);
            }

            $rutaAbsoluta = "{$carpetaAbsoluta}/{$nombreArchivo}";

            // ── 4. Procesar y convertir a WebP ───────────────────────────────
            $imagen = Image::read($archivo->getRealPath());

            // Redimensionar si supera el ancho máximo (mantiene proporción)
            if ($this->maxWidth && $imagen->width() > $this->maxWidth) {
                $imagen->scaleDown(width: $this->maxWidth);
            }

            // Convertir y guardar como WebP
            $imagen->toWebp(quality: $this->webpQuality)
                   ->save($rutaAbsoluta);

            // ── 5. Construir URLs de respuesta ───────────────────────────────
            $rutaRelativa  = "{$carpetaRelativa}/{$nombreArchivo}";
            $urlPublica    = asset("storage/{$rutaRelativa}");

            return response()->json([
                'success'   => true,
                'mensaje'   => 'Imagen guardada correctamente',
                'data'      => [
                    'nombre'        => $nombreArchivo,
                    'ruta'          => $rutaRelativa,
                    'url'           => $urlPublica,
                    'carpeta'       => $subcarpeta,
                    'formato'       => 'webp',
                    'ancho'         => $imagen->width(),
                    'alto'          => $imagen->height(),
                    'tamano_kb'     => round(filesize($rutaAbsoluta) / 1024, 2),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al procesar la imagen',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar imagen por ruta relativa
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'ruta' => 'required|string',
        ]);

        try {
            $rutaAbsoluta = storage_path('app/public/' . $request->input('ruta'));

            if (!file_exists($rutaAbsoluta)) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'El archivo no existe',
                ], 404);
            }

            unlink($rutaAbsoluta);

            return response()->json([
                'success' => true,
                'mensaje' => 'Imagen eliminada correctamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al eliminar la imagen',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}