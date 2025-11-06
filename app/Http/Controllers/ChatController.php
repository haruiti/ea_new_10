<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversa;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'telefone' => 'required|string|max:20',
            'mensagem_cliente' => 'required|string',
            'resposta_ia' => 'nullable|string',
        ]);

        $conversa = Conversa::create([
            'numero' => $validated['telefone'],
            'mensagem' => $validated['mensagem_cliente'],
            'tipo' => 'recebida',
            'dados_extras' => json_encode([
                'resposta_ia' => $validated['resposta_ia'] ?? null,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversa,
        ]);
    }

    public function index()
    {
        return response()->json(Conversa::latest()->get());
    }
}
