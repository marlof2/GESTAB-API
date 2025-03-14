<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Establishment;
use App\Models\EstablishmentUser;
use App\Models\User;

class EstablishmentService
{
    protected $establishment;
    protected $pageLimit;

    public function __construct(Establishment $establishment)
    {
        $this->establishment = $establishment;
        $this->pageLimit = 20;
    }
    public function index($request)
    {
        $data = $this->establishment->with('tipoPessoa', 'responsible')->orderBy('name');

        if ($request->filled('search')) {
            return response()->json($this->establishment::Filtro($request->search, $this->pageLimit));
        }

        $data = $data->withTrashed()->paginate($this->pageLimit);
        return response()->json($data, Response::HTTP_OK);
    }
    public function store($request)
    {
        $request['cnpj'] = $this->removeCaracters($request->cnpj);
        $request['cpf'] = $this->removeCaracters($request->cpf);
        $request['phone'] = $this->removeCaracters($request->phone);

        $dataFrom = $request->all();
        try {
            $data = $this->establishment->create($dataFrom);
            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function show($id)
    {
        $data = $this->establishment->with('payment', 'payments')->find($id);
        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($data, Response::HTTP_OK);
    }
    public function update($request, $id)
    {
        $data = $this->establishment->find($id);

        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }

        $request['cnpj'] = $this->removeCaracters($request->cnpj);
        $request['cpf'] = $this->removeCaracters($request->cpf);
        $request['phone'] = $this->removeCaracters($request->phone);
        try {
            $data->update($request->all());
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível atualizar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function destroy($id)
    {
        $data = $this->establishment->find($id);
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

    public function restore($id)
    {
        $establishment = Establishment::withTrashed()->find($id);
        if (!$establishment) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }
        try {
            $establishment->restore();
            return response()->json(['success' => 'Restaurado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível restaurar', "error" => $e], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function removeCaracters($value)
    {
        return preg_replace('/\D/', '', $value);
    }

    public function listEstablishimentByUser($request)
    {
        try {

            $data = $this->establishment->with('responsible')->orderBy('name');

            if ($request->filled('search')) {
                $data->where('name', 'LIKE', '%' . $request->search . '%');
            }

            $result = $data->whereNotExists(function ($q) use ($request) {
                $q->select('establishment_id', 'created_by_functionality')
                    ->from('establishment_user as EU')
                    ->whereColumn('EU.establishment_id', 'establishment.id')
                    ->where('EU.user_id', $request->user_id)
                    ->where('EU.created_by_functionality', 'ME');
            })->paginate($this->pageLimit);

            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Não foi possível obter os profissionais do estabelecimento.",
                "error" => $e
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function establishimentByResponsible($id)
    {
        try {

            $data = $this->establishment->whereResponsibleId($id)->orderBy('name')->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Não foi possível obter os estabelecimento.",
                "error" => $e
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function checkPaymentActive($establishment_id, $user_id)
    {
        $establishment = $this->establishment->with('payment')->find($establishment_id);

        $user = User::find($user_id);

        $existsProfessionalInPlan = EstablishmentUser::where('establishment_id', $establishment_id)
            ->where('user_id', $user_id)
            ->where('created_by_functionality', 'EP')
            ->where('have_plan_establishment', 1)
            ->exists();

        // caso entre é pq é um profissional e está em um plano
        if ($existsProfessionalInPlan && $user->profile_id == 3) {
            if (isset($establishment->payment)) { //profissional
                return response()->json([
                    'isActive' => $establishment->payment->isActive(),
                    'userInPlan' => true,
                    'remove_ads_client' => $establishment->payment->remove_ads_client ? true : false
                ], Response::HTTP_OK);
            }
        } else if (!$existsProfessionalInPlan && $user->profile_id == 3) {
            return response()->json([
                'isActive' => isset($establishment->payment) ? $establishment->payment->isActive() : false,
                'userInPlan' => false,
                'remove_ads_client' => isset($establishment->payment) ? $establishment->payment->remove_ads_client : 0
            ], Response::HTTP_OK);
        }


        // caso não encontrou um profissional é um cliente
        if (!$existsProfessionalInPlan && $user->profile_id == 2) {
            if (isset($establishment->payment) && $establishment->payment->remove_ads_client) { //profissional
                return response()->json([
                    'isActive' => isset($establishment->payment) ? $establishment->payment->isActive() : false,
                    'userInPlan' => true,
                    'remove_ads_client' => true
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'isActive' => isset($establishment->payment) ? $establishment->payment->isActive() : false,
                    'userInPlan' => false,
                    'remove_ads_client' => false
                ], Response::HTTP_OK);
            }
        }

        if ($user->profile_id == 1) {

            return response()->json([
                'isActive' => true,
                'userInPlan' => true,
                'remove_ads_client' => true
            ], Response::HTTP_OK);
        }
    }
}
