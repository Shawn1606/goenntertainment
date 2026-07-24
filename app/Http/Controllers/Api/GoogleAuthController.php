<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GoogleLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Die Expo-App meldet sich per Google an, holt sich dort einen access_token
     * und schickt ihn hierher. Wir prüfen ihn bei Google, legen den User bei
     * Bedarf an und geben einen Sanctum-Token für die App zurück.
     */
    public function store(GoogleLoginRequest $request): JsonResponse
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->userFromToken($request->string('access_token')->toString());

        $user = User::query()->where('google_id', $googleUser->getId())->first();

        if ($user === null) {
            $user = User::query()->where('email', $googleUser->getEmail())->first();

            if ($user !== null) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::query()->create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }
        }

        $token = $user->createToken($request->string('device_name', 'mobile')->toString())->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'profile_complete' => $user->hasCompletedProfile(),
        ]);
    }
}
