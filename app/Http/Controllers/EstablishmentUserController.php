<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentUserRequest;
use App\Services\EstablishmentUserService;

class EstablishmentUserController extends Controller
{
    protected $establishment_user_service;
    public function __construct(EstablishmentUserService $establishment_user_service){
        $this->establishment_user_service = $establishment_user_service;
    }

    public function index(Request $request, $id)
    {
         return $this->establishment_user_service->index($request, $id);
    }

    public function establishimentByUser(Request $request, $id)
    {
         return $this->establishment_user_service->establishimentByUser($request, $id);
    }

    public function associationProfessionalAndEstablishment(EstablishmentUserRequest $request)
    {
        return $this->establishment_user_service->associationProfessionalAndEstablishment($request);
    }
    public function associationClientAndEstablishment(EstablishmentUserRequest $request)
    {
        return $this->establishment_user_service->associationClientAndEstablishment($request);
    }

    public function show($user_id)
    {
        return $this->establishment_user_service->show($user_id);
    }

    public function update(EstablishmentUserRequest $request, $id)
    {
        return $this->establishment_user_service->update($request,$id);
    }


    public function destroy($id)
    {
        return $this->establishment_user_service->destroy($id);
    }

    public function comboEstablishimentsById($id)
    {
        return $this->establishment_user_service->comboEstablishimentsById($id);
    }
    public function comboProfessionalByEstablishment($id)
    {
        return $this->establishment_user_service->comboProfessionalByEstablishment($id);
    }
    public function comboUserByEstablishiment($id)
    {
        return $this->establishment_user_service->comboUserByEstablishiment($id);
    }
}
