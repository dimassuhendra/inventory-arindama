<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat User-nya terlebih dahulu
        $admin = Users::create([
            'name' => 'Admin Arindama',
            'email' => 'admin@arindama.com',
            'password' => Hash::make('12345678'),
            'department' => 'IT',
            'avatar' => null,
        ]);

        // 2. Baru berikan Role setelah user berhasil dibuat
        $admin->assignRole('Super Admin');

        // Contoh membuat user Staff
        $staff = Users::create([
            'name' => 'Staff Logistik',
            'email' => 'staff@arindama.com',
            'password' => Hash::make('12345678'),
            'department' => 'Gudang',
        ]);

        $staff->assignRole('Staff');
    }
}