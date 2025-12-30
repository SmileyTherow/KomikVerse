<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Hati-hati: jika sudah ada user dengan email ini, seeder akan skip
        $email = 'inkomi.app@gmail.com';
        $existing = User::where('email', $email)->first();
        if ($existing) {
            $this->command->info("Admin user with email {$email} already exists. Skipping.");
            return;
        }

        User::create([
            'name' => 'Inkomi Admin',
            'email' => $email,
            'password' => Hash::make('admin1234'),
            'is_admin' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        $this->command->info("Admin user {$email} created with password 'admin1234'.");
    }
}
