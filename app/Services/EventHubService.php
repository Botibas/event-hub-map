<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class EventHubService
{
    /**
     * @throws ConnectionException
     */
    public function fetchEvents(string $search = '')
    {
        $token = config('eventhub.token');
        $url   = config('eventhub.url');

        // Hier: immer query= senden, auch wenn leer
        $query = '?query=' . urlencode('"' . $search . '"');

        $response = Http::withHeaders([
            'Content-type'   => 'application/json',
            'Accept'         => 'application/json',
            'EventHubToken'  => 'Bearer ' . $token,
        ])->get($url . $query);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('EventHub API failed: ' . $response->body());
    }


    public function getNextUpcomingEvent(string $search = null): ?array
    {
        $events = $this->getNextUpcomingEvents($search, 1);
        return $events[0] ?? null;
    }

    public function getNextUpcomingEvents(string $search = null, int $limit = 5): array
    {
        $data = $this->fetchEvents($search);

        if (empty($data['collection'])) {
            return [];
        }

        $now = now();

        return collect($data['collection'])
            ->filter(function ($event) use ($now) {
                if (empty($event['schedule']['start'])) {
                    return false;
                }
                $start = \Illuminate\Support\Carbon::parse($event['schedule']['start']);
                return $start->isFuture();
            })
            ->sortBy(function ($event) {
                return \Illuminate\Support\Carbon::parse($event['schedule']['start']);
            })
            ->take($limit)
            ->values()
            ->all();
    }


}
