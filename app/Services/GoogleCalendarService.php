<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

class GoogleCalendarService
{
    protected $client;
    protected $service;
    protected $calendarId;

    public function __construct()
    {
        $this->calendarId = env('GOOGLE_CALENDAR_ID'); // Corrigido nome da variável
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google/service-account.json'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->service = new Calendar($this->client);
    }

    /**
     * Cria um evento no Google Calendar (sem convidados)
     */
    public function createEvent($appointment)
    {
        $lead = $appointment->lead;

        $event = new Event([
            'summary' => "Consulta de Avaliação - {$lead->name}",
            'description' => "Consulta de avaliação com {$lead->name} ({$lead->phone})",
            'start' => [
                'dateTime' => "{$appointment->date}T{$appointment->time}:00-03:00",
                'timeZone' => 'America/Belem',
            ],
            'end' => [
                'dateTime' => "{$appointment->date}T" . $this->addMinutes($appointment->time, 60) . ":00-03:00",
                'timeZone' => 'America/Belem',
            ],
            'location' => 'Travessa 9 de Janeiro nº 2110 - Sala 1105 - Edifício Wall Street, Belém - PA',
        ]);

        // IMPORTANTE: Nenhum 'attendees', e forçar sendUpdates = 'none'
        $createdEvent = $this->service->events->insert(
            $this->calendarId,
            $event,
            ['sendUpdates' => 'none']
        );

        return $createdEvent->id;
    }

    /**
     * Atualiza um evento existente
     */
    public function updateEvent($appointment)
    {
        if (!$appointment->google_event_id) return null;

        $event = $this->service->events->get($this->calendarId, $appointment->google_event_id);

        $event->setStart([
            'dateTime' => "{$appointment->date}T{$appointment->time}:00-03:00",
            'timeZone' => 'America/Belem',
        ]);
        $event->setEnd([
            'dateTime' => "{$appointment->date}T" . $this->addMinutes($appointment->time, 60) . ":00-03:00",
            'timeZone' => 'America/Belem',
        ]);

        $this->service->events->update($this->calendarId, $event->id, $event, ['sendUpdates' => 'none']);
        return true;
    }

    /**
     * Exclui um evento existente
     */
    public function deleteEvent($appointment)
    {
        if (!$appointment->google_event_id) return null;

        $this->service->events->delete(
            $this->calendarId,
            $appointment->google_event_id,
            ['sendUpdates' => 'none']
        );

        return true;
    }

    /**
     * Função auxiliar para adicionar minutos ao horário
     */
    private function addMinutes($time, $minutes)
    {
        return date('H:i', strtotime("+{$minutes} minutes", strtotime($time)));
    }
}
