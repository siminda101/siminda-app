<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@setda.go.id'],
            [
                'name' => 'Administrator SIMINDA',
                'password' => 'Admin12345!',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@setda.go.id'],
            [
                'name' => 'Pimpinan SETDA',
                'password' => 'Pimpinan12345!',
                'role' => 'pimpinan',
            ]
        );
    }
}
