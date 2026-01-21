<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Daftar permission
        $permissions = [
            'view-dashboard',
            'manage-products',
            'manage-users',
            'request-item',   // Untuk fitur Carts/Request
            'process-stock',  // Untuk input Barang Masuk/Keluar
            'view-logs',      // Untuk melihat Activity Log
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Buat Role dan berikan Permission

        // Super Admin: Bisa semua hal
        $roleAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // Staff: Hanya bisa request dan lihat dashboard
        $roleStaff = Role::create(['name' => 'Staff']);
        $roleStaff->givePermissionTo(['view-dashboard', 'request-item']);

        // Gudang: Bisa urus stok dan dashboard
        $roleGudang = Role::create(['name' => 'Gudang']);
        $roleGudang->givePermissionTo(['view-dashboard', 'process-stock', 'manage-products']);
    }
}