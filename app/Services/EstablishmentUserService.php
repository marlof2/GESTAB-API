<?php

namespace App\Services;

use App\Models\Establishment;
use Illuminate\Http\Response;
use App\Models\EstablishmentUser;
use App\Models\User;

class EstablishmentUserService
{
    protected $establishment_user;
    protected $user;
    protected $pageLimit;

    public function __construct(EstablishmentUser $establishment_user, User $user)
    {
        $this->establishment_user = $establishment_user;
        $this->user = $user;
        $this->pageLimit = 20;
    }
    public function index($request, $id)
    {
        $data = $this->establishment_user->where('establishment_id', $id)
            ->select('users.id as user_id', 'establishment_user.*')
            ->join('users', 'users.id', '=', 'establishment_user.user_id')
            ->with("user")
            ->orderBy('users.name');

        if ($request->filled('search')) {
            return  $data->whereRelation('user', 'name', 'LIKE', '%' . $request->search . '%')
                ->paginate($this->pageLimit);
        }

        if ($request->filled('limit')) {
            $data = ["data" => $this->establishment_user->get()];
            return response()->json($data, Response::HTTP_OK);
        }

        $data = $data->paginate($this->pageLimit);
        return response()->json($data, Response::HTTP_OK);
    }
    public function establishimentByUser($request, $id)
    {
        $data = $this->establishment_user
            ->where('user_id', $id)
            ->select('establishment.id as establishment_id', 'establishment_user.*')
            ->join('establishment', 'establishment.id', '=', 'establishment_user.establishment_id')
            ->with("establishment_user")
            ->orderBy('establishment.name');

        if ($request->filled('search')) {
            return  $data->whereRelation('establishment_user', 'name', 'LIKE', '%' . $request->search . '%')
                ->paginate($this->pageLimit);
        }

        if ($request->filled('limit')) {
            $data = ["data" => $this->establishment_user->get()];
            return response()->json($data, Response::HTTP_OK);
        }

        $data = $data->paginate($this->pageLimit);
        return response()->json($data, Response::HTTP_OK);
    }
    public function associationProfessionalAndEstablishment($request)
    {
        try {
            $user_id = $request["user_id"];
            $establishment_id = $request->establishment_id;

            foreach ($user_id as $key => $id) {

                $this->establishment_user->create(["establishment_id" => $establishment_id, "user_id" => $id]);
            }

            return response()->json(["message" => "Profissionais vinculados com sucesso."], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function associationClientAndEstablishment($request)
    {
        try {
            $establishment_ids = $request["establishment_ids"];
            $user_id = $request->user_id;

            foreach ($establishment_ids as $key => $id) {
                $this->establishment_user->create(["establishment_id" => $id, "user_id" => $user_id]);
            }

            return response()->json(["message" => "Estabelecimentos vinculados com sucesso."], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function show($user_id)
    {
        $data = $this->establishment_user->with("establishment:id,name,cnpj,cpf,phone")->where('user_id', $user_id)->get();
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(["data" => $data], Response::HTTP_OK);
    }
    public function update($request, $id)
    {
        $data = $this->establishment_user->find($id);
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
        try {

            $establishment_user = $this->establishment_user->find($id);

            if (!$establishment_user) {
                return response()->json([
                    "message" => "O estabelecimento informado não existe."
                ], Response::HTTP_NOT_FOUND);
            }

            $establishment_user->delete();

            return response()->json(["message" => "Estabelecimento desvinculado com sucesso."], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível desvincular', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
