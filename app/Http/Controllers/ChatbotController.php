<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function procesar(Request $request)
    {
        // Enviamos el mensaje del paciente al servidor de Python (Flask)
        $response = Http::post('http://127.0.0.1:5000/chat', [
            'mensaje' => $request->mensaje,
            'paciente_id' => auth()->id() // Por si el paciente ya está logueado
        ]);

        return $response->json();
    }
}