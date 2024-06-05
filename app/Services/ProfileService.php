<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Profile;
use App\Models\ProfileAbility;

class ProfileService
{
    protected $profile;
    protected $pageLimit;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->pageLimit = 10;
    }

    public function index($request)
    {
        $data = $this->profile->orderBy('name');
        if ($request->filled('search')) {
            $data = $data->where('name', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filled('limit')) {
            $data = ["data" => $this->profile->get()];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $page_limit = $request->filled('per_page') ? $request->per_page : config($this->pageLimit);
            $data = $data->paginate($page_limit);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function store($request)
    {
        $dataFrom = $request->all();
        try {
            $data = $this->profile->create($dataFrom);
            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function show($id)
    {
        $data = $this->profile->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function update($request, $id)
    {
        $data = $this->profile->find($id);
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
        $data = $this->profile->find($id);
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

    public function getAbilities(int $id)
    {
        $abilities = $this->profile->with('abilities')->findOrFail($id);
        $abilities = $abilities->abilities;
        $idAbilitis = $abilities->pluck('id')->toArray();
        return ['abilities' => $idAbilitis];
    }

    public function addPermissions($request)
    {

        try {

            $profileAbility = new ProfileAbility();
            $dataFrom = $request->all();

            $count =  $profileAbility::where('profile_id', $request->profile_id)->count();

            if ($count > 0) {
                $profileAbility::where('profile_id', $request->profile_id)->delete();
            }

            foreach ($dataFrom['permissao'] as $value) {
                $result = $profileAbility::create([
                    'ability_id' => $value,
                    'profile_id' => $request->profile_id,
                ]);
            }

            return response()->json(['message' => 'Cadastrado com sucesso'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
