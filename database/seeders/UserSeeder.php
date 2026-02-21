<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Owner
        $owner = User::create([
            'nama' => 'Bapak Owner',
            'username' => 'owner_seblak',
            'password' => Hash::make('owner321'),
            'alamat' => 'Jl. Pusat Bisnis No. 1',
            'status' => 'aktif',
            'catatan' => 'Akun Super Admin',
        ]);
        $owner->assignRole('owner'); // Berikan role owner Spatie

        // 2. Buat Akun Admin Gudang
        $admin = User::create([
            'nama' => 'Admin Gudang',
            'username' => 'admin_seblak',
            'password' => Hash::make('admin312'),
            'alamat' => 'Jl. Gudang No. 2',
            'status' => 'aktif',
            'catatan' => 'Akun Operasional',
        ]);
        $admin->assignRole('admin'); // Berikan role admin Spatie

        // 3. Buat Akun Kasir Depan
        $kasir = User::create([
            'nama' => 'Kasir Utama',
            'username' => 'kasir_1',
            'password' => Hash::make('kasir321'),
            'alamat' => 'Toko Cabang Utama',
            'status' => 'aktif',
            'catatan' => 'Akun Kasir Shift 1',
        ]);
        $kasir->assignRole('kasir'); // Berikan role kasir Spatie
    }
}