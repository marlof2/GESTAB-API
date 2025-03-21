<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentRequest;
use App\Services\EstablishmentService;

class EstablishmentController extends Controller
{
    protected $establishment_service;
    public function __construct(EstablishmentService $establishment_service)
    {
        $this->establishment_service = $establishment_service;
    }

    public function index(Request $request)
    {
        return $this->establishment_service->index($request);
    }

    public function listEstablishimentByUser(Request $request)
    {
        return $this->establishment_service->listEstablishimentByUser($request);
    }

    public function store(EstablishmentRequest $request)
    {
        return $this->establishment_service->store($request);
    }

    public function show($id)
    {
        return $this->establishment_service->show($id);
    }

    public function update(EstablishmentRequest $request, $id)
    {
        return $this->establishment_service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->establishment_service->destroy($id);
    }

    public function restore($id)
    {
        return $this->establishment_service->restore($id);
    }

    public function establishimentByResponsible($id)
    {
        return $this->establishment_service->establishimentByResponsible($id);
    }

    public function checkPaymentActive($establishment_id, $user_id)
    {
        return $this->establishment_service->checkPaymentActive($establishment_id, $user_id);
    }
}
