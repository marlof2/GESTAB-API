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

    public function index(Request $request)
    {
         return $this->establishment_user_service->index($request);
    }

    public function store(EstablishmentUserRequest $request)
    {
        return $this->establishment_user_service->store($request);
    }

    public function show($id)
    {
        return $this->establishment_user_service->show($id);
    }

    public function update(EstablishmentUserRequest $request, $id)
    {
        return $this->establishment_user_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->establishment_user_service->destroy($id);
    }

}
