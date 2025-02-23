<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\AuthenticationException;
use App\Exceptions\GoogleCalendarException;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Auth;
use Exception;
use Google\Service\Calendar\EventDateTime;
use App\Models\GoogleCalendarEvent;
use App\Models\Lista;
use Illuminate\Support\Facades\DB;

final class GoogleCalendarService
{
    private GoogleClient $client;
    private Calendar $service;
    private $user;

    public function __construct($user)
    {
        $this->client = new GoogleClient();

        // Configuração do cliente HTTP
        $this->client->setHttpClient(
            new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 10
            ])
        );

        $this->client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
        $this->client->setScopes(Calendar::CALENDAR_EVENTS);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        // Configura o token do usuário atual
        $this->setUserToken($user);

        $this->service = new Calendar($this->client);
        $this->user = $user;
    }

    private function setUserToken($user): void
    {
        try {
            if (is_null($user)) {
                throw new AuthenticationException('Usuário não está autenticado');
            }

            $token = $user->googleCalendarToken;
            if (is_null($token)) {
                throw new GoogleCalendarException('Token do Google Calendar não encontrado para este usuário');
            }

            $this->client->setAccessToken([
                'access_token' => $token->access_token,
                'refresh_token' => $token->refresh_token,
                'token_type' => $token->token_type,
                'expires_in' => $token->expires_at->timestamp - now()->timestamp,
            ]);

            if ($this->client->isAccessTokenExpired()) {
                if (empty($token->refresh_token)) {
                    throw new GoogleCalendarException('Refresh token não disponível. Necessário reautenticar');
                }

                $newToken = $this->client->fetchAccessTokenWithRefreshToken($token->refresh_token);

                if (!isset($newToken['access_token'])) {
                    throw new GoogleCalendarException('Falha ao atualizar o token de acesso');
                }

                $token->update([
                    'access_token' => $newToken['access_token'],
                    'expires_at' => now()->addSeconds($newToken['expires_in'])
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Erro na autenticação do Google Calendar', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            throw $e;
        }
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();

            $list = Lista::with('professional:id,name', 'service:id,name')->find($data['list_id']);

            $title = 'Agendamento com ' . $list->professional->name;
            $description = 'O serviço prestado será ' . $list->service->name;
            $event = new Event([
                'summary' => $title,
                'description' => $description,
                'start' => [
                    'dateTime' => $data['start_time'],
                    'timeZone' => 'America/Sao_Paulo',
                ],
                'end' => [
                    'dateTime' => $data['end_time'],
                    'timeZone' => 'America/Sao_Paulo',
                ],
                'attendees' => [
                    ['email' => $data['client_email']],
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 120],
                        ['method' => 'popup', 'minutes' => 60],
                    ],
                ],
            ]);

            $createdEvent = $this->service->events->insert('primary', $event);

            // Salva o evento no banco de dados
            $calendarEvent = GoogleCalendarEvent::create([
                'google_event_id' => $createdEvent->getId(),
                'title' => $title,
                'description' => $description,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'client_email' => $data['client_email'],
                'user_id' => $this->user->id,
                'list_id' => $data['list_id'],
                'html_link' => $createdEvent->getHtmlLink()
            ]);

            DB::commit();

            return [
                'success' => true,
                'event_id' => $createdEvent->getId(),
                'local_event_id' => $calendarEvent->id,
                'html_link' => $createdEvent->getHtmlLink(),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateEvent(string $eventId, array $eventData): array
    {
        try {
            $event = $this->service->events->get('primary', $eventId);

            $event->setSummary($eventData['title']);
            $event->setDescription($eventData['description']);
            $event->setStart(new EventDateTime([
                'dateTime' => $eventData['start_time'],
                'timeZone' => 'America/Sao_Paulo'
            ]));
            $event->setEnd(new EventDateTime([
                'dateTime' => $eventData['end_time'],
                'timeZone' => 'America/Sao_Paulo'
            ]));

            $updatedEvent = $this->service->events->update('primary', $eventId, $event);

            return [
                'success' => true,
                'event_id' => $updatedEvent->getId()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function destroy($user_id, $list_id): array
    {
        try {
            DB::beginTransaction();

            $calendarEvent = GoogleCalendarEvent::where('user_id', $user_id)
                ->where('list_id', $list_id)
                ->firstOrFail();

            $this->service->events->delete('primary', $calendarEvent->google_event_id);
            $calendarEvent->delete();

            DB::commit();

            return [
                'success' => true
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
