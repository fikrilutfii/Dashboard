<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Sanusi (Admin)',
                'email' => 'sanusi@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'allowed_division' => 'all',
            ],
            [
                'name' => 'Sellyn (Konfeksi)',
                'email' => 'sellyn@example.com',
                'password' => bcrypt('password'),
                'role' => 'sales',
                'allowed_division' => 'konfeksi',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
