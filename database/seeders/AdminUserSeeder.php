<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'isaacperuperu@gmail.com',
            'password' => Hash::make('123456789'), // Asegúrate de cambiar esta contraseña
            'type' => 1, // 1 = Admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
