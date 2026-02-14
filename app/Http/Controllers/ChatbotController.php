<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function consulta(Request $request)
    {
        try {
        $url = env('CHATBOT_API_URL', 'http://127.0.0.1:5000/chat');

        $response = Http::post($url, [
            'mensaje' => $request->mensaje,
            'session_id' => $request->session_id,
        ]);


            return $response->json();
        } catch (\Exception $e) {
            // Si Python está apagado, avisamos al usuario
            return response()->json(['¡Hola! Un gusto saludarte. Mi sistema de IA se está activando. 
            Por favor, danos 30 segundos y vuelve a intentarlo. ¡Tu salud es nuestra prioridad!']);
        }
    }
}