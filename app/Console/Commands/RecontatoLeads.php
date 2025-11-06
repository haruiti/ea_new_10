<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RecontatoLeads extends Command
{
    protected $signature = 'leads:recontato';
    protected $description = 'Faz recontato automático com leads frios';

    public function handle()
    {
        $hoje = Carbon::now();

        // 1️⃣ Leads que entraram mas não responderam (2 dias)
        $semResposta = Lead::where('status', 'novo')
            ->where('ultima_interacao', '<', $hoje->copy()->subDays(2))
            ->get();

        foreach ($semResposta as $lead) {
            $this->enviarMensagem($lead, 1);
            $lead->status = 'em_contato';
            $lead->save();
        }

        // 2️⃣ Leads que responderam mas não agendaram (5 dias)
        $semAgendar = Lead::where('status', 'em_contato')
            ->where('ultima_interacao', '<', $hoje->copy()->subDays(5))
            ->get();

        foreach ($semAgendar as $lead) {
            $this->enviarMensagem($lead, 2);
        }

        // 3️⃣ Leads não qualificados (30 dias)
        $naoConvertidos = Lead::where('status', 'perdido')
            ->where('ultima_interacao', '<', $hoje->copy()->subDays(30))
            ->get();

        foreach ($naoConvertidos as $lead) {
            $this->enviarMensagem($lead, 3);
        }

        $this->info('✅ Recontato automático concluído.');
    }

    private function enviarMensagem(Lead $lead, int $tipo)
    {
        $mensagem = match ($tipo) {
            1 => "Olá! 😊 Vi que você chegou a entrar em contato conosco, mas não conseguimos conversar. Gostaria de entender melhor como funciona a hipnoterapia?",
            2 => "Oi! 🌿 Vi que conversamos há alguns dias, mas não finalizamos o agendamento da sua consulta de avaliação. Posso te ajudar com o melhor horário?",
            3 => "Olá! 🌱 Aqui é da Yamato Hipnose Clínica. Estamos compartilhando novas informações sobre o poder da hipnoterapia na ansiedade e no emocional. Posso te enviar?",
            default => null,
        };

        if ($mensagem) {
            Http::post(env('WHATSAPP_BOT_URL') . '/send-message', [
                'numero' => $lead->phone,
                'mensagem' => $mensagem,
            ]);
        }
    }
}
