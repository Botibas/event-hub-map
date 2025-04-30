<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EventHubService
{
    public function fetchEvents()
    {
        $token = config('eventhub.token');
        $url   = config('eventhub.url');

        $response = Http::withHeaders([
            'Content-type'   => 'application/json',
            'Accept'         => 'application/json',
            'EventHubToken'  => 'Bearer ' . $token,
        ])->get($url, [
            'query'      => '*:*',
            'startIndex' => 0,
            'endIndex'   => 9,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('EventHub API failed: ' . $response->body());
    }
}
