<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'john_doe',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'jane_smith',
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'michael_lee',
                'name' => 'Michael Lee',
                'email' => 'michael@example.com',
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'sarah_connor',
                'name' => 'Sarah Connor',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'david_williams',
                'name' => 'David Williams',
                'email' => 'david@example.com',
                'password' => Hash::make('password123')
            ],
        ]);
    }
}
