<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class AvailableAppointmentsController extends Controller
{
    /**
     * Retorna os próximos horários disponíveis, considerando os agendamentos existentes.
     * 
     * Você pode ajustar os dias e horários fixos conforme a rotina da clínica.
     */
    public function index(Request $request)
    {
        $diasDisponiveis = 7; // mostra horários dos próximos 7 dias
        $intervalo = 60; // minutos entre cada horário
        $inicioExpediente = '09:00';
        $fimExpediente = '18:00';

        $hoje = Carbon::today();
        $horariosLivres = [];

        for ($i = 0; $i < $diasDisponiveis; $i++) {
            $data = $hoje->copy()->addDays($i);
            $dataString = $data->toDateString();

            $ocupados = Appointment::where('date', $dataString)
                ->where('status', '!=', 'cancelado')
                ->pluck('time')
                ->toArray();

            $inicio = Carbon::parse($inicioExpediente);
            $fim = Carbon::parse($fimExpediente);

            while ($inicio < $fim) {
                $hora = $inicio->format('H:i');
                if (!in_array($hora, $ocupados)) {
                    $horariosLivres[$dataString][] = $hora;
                }
                $inicio->addMinutes($intervalo);
            }
        }

        return response()->json([
            'success' => true,
            'horarios_disponiveis' => $horariosLivres
        ]);
    }
}