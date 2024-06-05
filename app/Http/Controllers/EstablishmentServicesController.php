<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentServicesRequest;
use App\Services\EstablishmentServicesService;

class EstablishmentServicesController extends Controller
{
    protected $establishment_services_service;
    public function __construct(EstablishmentServicesService $establishment_services_service){
        $this->establishment_services_service = $establishment_services_service;
    }

    public function index(Request $request)
    {
         return $this->establishment_services_service->index($request);
    }

    public function store(EstablishmentServicesRequest $request)
    {
        return $this->establishment_services_service->store($request);
    }

    public function show($establishment_id)
    {
        return $this->establishment_services_service->show($establishment_id);
    }

    public function update(EstablishmentServicesRequest $request, $id)
    {
        return $this->establishment_services_service->update($request,$id);
    }

    public function destroy(EstablishmentServicesRequest $request)
    {
        return $this->establishment_services_service->destroy($request);
    }

}
