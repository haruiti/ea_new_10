<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Lead;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Cria um novo agendamento e adiciona no Google Calendar
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'date'    => 'required|date',
            'time'    => 'required|date_format:H:i',
            'notes'   => 'nullable|string',
        ]);

        // Verifica se o horário já está ocupado
        $exists = Appointment::whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Este horário já está ocupado.'
            ], 409);
        }

        $validated['status'] = 'pending';
        $appointment = Appointment::create($validated);

        // 🔹 Cria evento no Google Calendar
        try {
            $eventId = $this->createGoogleEvent($appointment);
            $appointment->update(['google_event_id' => $eventId]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar evento no Google Calendar: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Agendamento realizado com sucesso!',
            'data' => $appointment
        ], 201);
    }

    /**
     * Atualiza um agendamento e o evento correspondente no Google Calendar
     */
    public function apiUpdate(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'date'  => 'required|date',
            'time'  => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        // Verifica conflito de horário
        $exists = Appointment::whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Este horário já está ocupado.'
            ], 409);
        }

        $appointment->update($validated);

        // 🔹 Atualiza evento no Google Calendar
        try {
            if ($appointment->google_event_id) {
                $this->updateGoogleEvent($appointment);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar evento no Google Calendar: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    /**
     * Cancela um agendamento e remove do Google Calendar
     */
    public function apiCancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelado']);

        // 🔹 Remove evento do Google Calendar
        try {
            if ($appointment->google_event_id) {
                $this->deleteGoogleEvent($appointment->google_event_id);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao remover evento no Google Calendar: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Agendamento cancelado com sucesso.'
        ]);
    }

    /**
     * -------------------------------
     * 🔧 MÉTODOS PRIVADOS AUXILIARES
     * -------------------------------
     */

    private function getGoogleService()
    {
        $client = new Google_Client();
        $client->setAuthConfig(config('google.credentials_path'));
        $client->addScope(Google_Service_Calendar::CALENDAR);

        return new Google_Service_Calendar($client);
    }

    private function createGoogleEvent($appointment)
    {
        $service = $this->getGoogleService();
        $calendarId = config('google.calendar_id');

        $lead = Lead::find($appointment->lead_id);

        $startDateTime = Carbon::parse("{$appointment->date} {$appointment->time}");
        $endDateTime   = $startDateTime->copy()->addMinutes(60);

        $event = new Google_Service_Calendar_Event([
            'summary' => "Consulta com {$lead->nome}",
            'description' => $appointment->notes ?? 'Consulta agendada via Yamato Hipnose Clínica',
            'start' => ['dateTime' => $startDateTime->toRfc3339String(), 'timeZone' => 'America/Belem'],
            'end'   => ['dateTime' => $endDateTime->toRfc3339String(), 'timeZone' => 'America/Belem'],
        ]);

        $createdEvent = $service->events->insert($calendarId, $event);
        return $createdEvent->id;
    }

    private function updateGoogleEvent($appointment)
    {
        $service = $this->getGoogleService();
        $calendarId = config('google.calendar_id');

        $lead = Lead::find($appointment->lead_id);
        $event = $service->events->get($calendarId, $appointment->google_event_id);

        $startDateTime = Carbon::parse("{$appointment->date} {$appointment->time}");
        $endDateTime   = $startDateTime->copy()->addMinutes(60);

        $event->setSummary("Consulta com {$lead->nome}");
        $event->setDescription($appointment->notes ?? 'Consulta agendada via Yamato Hipnose Clínica');
        $event->setStart(['dateTime' => $startDateTime->toRfc3339String(), 'timeZone' => 'America/Belem']);
        $event->setEnd(['dateTime' => $endDateTime->toRfc3339String(), 'timeZone' => 'America/Belem']);

        $service->events->update($calendarId, $event->getId(), $event);
    }

    private function deleteGoogleEvent($googleEventId)
    {
        $service = $this->getGoogleService();
        $calendarId = config('google.calendar_id');

        $service->events->delete($calendarId, $googleEventId);
    }

    public function apiByLead($id)
    {
        $appointments = Appointment::where('lead_id', $id)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        // ⚠️ Em vez de retornar 404 quando não há registros
        // retorne apenas um array vazio
        return response()->json($appointments, 200);
    }



}
