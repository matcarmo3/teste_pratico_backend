<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('manager'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Finance',
            'email' => 'finance@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('finance'),
            'role' => 'finance',
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@teste.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user'),
            'role' => 'user',
        ]);
    }
}
