<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LeadTracking;

class TrackingController extends Controller
{
    public function redirectToWhatsApp(Request $request)
    {
        // 1️⃣ Gera um código único
        $leadCode = strtoupper(Str::random(8));

        // 2️⃣ Cria o registro
        LeadTracking::create([
            'lead_code' => $leadCode,
            'gclid' => $request->get('gclid'),
            'utm_source' => $request->get('utm_source'),
            'utm_medium' => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign'),
            'utm_term' => $request->get('utm_term'),
            'utm_content' => $request->get('utm_content'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
        ]);

        // 3️⃣ Monta a mensagem personalizada
        $whatsAppNumber = '5591985867184'; // seu número no formato internacional
        $mensagem = urlencode("Olá, vi seu anúncio sobre hipnoterapia e quero entender melhor. (Código: {$leadCode})");

        // 4️⃣ Redireciona para o WhatsApp
        $whatsAppUrl = "https://wa.me/{$whatsAppNumber}?text={$mensagem}";

        return redirect()->away($whatsAppUrl);
    }
}
