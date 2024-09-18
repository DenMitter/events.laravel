<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function show($eventId)
    {
        $places = $this->ticketService->getPlacesByEventId($eventId);
        return view('events.show', compact('places'));
    }

    public function reserve(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string',
            'places' => 'required|array',
        ]);

        $reservation = $this->ticketService->reservePlaces($eventId, $request->name, $request->places);

        return view('events.confirmation', compact('reservation'));
    }
}