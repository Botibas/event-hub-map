<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/map');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the map', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/map');
    $response->assertStatus(200);
});