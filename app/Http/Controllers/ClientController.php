<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;

class ClientController extends Controller
{
    protected $client_service;
    public function __construct(ClientService $client_service){
        $this->client_service = $client_service;
    }

    public function index(Request $request)
    {
         return $this->client_service->index($request);
    }

    public function store(ClientRequest $request)
    {
        return $this->client_service->store($request);
    }

    public function show($id)
    {
        return $this->client_service->show($id);
    }

    public function update(ClientRequest $request, $id)
    {
        return $this->client_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->client_service->destroy($id);
    }

}
