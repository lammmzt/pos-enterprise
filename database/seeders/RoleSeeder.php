<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat roles dari PRD (guard default 'web')
        $roles = [
            'owner',     // Super Admin
            'admin',     // Back-office, gudang, produk
            'kasir',     // Panel POS UI
            'pelanggan', // Akses Frontend saja
            'antrean'    // Kiosk/Guest Panel
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}