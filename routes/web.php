<?php

use App\Http\Controllers\EventHubController;
use App\Http\Controllers\GeoLocationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('map', function () {
    return Inertia::render('MapPage');
})->middleware(['auth', 'verified'])->name('map');

Route::get('events', function () {
    return Inertia::render('EventsPage');
})->middleware(['auth', 'verified'])->name('events');

Route::get('/geocode', GeoLocationController::class)->middleware(['auth', 'verified'])->name('geocode');


Route::middleware(['auth', 'verified'])
    ->prefix('eventhub')
    ->name('eventhub.')
    ->group(function () {
        Route::get('/', [EventHubController::class, 'fetchEvent'])->name('fetch');
        Route::get('/next', [EventHubController::class, 'getNextUpcomingEvent'])->name('next');
        Route::get('/upcoming', [EventHubController::class, 'getNextUpcomingEvents'])->name('upcoming');
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
