<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            User::create([
            'name' => 'Sergio',
            'email' => 'sergio@crypto.com',
            'password' => Hash::make('1245'),
            'rol' => 'normal',
        ]);

        User::create([
            'name' => 'Raquel',
            'email' => 'raquel@crypto.com',
            'password' => Hash::make('1245'),
            'rol' => 'pro',
        ]);

        User::create([
            'name' => 'Alicia',
            'email' => 'alicia@crypto.com',
            'password' => Hash::make('1245'),
            'rol' => 'normal',
        ]);
    }
}
