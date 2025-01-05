<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolicitudesArriendoController extends Controller
{
    public function index()
    {
        // Obtener todas las solicitudes de arriendo
        $solicitudes = solicitudesArriendo::all();

        // Retornar la respuesta como JSON
        return response()->json([
            'data' => $solicitudes
        ]);
    }
}
