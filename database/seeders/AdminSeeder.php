<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'saqib@oldcity.com'],
            [
                'name'     => 'Saqib Din',
                'password' => Hash::make('12345678'),
                'role'     => 'admin', 
                'status'   => 'active',   
            ]
        );
    }
}
