<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TicketService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = 'https://leadbook.ru/test-task-api/';
        $this->token = 'f72e02929b79c96daf9e336e0a5cdb8059e60685';

        // $this->apiUrl = config('app.api_url');
        // $this->token = config('app.api_token');
    }

    public function getShows()
    {
        return $this->sendRequest('GET', 'shows');
    }

    public function getEventsByShowId($showId)
    {
        return $this->sendRequest('GET', "shows/{$showId}/events");
    }

    public function getPlacesByEventId($eventId)
    {
        return $this->sendRequest('GET', "events/{$eventId}/places");
    }

    public function reservePlaces($eventId, $name, $places)
    {
        return $this->sendRequest('POST', "events/{$eventId}/reserve", [
            'name' => $name,
            'places' => $places
        ]);
    }

    protected function sendRequest($method, $endpoint, $data = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'token=' . $this->token,
        ])->{$method}($this->apiUrl . $endpoint, $data);

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => 'Failed to fetch data from API'];
    }
}