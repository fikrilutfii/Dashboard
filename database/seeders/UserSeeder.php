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
                'username' => 'admin_1',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'allowed_division' => 'all',
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@example.com',
                'username' => 'admin_2',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'allowed_division' => 'konfeksi',
            ],
            [
                'name' => 'Admin 3',
                'email' => 'admin3@example.com',
                'username' => 'admin_3',
                'password' => bcrypt('12345678'),
                'role' => 'admin3',
                'allowed_division' => 'all',
            ],
            [
                'name' => 'Supervisor Tracker',
                'email' => 'tracker@example.com',
                'username' => 'tracker',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'allowed_division' => 'all',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                    'allowed_division' => $user['allowed_division'],
                ]
            );
        }
    }
}
