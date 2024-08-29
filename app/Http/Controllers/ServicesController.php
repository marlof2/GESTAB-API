<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ServicesRequest;
use App\Services\ServicesService;

class ServicesController extends Controller
{
    protected $services_service;
    public function __construct(ServicesService $services_service)
    {
        $this->services_service = $services_service;
    }

    public function index(Request $request)
    {
        return $this->services_service->index($request);
    }

    public function store(ServicesRequest $request)
    {
        return $this->services_service->store($request);
    }

    public function show($id)
    {
        return $this->services_service->show($id);
    }

    public function update(ServicesRequest $request, $id)
    {
        return $this->services_service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services_service->destroy($id);
    }

    public function comboServicesByEstablishment($id)
    {
        return $this->services_service->comboServicesByEstablishment($id);
    }
}
