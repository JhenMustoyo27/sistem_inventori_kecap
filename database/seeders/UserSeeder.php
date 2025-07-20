<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'pemilik',
                'password' => Hash::make('password'), // Ganti 'password' dengan password yang aman
                'role' => 'pemilik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
            ,[
            'username' => 'admin',
            'password' => Hash::make('password'), // Ganti 'password' dengan password yang aman
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]
        ]);
    }
}