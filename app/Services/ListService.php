<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Lista;
use Carbon\Carbon;
use Exception;

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
        $dataFrom = $request->all();
        $dataFrom['status_id'] = 2;

        try {
            if ($request->typeSchedule == "HM") {
                // $count = $this->checkDuplicity($request);

                // if ($count > 0) {
                //     throw new Exception(
                //         "Você já está agendado no dia " . Carbon::parse($request->date)->format('d/m/Y') .
                //             " no horário das " . $request->time,
                //         406
                //     );
                // }

                // Verificação para impedir agendamento no passado
                $timezone = 'America/Sao_Paulo'; // Ajuste conforme necessário
                $requestedDateTime = Carbon::parse($request->date . ' ' . $request->time, $timezone);
                $currentDateTime = Carbon::now($timezone);
                if ($currentDateTime->gt($requestedDateTime)) {
                    // A data/hora atual é maior que a data/hora solicitada
                    // Não permite salvar
                    throw new Exception(
                        "Não é possível agendar para o dia " . Carbon::parse($request->date)->format('d/m/Y') .
                            " às " . $request->time . " porque este horário já passou.",
                        406
                    );
                }
            }

            $data = $this->list->create($dataFrom);
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

    public function checkDuplicity($data)
    {
        // Certifique-se de que os valores estão no formato correto
        $conditions = [
            'date' => $data['date'],
            'time' => $data['time'] . ':00',
            'establishment_id' => $data['establishment_id'],
            'user_id' => $data['user_id'],
            'professional_id' => $data['professional_id'],
            'service_id' => $data['service_id'],
        ];

        $query = $this->list->where($conditions)->orWhere('status_id', 1)->orWhere('status_id', 2)->get();
        dd($query);

        return $query;
    }
}
