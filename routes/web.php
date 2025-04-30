<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('map', function (\App\Services\EventHubService  $eventHubService) {
//    $events = $eventHubService->fetchEvents(); // or whatever method you created
//    dd($events); // dump results for testing

    return Inertia::render('Map');
})->middleware(['auth', 'verified'])->name('map');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
