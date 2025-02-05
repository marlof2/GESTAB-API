<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\BlockCalendar;

class BlockCalendarService
{
    protected $blockcalendar;
    protected $pageLimit;

    public function __construct(BlockCalendar $blockcalendar)
    {
        $this->blockcalendar = $blockcalendar;
        $this->pageLimit = 10;
    }
    public function index($request)
    {
        $data = $this->blockcalendar->orderBy('date');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $data = $data->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('limit')) {
            $data = ["data" => $this->blockcalendar->get()];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $page_limit = $request->filled('per_page') ? $request->per_page : config($this->pageLimit);
            $data = $data->paginate($page_limit);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    private function checkExistingBlock(array $block): ?BlockCalendar
    {
        return $this->blockcalendar
            ->where('date', $block['date'])
            ->where('user_id', $block['user_id'])
            ->where('establishment_id', $block['establishment_id'])
            ->first();
    }


    private function handleBlockCreation(array $blocks)
    {
        $lastCreatedBlock = null;

        foreach ($blocks as $block) {
            if ($this->checkExistingBlock($block)) {
                return response()->json([
                    "message" => "Erro ao cadastrar",
                    "error" => ['Já existe um bloqueio cadastrado para esta data']
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $lastCreatedBlock = $this->blockcalendar->create($block);
        }

        return response()->json($lastCreatedBlock, Response::HTTP_CREATED);
    }

    public function store($request)
    {
        try {
            return $this->handleBlockCreation($request->blocks);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Não foi possível cadastrar",
                "error" => $e->getMessage()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function show($id)
    {
        $data = $this->blockcalendar->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data, Response::HTTP_OK);
    }
    public function update($request, $id)
    {
        $data = $this->blockcalendar->find($id);
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
        $data = $this->blockcalendar->find($id);
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
}
