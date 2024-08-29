<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ListRequest;
use App\Services\ListService;

class ListController extends Controller
{
    protected $list_service;
    public function __construct(ListService $list_service)
    {
        $this->list_service = $list_service;
    }

    public function index(Request $request)
    {
        return $this->list_service->index($request);
    }

    public function store(ListRequest $request)
    {
        return $this->list_service->store($request);
    }

    public function show($id)
    {
        return $this->list_service->show($id);
    }

    public function update(ListRequest $request, $id)
    {
        return $this->list_service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->list_service->destroy($id);
    }

    public function statusEmAtendimento($id)
    {
        return $this->list_service->statusEmAtendimento($id);
    }

    public function statusAguardandoAtendimento($id)
    {
        return $this->list_service->statusAguardandoAtendimento($id);
    }

    public function statusConcluido($id)
    {
        return $this->list_service->statusConcluido($id);
    }

    public function statusDesistiu($id)
    {
        return $this->list_service->statusDesistiu($id);
    }
}
