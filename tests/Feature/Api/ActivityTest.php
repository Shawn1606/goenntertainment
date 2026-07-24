<?php

use App\Models\Activity;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('activities-liste braucht einen Token', function () {
    $this->getJson('/api/activities')->assertUnauthorized();
});

test('activities-liste gibt die Aktivitäten zurück', function () {
    $user = User::factory()->create();
    Activity::factory()->count(2)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/activities')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'title', 'location', 'starts_at', 'banner_url', 'host', 'interests']]])
        ->assertJsonCount(2, 'data');
});

test('activity anlegen braucht einen Token', function () {
    $this->postJson('/api/activities', [])->assertUnauthorized();
});

test('activity anlegen speichert die Aktivität mit dem User als Host', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/activities', [
        'title' => 'Feierabend-Fußball',
        'description' => 'Locker kicken im Park.',
        'location' => 'Stadtpark, Köln',
        'starts_at' => now()->addDay()->toIso8601String(),
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'Feierabend-Fußball')
        ->assertJsonPath('data.host.id', $user->id);

    expect(Activity::query()->where('title', 'Feierabend-Fußball')->where('user_id', $user->id)->exists())->toBeTrue();
});

test('activity anlegen lehnt leere Pflichtfelder ab', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/activities', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description', 'location', 'starts_at']);
});

test('activity anlegen verknüpft bis zu fünf Interessen', function () {
    $user = User::factory()->create();
    $interests = Interest::factory()->count(3)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/activities', [
        'title' => 'Kreativabend',
        'description' => 'Zusammen malen und basteln.',
        'location' => 'Südstadt',
        'starts_at' => now()->addDay()->toIso8601String(),
        'interests' => $interests->pluck('id')->all(),
    ])->assertCreated()->assertJsonCount(3, 'data.interests');

    expect(Activity::query()->first()->interests()->count())->toBe(3);
});

test('activity anlegen lehnt mehr als fünf Interessen ab', function () {
    $user = User::factory()->create();
    $interests = Interest::factory()->count(6)->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/activities', [
        'title' => 'Zu viele',
        'description' => 'Test.',
        'location' => 'Irgendwo',
        'starts_at' => now()->addDay()->toIso8601String(),
        'interests' => $interests->pluck('id')->all(),
    ])->assertStatus(422)->assertJsonValidationErrors(['interests']);
});

test('activity anlegen speichert ein Banner-Bild', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/activities', [
        'title' => 'Mit Banner',
        'description' => 'Hat ein Bild.',
        'location' => 'Altstadt',
        'starts_at' => now()->addDay()->toIso8601String(),
        'banner' => UploadedFile::fake()->image('banner.jpg'),
    ]);

    $response->assertCreated();
    expect($response->json('data.banner_url'))->not->toBeNull();

    $path = Activity::query()->first()->banner_path;
    Storage::disk('public')->assertExists($path);
});
