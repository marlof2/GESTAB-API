<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbacksRequest;
use App\Services\FeedbacksService;

class FeedbacksController extends Controller
{
    protected $feedbacks_service;
    public function __construct(FeedbacksService $feedbacks_service)
    {
        $this->feedbacks_service = $feedbacks_service;
    }

    public function index(Request $request)
    {
        return $this->feedbacks_service->index($request);
    }

    public function store(FeedbacksRequest $request)
    {
        return $this->feedbacks_service->store($request);
    }

    public function show($user_id)
    {
        return $this->feedbacks_service->show($user_id);
    }

    public function update(FeedbacksRequest $request, $id)
    {
        return $this->feedbacks_service->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->feedbacks_service->destroy($id);
    }
}
