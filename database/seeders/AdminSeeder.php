<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the default admin account.
     *
     * Login credentials:
     *   Email:    admin@yashvienterprise.com
     *   Password: Admin@123
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yashvienterprise.com'],
            [
                'name'     => 'Yashvi Admin',
                'password' => Hash::make('Admin@123'),
            ]
        );
    }
}
