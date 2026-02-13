<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function consulta(Request $request)
    {
        try {
            // Esto envía el mensaje al servidor de Flask que tienes corriendo
            $response = Http::post('http://127.0.0.1:5000/chat', [
            'mensaje' => $request->mensaje,
            'session_id' => $request->session_id,
        ]);


            return $response->json();
        } catch (\Exception $e) {
            // Si Python está apagado, avisamos al usuario
            return response()->json(['respuesta' => 'El servidor de IA no responde. Asegúrate de que app.py esté corriendo.']);
        }
    }
}