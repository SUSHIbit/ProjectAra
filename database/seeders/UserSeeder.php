<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'manager',
            'is_active' => true,
        ]);
        
        // Create employees
        User::create([
            'name' => 'Employee One',
            'email' => 'employee1@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'employee',
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Employee Two',
            'email' => 'employee2@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'employee',
            'is_active' => true,
        ]);
        
        // Create public users
        User::create([
            'name' => 'Sushi Maru',
            'email' => 'ariefsushi1@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'public',
            'phone' => '123-456-7890',
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'public',
            'phone' => '987-654-3210',
            'is_active' => true,
        ]);
    }
}