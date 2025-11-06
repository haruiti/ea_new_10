<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Models\Conversation;
use Carbon\Carbon;

class LeadsRecontato extends Command
{
    protected $signature = 'leads:recontato';
    protected $description = 'Verifica leads frios e realiza recontato automático com mensagens humanizadas.';

    public function handle()
    {
        $this->info('🔁 Iniciando rotina automática de recontato de leads...');
        Log::info('🔁 Iniciando rotina automática de recontato de leads...');

        $diasSemContato = 7; // tempo de inatividade para considerar "frio"
        $dataLimite = Carbon::now()->subDays($diasSemContato);

        // Leads que ainda não converteram nem agendaram, e sem conversa recente
        $leads = Lead::whereIn('status', ['novo', 'em_contato'])
            ->where(function ($q) use ($dataLimite) {
                $q->whereDoesntHave('conversas')
                  ->orWhereHas('conversas', function ($sub) use ($dataLimite) {
                      $sub->where('created_at', '<', $dataLimite);
                  });
            })
            ->get();

        if ($leads->isEmpty()) {
            $this->info('✅ Nenhum lead frio encontrado.');
            Log::info('✅ Nenhum lead frio encontrado.');
            return;
        }

        $this->info("📞 Recontatando {$leads->count()} leads frios...");
        Log::info("📞 Recontatando {$leads->count()} leads frios...");

        foreach ($leads as $lead) {
            try {
                if (!$lead->phone) {
                    Log::warning("⚠️ Lead sem número de telefone: ID {$lead->id}");
                    continue;
                }

                // Define mensagem personalizada
                $mensagem = $this->gerarMensagemHumanizada($lead);

                // Envia via API do bot
                $response = Http::post(env('BOT_API_URL') . '/send-message', [
                    'numero' => $lead->phone,
                    'mensagem' => $mensagem,
                ]);

                if ($response->failed()) {
                    Log::error("❌ Erro ao enviar mensagem para {$lead->phone}");
                    continue;
                }

                // Registra conversa
                Conversation::create([
                    'numero' => $lead->phone,
                    'tipo' => 'enviada',
                    'mensagem' => $mensagem,
                    'dados_extras' => ['origem' => 'recontato_automatico'],
                ]);

                // Atualiza status do lead
                $lead->update(['status' => 'em_contato']);

                $this->info("✅ Recontato enviado para {$lead->phone}");
                Log::info("✅ Recontato enviado para {$lead->phone}");
            } catch (\Exception $e) {
                Log::error("❌ Erro ao recontatar {$lead->phone}: {$e->getMessage()}");
            }
        }

        $this->info('🏁 Rotina de recontato concluída.');
        Log::info('🏁 Rotina de recontato concluída.');
    }

    /**
     * Mensagem personalizada e acolhedora
     */
    private function gerarMensagemHumanizada($lead)
    {
        $primeiroNome = explode(' ', trim($lead->name ?? ''))[0] ?? '';

        return "Olá {$primeiroNome}! 👋\n\n"
            . "Aqui é da *Yamato Hipnose Clínica*. Notei que conversamos há alguns dias e fiquei em dúvida se você ainda tem interesse em entender melhor "
            . "como a hipnoterapia pode te ajudar a lidar com as questões emocionais.\n\n"
            . "Posso te explicar novamente como funciona a *consulta de avaliação*? 😊";
    }
}
