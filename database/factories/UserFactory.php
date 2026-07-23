<?php

namespace Database\Factories;

use App\AccountType;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'account_type' => fake()->randomElement(AccountType::cases()),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withInterests(int $count = 3): static
    {
        return $this->afterCreating(function (User $user) use ($count): void {
            $interests = Interest::factory()->count($count)->create();
            $user->interests()->attach($interests);
        });
    }

    public function google(): static
    {
        return $this->state(fn (array $attributes) => [
            'google_id' => (string) fake()->unique()->numerify('################'),
            'password' => null,
            'avatar' => fake()->imageUrl(),
        ]);
    }

    public function incompleteProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => null,
            'account_type' => null,
        ]);
    }
}
