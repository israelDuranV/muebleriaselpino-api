<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Departamento;
use Illuminate\Support\Facades\Hash;
use \stdClass;

class DepartamentosController extends Controller
{
    public function getDepartamentos()
    {
        $departamentos = Departamento::all();

        if ($departamentos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron muebles'], 404);
        }

        return response()->json($departamentos);
    }
    public function getDepartamento($id)
    {
        $departamentos = Departamento::get($id);

        if ($departamentos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron muebles'], 404);
        }

        return response()->json($departamentos);
    }
}
