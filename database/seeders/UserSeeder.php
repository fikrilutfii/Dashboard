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
                'name' => 'Admin 1',
                'email' => 'admin1@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'allowed_division' => 'all',
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'allowed_division' => 'konfeksi',
            ],
            [
                'name' => 'Kasir Faktur',
                'email' => 'faktur@example.com',
                'password' => bcrypt('password'),
                'role' => 'faktur',
                'allowed_division' => 'all',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
