<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profile_service;
    public function __construct(ProfileService $profile_service){
        $this->profile_service = $profile_service;
    }

    public function index(Request $request)
    {
         return $this->profile_service->index($request);
    }

    public function store(ProfileRequest $request)
    {
        return $this->profile_service->store($request);
    }

    public function show($id)
    {
        return $this->profile_service->show($id);
    }

    public function update(ProfileRequest $request, $id)
    {
        return $this->profile_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->profile_service->destroy($id);
    }

}
