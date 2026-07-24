<?php

namespace Database\Seeders;

use App\AccountType;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Legt den Admin-Account für das Admin-Panel an (idempotent).
 *
 * E-Mail und Passwort kommen aus der .env (NICHT im Code, da das Repo öffentlich
 * ist). Das Passwort wird durch den 'hashed'-Cast des User-Models verschlüsselt.
 *
 * .env:
 *   ADMIN_EMAIL=Admin@Goe4Fun.sh
 *   ADMIN_PASSWORD=dein-passwort
 *
 * Ausführen: php artisan db:seed --class=AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! is_string($email) || $email === '' || ! is_string($password) || $password === '') {
            throw new RuntimeException('ADMIN_EMAIL und ADMIN_PASSWORD müssen in der .env gesetzt sein.');
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => $password,
                'account_type' => AccountType::Personal,
            ],
        );
    }
}
