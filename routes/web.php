<?php

use App\Http\Controllers\GeoLocationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('map', function (\App\Services\EventHubService  $eventHubService) {
//    $events = $eventHubService->fetchEvents();
//    dd($events); // dump results for testing

    return Inertia::render('MapPage');
})->middleware(['auth', 'verified'])->name('map');

Route::get('/geocode', GeoLocationController::class)->middleware(['auth', 'verified'])->name('geocode');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
