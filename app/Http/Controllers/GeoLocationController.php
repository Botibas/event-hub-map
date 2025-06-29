<?php

namespace App\Http\Controllers;

use App\Services\GeoLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoLocationController extends Controller
{
    protected GeoLocationService $geoLocationService;

    // Inject the GeoLocationService into the controller
    public function __construct(GeoLocationService $geoLocationService)
    {
        $this->geoLocationService = $geoLocationService;
    }

    public function __invoke(Request $request)
    {
        // Get the location query from the request
        $location = $request->query('location');

        // Use the service to fetch the coordinates
        $coordinates = $this->geoLocationService->getCoordinates($location);

        // Return the response
        if ($coordinates) {
            return $coordinates;
        } else {
            return response()->json(['error' => 'Location not found'], 404);
        }
    }

    public function getMultipleCoordinates(Request $request): JsonResponse
    {
        // Get the locations query from the request
        $locations = $request->locations;

        // Use the service to fetch the coordinates
        $coordinates = $this->geoLocationService->getMultipleCoordinates($locations);

        // Return the response
        if ($coordinates) {
            return response()->json($coordinates);
        } else {
            return response()->json(['error' => 'Location not found'], 404);
        }
    }
}

