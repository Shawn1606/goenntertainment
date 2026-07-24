<?php

namespace App\Http\Controllers\Api;

use App\AccountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\TransientToken;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create([
            'name' => $request->string('name')->toString(),
            'username' => $request->string('username')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'account_type' => $request->enum('account_type', AccountType::class),
        ]);

        if ($request->filled('interests')) {
            $user->interests()->sync($request->input('interests'));
        }

        event(new Registered($user));

        return $this->tokenResponse($user, $request->string('device_name', 'mobile')->toString(), 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->string('email')->toString())->first();

        if ($user === null || ! Hash::check($request->string('password')->toString(), (string) $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $this->tokenResponse($user, $request->string('device_name', 'mobile')->toString());
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token !== null && ! $token instanceof TransientToken) {
            $token->delete();
        }

        return response()->json(['message' => 'Abgemeldet.']);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'profile_complete' => $user->hasCompletedProfile(),
        ]);
    }

    private function tokenResponse(User $user, string $deviceName, int $status = 200): JsonResponse
    {
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'profile_complete' => $user->hasCompletedProfile(),
        ], $status);
    }
}
