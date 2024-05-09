<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentProfessionalRequest;
use App\Services\EstablishmentProfessionalService;

class EstablishmentProfessionalController extends Controller
{
    protected $establishment_professional_service;
    public function __construct(EstablishmentProfessionalService $establishment_professional_service){
        $this->establishment_professional_service = $establishment_professional_service;
    }

    public function index(Request $request)
    {
         return $this->establishment_professional_service->index($request);
    }

    public function store(EstablishmentProfessionalRequest $request)
    {
        return $this->establishment_professional_service->store($request);
    }

    public function show($id)
    {
        return $this->establishment_professional_service->show($id);
    }

    public function update(EstablishmentProfessionalRequest $request, $id)
    {
        return $this->establishment_professional_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->establishment_professional_service->destroy($id);
    }

}
