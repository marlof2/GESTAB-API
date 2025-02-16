<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\BlockCalendarRequest;
use App\Services\BlockCalendarService;

class BlockCalendarController extends Controller
{
    protected $block_calendar_service;
    public function __construct(BlockCalendarService $block_calendar_service){
        $this->block_calendar_service = $block_calendar_service;
    }

    public function index(Request $request)
    {
         return $this->block_calendar_service->index($request);
    }

    public function getBlockCalendarByEstablishmentAndUser(Request $request)
    {
        return $this->block_calendar_service->getBlockCalendarByEstablishmentAndUser($request);
    }

    public function store(BlockCalendarRequest $request)
    {
        return $this->block_calendar_service->store($request);
    }

    public function show($id)
    {
        return $this->block_calendar_service->show($id);
    }

    public function update(BlockCalendarRequest $request, $id)
    {
        return $this->block_calendar_service->update($request,$id);
    }

    public function destroy($id)
    {
        return $this->block_calendar_service->destroy($id);
    }

}
