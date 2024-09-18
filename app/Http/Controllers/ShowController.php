<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use Carbon\Carbon;

class ShowController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $shows = $this->ticketService->getShows();
        return view('shows.index', compact('shows'));
    }

    public function show($showId)
    {
        $events = $this->ticketService->getEventsByShowId($showId);

        // Переконайтесь, що `date` є екземпляром Carbon
        foreach ($events['response'] as &$event) {
            $event['date'] = Carbon::parse($event['date']);
        }

        return view('shows.show', compact('events'));
    }
}
