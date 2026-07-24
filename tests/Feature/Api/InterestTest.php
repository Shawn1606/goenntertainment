<?php

use App\Models\Interest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('interests-endpunkt gibt die Interessen öffentlich zurück', function () {
    Interest::factory()->count(3)->create();

    $this->getJson('/api/interests')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'icon']]])
        ->assertJsonCount(3, 'data');
});
