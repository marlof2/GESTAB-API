<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\EstablishmentServices;

class EstablishmentServicesService
{
    protected $establishmentservices;
    protected $pageLimit;

    public function __construct(EstablishmentServices $establishmentservices)
    {
        $this->establishmentservices = $establishmentservices;
        $this->pageLimit = 10;
    }
    public function index($request)
    {
        $data = $this->establishmentservices->with(["establishment", "service"]);

        if ($request->filled('search')) {
            $data = $data->with(["establishment", "service"])->whereHas('service', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            })
            ->orWhereHas('establishment', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }
        if ($request->filled('limit')) {
            $data = ["data" => $this->establishmentservices->with(["establishment", "service"])->get()];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $page_limit = $request->filled('per_page') ? $request->per_page : config($this->pageLimit);
            $data = $data->paginate($page_limit);
        }
        return response()->json($data, Response::HTTP_OK);
    }
    public function store($request)
    {
        try {
            $service_id = $request["service_id"];
            $establishment_id = $request->establishment_id;

            foreach ($service_id as $key => $id) {

                $this->establishmentservices->create(["establishment_id" => $establishment_id, "service_id" => $id]);
            }

            return response()->json(["message" => "Serviços vinculados com sucesso."], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }

        $dataFrom = $request->all();
        try {
            $data = $this->establishmentservices->create($dataFrom);
            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function show($establishment_id)
    {
        $data = $this->establishmentservices->with("service")->where('establishment_id', $establishment_id)->get();
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(["data" => $data], Response::HTTP_OK);
    }
    public function update($request, $id)
    {
        $data = $this->establishmentservices->find($id);
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

    public function destroy($request)
    {
        $service_id = $request["service_id"];
            $establishment_id = $request->establishment_id;

            $establishment = $this->establishmentservices->where('establishment_id', $establishment_id)->first();

            if (!$establishment) {
                return response()->json([
                    "message" => "O estabelecimento informado não existe."
                ], Response::HTTP_NOT_FOUND);
            }

            foreach ($service_id as $key => $id) {
                $this->establishmentservices->where([
                    'establishment_id' => $establishment_id,
                    'service_id' => $id
                ])->delete();
            }
            return response()->json(["message" => "Serviços desvinculados com sucesso."], Response::HTTP_OK);
    }
}
