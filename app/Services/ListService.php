<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Lista;
use App\Models\Services;
use Carbon\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class ListService
{
    protected $list;
    protected $pageLimit;

    public function __construct(Lista $list)
    {
        $this->list = $list;
        $this->pageLimit = 10;
    }
    public function index($request)
    {
        $data = $this->list->with('user:id,name,phone', 'professional:id,name', 'status:id,name', 'service')
            ->whereDate('date', '=', $request->date)
            ->whereIn('status_id', [1, 2])
            ->where('establishment_id', $request->establishment_id)
            ->where('professional_id', $request->professional_id)
            ->orderBy('date')
            ->orderBy('time');

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
                // $this->checkDuplicity($request);
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
                406
            );
        }
    }



    public function checkDisponibilityOfTime($request)
    {

        // Definir as condições para buscar o último agendamento
        $conditions = [
            // 'service_id' =>  $request->service_id,
            'establishment_id' =>  $request->establishment_id,
            'date' =>  $request->date,
            'professional_id' => $request['professional_id'],
        ];

        // Obter o horário do último agendamento
        $lastScheduleTime = $this->list->where($conditions)
            ->where('status_id', '!=', 3)
            ->where('status_id', '!=', 4)
            ->max('time');

        $lastScheduleId = $this->list->where($conditions)->max('id');

        if (!$lastScheduleTime && !$lastScheduleId) {
            return true;
        }

        $lastServiceId = $this->list->find($lastScheduleId)->service_id;

        $lastScheduleTime = substr($lastScheduleTime, 0, 5); // Formato 'HH:MM'

        $timeService = Services::where('establishment_id', $request->establishment_id)
            ->where('id', $lastServiceId)
            ->value('time');

        $timeService = substr($timeService, 0, 5); // Exemplo: '20:00'
        list($serviceMinutes, $serviceSeconds) = explode(':', $timeService);
        $serviceDurationInSeconds = ($serviceMinutes * 60) + $serviceSeconds;

        // Horário proposto para o novo agendamento
        $currentTime = $request->time; // Formato 'HH:MM'

        $lastScheduleTimestamp = strtotime($lastScheduleTime);

        // Calcular o horário de término do último agendamento
        $endTimeLastAppointmentTimestamp = $lastScheduleTimestamp + $serviceDurationInSeconds;

        // Converter o horário proposto para timestamp
        $currentTimestamp = strtotime($currentTime);

        // Verificar se o novo horário é posterior ao término do último agendamento
        if ($currentTimestamp >= $endTimeLastAppointmentTimestamp) {
            return true;
        } else {
            $nextAvailableTime = date('H:i', $endTimeLastAppointmentTimestamp);
            throw new Exception(
                "Não é possível agendar neste horário. Próximo horário disponível: " . $nextAvailableTime,
                406
            );
        }
    }


    public function checkDuplicity($data)
    {

        // Certifique-se de que os valores estão no formato correto
        $conditions = [
            'date' => $data['date'],
            'time' => $data['time'] . ':00',
            'establishment_id' => $data['establishment_id'],
            'status_id' => 1,
            'professional_id' => $data['professional_id'],
            // 'service_id' => $data['service_id'],
        ];

        $count = $this->list->where($conditions)->orWhere('status_id', 2)->count();


        if ($count > 0) {
            throw new Exception(
                "Já existe um agendamento marcado para este dia e essa hora.",
                406
            );
        }
    }

    public function exportReport($request)
    {
        $data = $this->list->with('professional:id,name', 'status:id,name', 'service')
            ->whereDate('date', '>=', $request->initial_date)
            ->whereDate('date', '<=', $request->final_date)
            ->where('establishment_id', $request->establishment_id)
            ->where('status_id', 3)
            ->orderBy('date');

        $total = 0;
        // Clonar a query para calcular o total
        $query = clone $data;

        foreach ($query->get() as $value) {
            $total += $value->service->amount;
        }

        if ($request->filled('professional_id')) {
            $data->where('professional_id', $request->professional_id);
        }

        if ($request->filled('service_id')) {
            $data->where('service_id', $request->service_id);
        }

        // Obter os dados paginados
        $dataPaginated = $data->paginate($this->pageLimit);

        // Adicionar o total à resposta paginada
        $response = $dataPaginated->toArray();
        $response['total_amount'] = number_format($total, 2, ',', '.');


        return response()->json($response, Response::HTTP_OK);
    }

    public function exportReportDownload($request)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }
}
