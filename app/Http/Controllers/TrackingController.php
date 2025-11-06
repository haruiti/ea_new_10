<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function redirectToWhatsApp(Request $request)
    {
        try {
            // Gera o código do lead
            $lead_code = strtoupper(Str::random(8));

            // (Opcional) salva no banco, se quiser rastrear o lead
            // Lead::create([...]);

            // Retorna um JSON ao invés de redirecionar
            return response()->json([
                'success' => true,
                'lead_code' => $lead_code,
                'numero' => '5591985867184',
                'mensagem' => "Olá! Vi seu anúncio sobre hipnoterapia e quero entender melhor. (Código: {$lead_code})",
            ]);
        } catch (\Exception $e) {
            Log::error('Erro no go-whatsapp: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Erro interno ao gerar link do WhatsApp'
            ], 500);
        }
    }
}
