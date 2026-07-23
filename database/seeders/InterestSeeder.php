<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            ['name' => 'Sport & Radfahren', 'icon' => 'bike'],
            ['name' => 'Soziales & Community', 'icon' => 'people'],
            ['name' => 'Basketball', 'icon' => 'basketball'],
            ['name' => 'Fotografie', 'icon' => 'camera'],
            ['name' => 'Musik', 'icon' => 'music'],
            ['name' => 'Gaming', 'icon' => 'gaming'],
            ['name' => 'Reisen', 'icon' => 'travel'],
            ['name' => 'Kochen', 'icon' => 'cooking'],
            ['name' => 'Kunst & Design', 'icon' => 'art'],
            ['name' => 'Fitness', 'icon' => 'fitness'],
        ];

        foreach ($interests as $interest) {
            Interest::query()->updateOrCreate(
                ['slug' => Str::slug($interest['name'])],
                [
                    'name' => $interest['name'],
                    'icon' => $interest['icon'],
                ],
            );
        }
    }
}
