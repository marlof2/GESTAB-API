<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbacksRequest;
use App\Services\CategoriesService;
use App\Services\FeedbacksService;

class CategoriesController extends Controller
{
    protected $category_service;
    public function __construct(CategoriesService $category_service)
    {
        $this->category_service = $category_service;
    }

    public function index(Request $request)
    {
        return $this->category_service->index($request);
    }

    // public function store(FeedbacksRequest $request)
    // {
    //     return $this->category_service->store($request);
    // }

    // public function show($id)
    // {
    //     return $this->category_service->show($id);
    // }

    // public function update(FeedbacksRequest $request, $id)
    // {
    //     return $this->category_service->update($request, $id);
    // }

    // public function destroy($id)
    // {
    //     return $this->category_service->destroy($id);
    // }
}
