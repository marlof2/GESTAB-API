<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\TypeOfUserRequest;
use App\Services\TypeOfUserService;

class TypeOfUserController extends Controller
{
    protected $type_of_user_service;
    public function __construct(TypeOfUserService $type_of_user_service){
        $this->type_of_user_service = $type_of_user_service;
    }

    public function index(Request $request)
    {
         return $this->type_of_user_service->index($request);
    }

    public function store(TypeOfUserRequest $request)
    {
        return $this->type_of_user_service->store($request);
    }

    public function show($id)
    {
        return $this->type_of_user_service->show($id);
    }

    public function update(TypeOfUserRequest $request, $id)
    {
        return $this->type_of_user_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->type_of_user_service->destroy($id);
    }

}
