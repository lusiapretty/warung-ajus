<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
['email' => 'admin@gmail.com'],
    [
            'nama' => 'Admin',
            'email' => 'warungajusjimbaran@gmail.com',
            'password' => Hash::make('Ajus1206'),
            'role' => 'admin', 
        ]);
    }
}
