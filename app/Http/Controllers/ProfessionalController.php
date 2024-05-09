<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfessionalRequest;
use App\Services\ProfessionalService;

class ProfessionalController extends Controller
{
    protected $professional_service;
    public function __construct(ProfessionalService $professional_service){
        $this->professional_service = $professional_service;
    }

    public function index(Request $request)
    {
         return $this->professional_service->index($request);
    }

    public function store(ProfessionalRequest $request)
    {
        return $this->professional_service->store($request);
    }

    public function show($id)
    {
        return $this->professional_service->show($id);
    }

    public function update(ProfessionalRequest $request, $id)
    {
        return $this->professional_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->professional_service->destroy($id);
    }

}
