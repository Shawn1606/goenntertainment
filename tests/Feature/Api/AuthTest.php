<?php

use App\AccountType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(RefreshDatabase::class);

test('register legt einen User an und gibt einen Token zurück', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Max Mustermann',
        'username' => 'maxmuster',
        'email' => 'max@example.com',
        'password' => 'geheim1234',
        'account_type' => AccountType::cases()[0]->value,
    ]);

    $response->assertCreated()
        ->assertJsonStructure(['user' => ['id', 'email'], 'token', 'profile_complete']);

    expect(User::query()->where('email', 'max@example.com')->exists())->toBeTrue();
});

test('register lehnt fehlende Felder ab', function () {
    $this->postJson('/api/register', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'username', 'email', 'password', 'account_type']);
});

test('register lehnt doppelte E-Mail ab', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/api/register', [
        'name' => 'Zwei',
        'username' => 'zweiter',
        'email' => 'taken@example.com',
        'password' => 'geheim1234',
        'account_type' => AccountType::cases()[0]->value,
    ])->assertStatus(422)->assertJsonValidationErrors(['email']);
});

test('login mit richtigen Daten gibt einen Token zurück', function () {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'geheim1234',
    ]);

    $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'geheim1234',
    ])->assertOk()->assertJsonStructure(['user', 'token', 'profile_complete']);
});

test('login mit falschem Passwort schlägt fehl', function () {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'geheim1234',
    ]);

    $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'falsch-falsch',
    ])->assertStatus(422)->assertJsonValidationErrors(['email']);
});

test('user-Endpunkt braucht einen Token', function () {
    $this->getJson('/api/user')->assertUnauthorized();
});

test('user-Endpunkt liefert den eingeloggten User', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('user.id', $user->id);
});

test('logout löscht den benutzten Token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)->postJson('/api/logout')->assertOk();

    expect($user->tokens()->count())->toBe(0);
});

test('google-login legt bei Bedarf einen User an und gibt einen Token zurück', function () {
    $socialiteUser = (new SocialiteUser)->setRaw([])->map([
        'id' => 'google-abc-123',
        'name' => 'Google Nutzer',
        'nickname' => 'gnutzer',
        'email' => 'google@example.com',
        'avatar' => 'https://example.com/avatar.png',
    ]);

    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('stateless')->andReturnSelf();
    $provider->shouldReceive('userFromToken')->with('valid-google-token')->andReturn($socialiteUser);
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $this->postJson('/api/auth/google', ['access_token' => 'valid-google-token'])
        ->assertOk()
        ->assertJsonStructure(['user', 'token', 'profile_complete']);

    expect(User::query()->where('google_id', 'google-abc-123')->exists())->toBeTrue();
});
