<?php

namespace App\Services;

use App\Models\BlockCalendar;
use Illuminate\Http\Response;
use App\Models\Lista;
use App\Models\Services;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class ListService
{
    protected $list;
    protected $pageLimit;
    protected $blockcalendar;
    public function __construct(Lista $list, BlockCalendar $blockcalendar)
    {
        $this->list = $list;
        $this->pageLimit = 10;
        $this->blockcalendar = $blockcalendar;
    }
    public function index($request)
    {
        $data = $this->list->with('user:id,name,phone', 'professional:id,name', 'status:id,name', 'service')
            ->whereDate('date', '=', $request->date)
            ->where('establishment_id', $request->establishment_id)
            ->where('professional_id', $request->professional_id)
            ->orderBy('date');

        if ($request->filled('clientsAttended') && $request->clientsAttended == 'attended') {
            $data->where('status_id', 3);
        } else {
            $data->whereIn('status_id', [1, 2]);
        }

        if ($request->typeSchedule == "HM") {
            $data->orderBy('time');
        }

        if ($request->filled('search')) {
            $data->whereRelation('user', 'name', 'LIKE', '%' . $request->search . '%');
        }

        $data = $data->paginate($this->pageLimit);
        return response()->json($data, Response::HTTP_OK);
    }
    public function store($request)
    {
        $request['status_id'] = 2;

        try {
            if ($request->typeSchedule == "HM") {
                $this->checkAppointmentIsInFuture($request);
                $this->checkDisponibilityOfTime($request);
            }

            if ($request->filled('block_calendar_id') && $request->block_calendar_id != null) {
                $this->checkBlockCalendar($request);
            }

            $data = $this->list->create($request->all());

            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                "message" => 'Não foi possível cadastrar',
                "error" => [$e->getMessage()]
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    private function checkBlockCalendar($request): void
    {
        $blockCalendar = $this->blockcalendar->find($request->block_calendar_id);

        if ($blockCalendar) {
            $requestTime = Carbon::parse($request->time);
            $blockStart = Carbon::parse($blockCalendar->time_start);
            $blockEnd = Carbon::parse($blockCalendar->time_end);

            if ($requestTime->between($blockStart, $blockEnd)) {
                throw new Exception(
                    "Não é possível agendar para o dia " . Carbon::parse($request->date)->format('d/m/Y') .
                    " às " . $requestTime->format('H:i') .
                    " porque esta data está bloqueada, por favor verifique no calendário na tela anterior." .
                    " Periodo do bloqueio: " . $blockStart->format('H:i') . " até " . $blockEnd->format('H:i'),
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }
        }
    }

    public function show($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function update($request, $id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        $dataFrom = $request->all();
        try {
            $data->update($dataFrom);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function destroy($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->delete();
            return response()->json(['success' => 'Deletado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível excluir', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function statusEmAtendimento($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->update(['status_id' => 1]);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function statusAguardandoAtendimento($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->update(['status_id' => 2]);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function statusConcluido($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->update(['status_id' => 3]);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function statusDesistiu($id)
    {
        $data = $this->list->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->update(['status_id' => 4]);
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function checkAppointmentIsInFuture($request)
    {
        // Definir o fuso horário
        $timezone = 'America/Sao_Paulo';

        // Obter a data e hora solicitada e a atual como objetos Carbon, sem milissegundos
        $requestedDateTime = Carbon::parse($request->date . ' ' . $request->time, $timezone)->startOfMinute();
        $currentDateTime = Carbon::now($timezone)->startOfMinute();

        // Comparação direta de objetos Carbon sem milissegundos
        if ($requestedDateTime->lessThan($currentDateTime)) {

            // A data/hora atual é maior ou igual à data/hora solicitada, não permite salvar
            throw new Exception(
                "Não é possível agendar para o dia " . $requestedDateTime->format('d/m/Y') .
                    " às " . $requestedDateTime->format('H:i') . " porque este horário já passou.",
                Response::HTTP_NOT_ACCEPTABLE
            );
        }
    }



    public function checkDisponibilityOfTime($request)
    {
        $existingAppointments = $this->list
            ->where([
                'establishment_id' => $request->establishment_id,
                'date' => $request->date,
                'professional_id' => $request->professional_id,
            ])
            ->whereIn('status_id', [1, 2])
            ->with('service')
            ->orderBy('time')
            ->get();

        if ($existingAppointments->isEmpty()) {
            return true;
        }

        // Formatar e converter o horário solicitado
        $requestedTimeFormatted = Carbon::parse($request->time)->format('H:i');
        $requestedTime = Carbon::parse($requestedTimeFormatted);

        // Buscar o serviço solicitado e calcular sua duração
        $requestedService = Services::find($request->service_id);
        $requestedDuration = (int)$requestedService->time; // duração em minutos
        $requestedEndTime = $requestedTime->copy()->addMinutes($requestedDuration);
        foreach ($existingAppointments as $appointment) {
            // Formatar e converter o horário do agendamento existente
            $appointmentStart = Carbon::parse($appointment->time)->format('H:i');
            $appointmentTime = Carbon::parse($appointmentStart);
            $appointmentDuration = (int)$appointment->service->time; // duração em minutos
            $appointmentEndTime = $appointmentTime->copy()->addMinutes($appointmentDuration);

            // Verifica se há sobreposição de horários
            if (
                // Novo agendamento começa durante um existente
                ($requestedTime >= $appointmentTime && $requestedTime < $appointmentEndTime) ||
                // Novo agendamento termina durante um existente
                ($requestedEndTime > $appointmentTime && $requestedEndTime <= $appointmentEndTime) ||
                // Novo agendamento engloba um existente
                ($requestedTime <= $appointmentTime && $requestedEndTime >= $appointmentEndTime)
            ) {
                $nextAvailable = $appointmentEndTime->format('H:i');
                throw new Exception(
                    "Horário ocupado. Próximo horário disponível: {$nextAvailable}",
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }
        }

        return true;
    }

    public function exportReport($request, $forExport = false)
    {
        $data = $this->list->with('professional:id,name', 'status:id,name', 'service')
            ->whereDate('date', '>=', $request->initial_date)
            ->whereDate('date', '<=', $request->final_date)
            ->where('establishment_id', $request->establishment_id)
            ->where('status_id', 3)
            ->orderBy('date');

        if ($request->filled('professional_id')) {
            $data->where('professional_id', $request->professional_id);
        }

        if ($request->filled('service_id')) {
            $data->where('service_id', $request->service_id);
        }

        $total = 0;
        // Clonar a query para calcular o total
        $query = clone $data;

        foreach ($query->get() as $value) {
            $total += $value->service->amount;
        }

        if ($forExport) {
            $response['data'] =  $data->get();
            $response['total_amount'] = number_format($total, 2, ',', '.');
            return $response;
        } else {
            // Obter os dados paginados
            $dataPaginated = $data->paginate($this->pageLimit);

            // Adicionar o total à resposta paginada
            $response = $dataPaginated->toArray();
            $response['total_amount'] = number_format($total, 2, ',', '.');
            return response()->json($response, Response::HTTP_OK);
        }
    }

    public function exportReportDownload($request)
    {
        $pdf = App::make('dompdf.wrapper');

        $query =  $this->exportReport($request, true);
        $view =  View::make('reports.financial', compact('query'))->render();
        $pdf->loadHTML(mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8'));
        return $pdf->stream('teste.pdf', array("Attachment" => false));
    }

    public function hystoricUser($request)
    {
        try {
            $data = $this->list->with('professional:id,name', 'status:id,name', 'service')
                ->whereDate('date', '>=', $request->start_date)
                ->whereDate('date', '<=', $request->end_date)
                ->where('establishment_id', $request->establishment_id)
                ->where('user_id', $request->user_id)
                ->where('status_id', 3)
                ->orderBy('date')
                ->get();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                "message" => 'Não foi possível buscar o histórico',
                "error" => [$e->getMessage()]
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
