
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@vst.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('AdminJafran2026!'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'satpam@vst.com'],
            [
                'name' => 'Satpam',
                'password' => Hash::make('SatpamJafran2026!'),
                'role' => 'satpam',
            ]
        );
    }
}
