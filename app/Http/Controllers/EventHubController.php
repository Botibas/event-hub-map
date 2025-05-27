<?php

namespace App\Http\Controllers;

use App\Services\EventHubService;

class EventHubController extends Controller {
    protected EventHubService $eventHubService;
    public function __construct(EventHubService $eventHubService) {
        $this->eventHubService = $eventHubService;
    }
    public function fetchEvent() {
        // Get the search query from the request
        $search = request()->query('query');

        // Use the service to fetch events
        $events = $this->eventHubService->fetchEvents($search);

        // Return the response
        if ($events) {
            return response()->json($events);
        } else {
            return response()->json(['error' => 'No events found'], 404);
        }
    }

    public function getNextUpcomingEvent()
    {
        $search = request()->query('query');

        $event = $this->eventHubService->getNextUpcomingEvent($search);

        if ($event) {
            return response()->json($event);
        }

        return response()->json(['error' => 'No upcoming events found'], 404);
    }

    public function getNextUpcomingEvents()
    {
        $search = request()->query('query', '');
        $limit = (int) request()->query('limit', 5);

        $events = $this->eventHubService->getNextUpcomingEvents($search, $limit);

        if (empty($events)) {
            return response()->json([
                'error' => 'No upcoming events found'
            ], 404);
        }

        return response()->json($events);
    }

}
