<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin HRD',
            'email'    => 'admin@vms.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Satpam',
            'email'    => 'satpam@vms.test',
            'password' => Hash::make('password'),
            'role'     => 'satpam',
        ]);
    }
}