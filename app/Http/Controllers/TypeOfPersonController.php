<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\TypeOfPersonRequest;
use App\Services\TypeOfPersonService;

class TypeOfPersonController extends Controller
{
    protected $type_of_person_service;
    public function __construct(TypeOfPersonService $type_of_person_service){
        $this->type_of_person_service = $type_of_person_service;
    }

    public function index(Request $request)
    {
         return $this->type_of_person_service->index($request);
    }

    public function store(TypeOfPersonRequest $request)
    {
        return $this->type_of_person_service->store($request);
    }

    public function show($id)
    {
        return $this->type_of_person_service->show($id);
    }

    public function update(TypeOfPersonRequest $request, $id)
    {
        return $this->type_of_person_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->type_of_person_service->destroy($id);
    }

}
