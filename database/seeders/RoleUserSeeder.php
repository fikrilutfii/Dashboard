<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Viewer User',
                'username' => 'admin1', // Viewer
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'viewer',
            ],
            [
                'name' => 'Admin User',
                'username' => 'admin2', // Admin full access
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Kasir User',
                'username' => 'admin3', // Limited access
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin3',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
