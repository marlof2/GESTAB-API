<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProfileAbility;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserService
{

    protected $user;
    protected $pageLimit;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->pageLimit = 20;
    }

    public function index($request)
    {
        $data = $this->user->with('profile')->orderBy('name');

        if ($request->filled('profile_id')) {
            $data->where('profile_id', $request->profile_id);
        }

        if ($request->filled('search')) {
            return response()->json($this->user::Filtro($request->search, $this->pageLimit));
        }
        if ($request->filled('limit')) {
            $data = ["data" => $this->user->with('profile')->orderBy('name')->get()];
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data = $data->paginate($this->pageLimit);
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function store($request)
    {
        $request['cpf'] = $this->removeCaracters($request->cpf);
        $request['phone'] = $this->removeCaracters($request->phone);

        $dataFrom = $request->all();
        try {
            $data = $this->user->create($dataFrom);
            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível cadastrar', "error" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }


    public function show($id)
    {
        $data = $this->user->with('profile')->find($id);

        if (!$data) {
            return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($data, Response::HTTP_OK);
    }

    public function update($request, $id)
    {
        $dataFrom = $request->all();
        try {
            $data = $this->user->whereId($id)->update($dataFrom);
            return response()->json(['message' => 'Atualizado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível alterar', "error" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function destroy($id)
    {
        $data = $this->user->find($id);
        if (!$data) {
            return response()->json(['message' => 'Não foi possível executar a ação', 'error' => ['Dados não encontrados']], Response::HTTP_NOT_FOUND);
        }
        try {
            $data->delete();
            return response()->json(['success' => 'Deletado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["message" => 'Não foi possível excluir', "error" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function login($request)
    {
        $request['cpf'] = preg_replace('/[^0-9]/', '', $request->cpf);
        $user = $this->user->where('cpf', $request->cpf)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario não encontrado na base de dados.'], Response::HTTP_NOT_FOUND);
        };

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Usuário ou Senha Inválido.'
            ], 406);
        }

        $abilities = [];
        foreach ($user->profile->abilities as  $ability) {
            array_push($abilities, $ability->slug);
        }

        $token = $user->createToken('AccessToken', $abilities)->plainTextToken;


        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function me($request): array
    {
        $profile_id = $request->user()->profile()->first()->id;

        $abilities =   ProfileAbility::select('abilities.slug as abilities')
            ->join('abilities', 'abilities.id', '=', 'profile_abilities.ability_id')
            ->where('profile_abilities.profile_id', '=', $profile_id)
            ->get();


        $abilitiesAux = $abilities->pluck('abilities');

        // $abilitiesAux = base64_encode(Crypt::encryptString($abilitiesAux));

        $data['abilities'] = $abilitiesAux;
        $data['user'] = $request->user()->with('profile')->whereId($request->user()->id)->first();

        return $data;
    }

    public function logout($request)
    {
        return $request->user()->tokens()->delete();
    }



    public function alterarSenhaUsuario($request)
    {

        try {
            $user = User::whereId($request->id)->first();

            if (!$user) {
                return response([
                    'message' => 'Usuário não encontrado.'
                ], 401);
            }


            if ($request->senhaNova != $request->confirmaSenhaNova) {
                return response([
                    'message' => 'A nova senha é diferente da confirmação de senha.'
                ], 401);
            }


            $user->password = Hash::make($request->senhaNova);
            $user->save();

            return response([
                'message' => 'Alterado com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 406);
        }
    }

    public function resetSenha($request)
    {

        try {
            $user = User::whereId($request->id)->first();

            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response([
                'message' => 'Alterado com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 406);
        }
    }

    public function removeCaracters($value)
    {
        return preg_replace('/\D/', '', $value);
    }

    public function establishments($id)
    {
        try {
            $user = $this->user->find($id);
            $establishments = $user->establishments;

            if (!$user) {
                return response()->json(['error' => 'Dados não encontrados'], Response::HTTP_NOT_FOUND);
            }
            return response()->json($establishments, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Não foi possível obter os estabelecimentos do usuário.",
                "error" => $e
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }


    public function listUsersByEstablishiment($request)
    {
        try {

            $data = $this->user->with('profile')->orderBy('name');
            if ($request->filled('profile_id')) {
                $data->where('profile_id', $request->profile_id);
            }

            if ($request->filled('search')) {
                return $data->where('name', 'LIKE', '%' . $request->search . '%')
                    ->OrWhere('cpf', 'LIKE', '%' . $request->search . '%')->paginate($this->pageLimit);
            }

            $result = $data->whereNotExists(function ($q) use ($request) {
                $q->select('user_id')
                    ->from('establishment_user as EU')
                    ->whereColumn('EU.user_id', 'users.id')
                    ->where('EU.establishment_id', $request->establishment_id);
            })->paginate($this->pageLimit);

            return response()->json($result, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Não foi possível obter os profissionais do estabelecimento.",
                "error" => $e
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }


}
