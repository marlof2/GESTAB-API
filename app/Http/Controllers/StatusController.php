<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\StatusRequest;
use App\Services\StatusService;

class StatusController extends Controller
{
    protected $status_service;
    public function __construct(StatusService $status_service){
        $this->status_service = $status_service;
    }

    public function index(Request $request)
    {
         return $this->status_service->index($request);
    }

    public function store(StatusRequest $request)
    {
        return $this->status_service->store($request);
    }

    public function show($id)
    {
        return $this->status_service->show($id);
    }

    public function update(StatusRequest $request, $id)
    {
        return $this->status_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->status_service->destroy($id);
    }

}
