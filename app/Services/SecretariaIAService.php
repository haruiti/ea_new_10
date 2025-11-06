<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Lead;
use App\Models\Appointment;
use Carbon\Carbon;

class SecretariaIAService
{
    public function processarMensagem($numero, $mensagem)
    {
        $mensagemLower = mb_strtolower($mensagem, 'UTF-8');
        $lead = Lead::where('phone', $numero)->first();

        if (!$lead) {
            $lead = Lead::create([
                'name' => 'Lead ' . $numero,
                'phone' => $numero,
                'status' => 'novo'
            ]);
        }

        if ($this->solicitouAgendamento($mensagemLower)) {
            return $this->sugerirHorarios($lead);
        }

        if ($this->confirmouAgendamento($mensagemLower)) {
            return $this->confirmarAgendamento($lead, $mensagemLower);
        }

        return [
            'resposta' => "Olá! 😊 Sou a assistente da YHC. Posso te ajudar a agendar sua consulta de avaliação ou tirar dúvidas sobre o tratamento. O que você prefere?",
            'acao' => 'mensagem_inicial'
        ];
    }

    private function solicitouAgendamento($texto)
    {
        $gatilhos = ['agendar', 'consulta', 'marcar', 'atendimento', 'horário'];
        return collect($gatilhos)->contains(fn($g) => str_contains($texto, $g));
    }

    private function confirmouAgendamento($texto)
    {
        return preg_match('/(segunda|terça|quarta|quinta|sexta|sábado|domingo).*([0-2]?\d[:h][0-5]\d?)/', $texto);
    }

    private function sugerirHorarios($lead)
    {
        $response = Http::get(url('/api/agendamentos/disponiveis'));
        $dados = $response->json();

        if (empty($dados['horarios_disponiveis'])) {
            return [
                'resposta' => "No momento não encontrei horários livres nos próximos dias. Pode me informar o melhor período (manhã, tarde ou noite)?",
                'acao' => 'aguardar_preferencia'
            ];
        }

        $opcoes = collect($dados['horarios_disponiveis'])->take(3)->map(function ($horarios, $data) {
            $dataFmt = Carbon::parse($data)->locale('pt_BR')->isoFormat('dddd (DD/MM)');
            $hora = $horarios[0] ?? null;
            return ucfirst($dataFmt) . " às $hora";
        })->implode(", ");

        return [
            'resposta' => "Perfeito! 😊 Tenho os seguintes horários disponíveis: $opcoes. Qual deles você prefere?",
            'acao' => 'aguardar_confirmacao'
        ];
    }

    private function confirmarAgendamento($lead, $texto)
    {
        $dias = [
            'segunda' => 1, 'terça' => 2, 'terca' => 2, 'quarta' => 3,
            'quinta' => 4, 'sexta' => 5, 'sábado' => 6, 'sabado' => 6, 'domingo' => 0
        ];

        foreach ($dias as $dia => $num) {
            if (str_contains($texto, $dia)) {
                $hora = $this->extrairHora($texto);
                $data = Carbon::now()->next($num);
                Appointment::create([
                    'lead_id' => $lead->id,
                    'date' => $data->toDateString(),
                    'time' => $hora,
                    'status' => 'agendado',
                    'notes' => 'Agendado automaticamente pela secretária IA'
                ]);

                $lead->update(['status' => 'agendado']);

                return [
                    'resposta' => "Perfeito, $lead->name! 💫 Sua consulta de avaliação está marcada para *" .
                        $data->translatedFormat('l, d/m') . " às $hora*. Te esperamos na YHC!",
                    'acao' => 'agendamento_confirmado'
                ];
            }
        }

        return [
            'resposta' => "Não consegui identificar o dia e o horário certinhos 😅. Pode me confirmar novamente?",
            'acao' => 'aguardar_confirmacao'
        ];
    }

    private function extrairHora($texto)
    {
        if (preg_match('/([0-2]?\d)[:h]?([0-5]?\d)?/', $texto, $m)) {
            $hora = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $min = isset($m[2]) && $m[2] !== '' ? str_pad($m[2], 2, '0', STR_PAD_LEFT) : '00';
            return "$hora:$min";
        }
        return '15:00';
    }
}
