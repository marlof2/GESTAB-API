<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GoogleCalendarEventRequest;
use App\Services\GoogleCalendarService;
use Illuminate\Http\JsonResponse;
use App\Models\GoogleCalendarEvent;

final class GoogleCalendarController extends Controller
{

    public function getEventsByUser(): JsonResponse
    {
        $events = GoogleCalendarEvent::where('user_id', auth()->id())
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return response()->json($events);
    }


    public function store(GoogleCalendarEventRequest $request): JsonResponse
    {
        try {
            $calendarService = new GoogleCalendarService($request->user());
            $result = $calendarService->store($request->all());

            if (!$result['success']) {
                return response()->json([
                    'message' => 'Erro ao criar evento',
                    'error' => $result['message']
                ], 500);
            }

            return response()->json([
                'message' => 'Evento criado com sucesso',
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar a requisição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function updateEvent(GoogleCalendarEventRequest $request, string $eventId): JsonResponse
    // {
    //     try {
    //         $calendarEvent = GoogleCalendarEvent::findOrFail($eventId);

    //         if ($calendarEvent->user_id !== $request->user()->id) {
    //             return response()->json([
    //                 'message' => 'Não autorizado a atualizar este evento'
    //             ], 403);
    //         }

    //         $calendarService = new GoogleCalendarService($request->user());
    //         $result = $calendarService->updateEvent($calendarEvent->google_event_id, $request->validated());

    //         if (!$result['success']) {
    //             return response()->json([
    //                 'message' => 'Erro ao atualizar evento',
    //                 'error' => $result['message']
    //             ], 500);
    //         }

    //         return response()->json([
    //             'message' => 'Evento atualizado com sucesso',
    //             'data' => $result
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Erro ao processar a requisição',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function destroy($user_id, $list_id): JsonResponse
    {
        try {

            $calendarService = new GoogleCalendarService(auth()->user());
            $result = $calendarService->destroy($user_id, $list_id);

            if (!$result['success']) {
                return response()->json([
                    'message' => 'Erro ao deletar evento',
                    'error' => $result['message']
                ], 500);
            }

            return response()->json([
                'message' => 'Evento deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar a requisição',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
