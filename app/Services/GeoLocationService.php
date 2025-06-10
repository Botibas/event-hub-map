<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoLocationService
{
    protected $apiKey;

    // Constructor to get the API key from environment
    public function __construct()
    {
        // Access the API key from the config file
        $this->apiKey = config('geolocation.api_key');
    }

    // Function to get coordinates for a location
    public function getCoordinates($location): ?array {
        // Make the API request
        $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
            'q' => $location,
            'key' => $this->apiKey,
            'language' => 'en',
            'no_annotations' => 1,
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            // Get the first result from the response
            $data = $response->json();

            // If there are results, return the coordinates
            if (isset($data['results'][0])) {
                $geometry = $data['results'][0]['geometry'];
                return [
                    'latitude' => $geometry['lat'],
                    'longitude' => $geometry['lng'],
                    'formatted_address' => $data['results'][0]['formatted'],
                ];
            }
        }

        // Return null if there are no results or an error occurs
        return null;
    }

    // Function to get coordinates for multiple location
    public function getMultipleCoordinates($locations): ?array {
        $results = [];
        $cache = [];
        $skipped = 0;

        foreach ($locations as $location) {
            // Check if a similar location has already been processed
            $matched = null;
            foreach ($cache as $cachedLocation => $cachedResult) {
                // You can adjust the similarity threshold as needed (0 = exact, 1 = totally different)
                similar_text(strtolower($location), strtolower($cachedLocation), $percent);
                if ($percent > 85) { // 85% similarity threshold
                    $matched = $cachedResult;
                    break;
                }
            }

            if ($matched) {
                $results[] = $matched;
                $skipped++;
                continue;
            }

            // Make the API request
            $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
                'q' => $location,
                'key' => $this->apiKey,
                'language' => 'en',
                'no_annotations' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['results'][0])) {
                    $geometry = $data['results'][0]['geometry'];
                    $result = [
                        'latitude' => $geometry['lat'],
                        'longitude' => $geometry['lng'],
                        'formatted_address' => $data['results'][0]['formatted'],
                    ];

                    // Save to both results and cache
                    $results[] = $result;
                    $cache[$location] = $result;
                }
            }
        }


        return $results;
    }
}

