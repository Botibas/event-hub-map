<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
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
        $events = $this->getNextUpcomingEvents($search, null, null, 1);
        return $events[0] ?? null;
    }

    public function getNextUpcomingEvents(string $search = null, $start = null, $end = null, int $limit = null): array
    {
        if ($search === null) {
            $search = '';
        }
        $data = $this->fetchEvents($search);

        if (empty($data['collection'])) {
            return [];
        }

        if ($start) $start = Carbon::parse($start);
        if ($end) $end = Carbon::parse($end);

        return collect($data['collection'])
            ->filter(function ($event) use ($start, $end) {
                if (empty($event['schedule']['start'])) {
                    return false;
                }
                $eventStart = Carbon::parse($event['schedule']['start']);

                if ($start || $end) {
                    if ($start && $eventStart->lessThanOrEqualTo($start)) {
                        return false;
                    }
                    if ($end && $eventStart->greaterThanOrEqualTo($end)) {
                        return false;
                    }

                    return true;
                }

                return $eventStart->isFuture();

            })
            ->sortBy(function ($event) {
                return Carbon::parse($event['schedule']['start']);
            })
            ->take($limit)
            ->values()
            ->all();
    }


}
