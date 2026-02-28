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
            'role' => 'owner',
            'status' => 'aktif',
            'catatan' => 'Akun Super Admin',
        ]);

        // 2. Buat Akun Admin Gudang
        $admin = User::create([
            'nama' => 'Admin Gudang',
            'username' => 'admin_gudang',
            'password' => Hash::make('admin321'),
            'alamat' => 'Jl. Gudang No. 2',
            'status' => 'aktif',
            'role' => 'admin',
            'catatan' => 'Akun Operasional',
        ]);

        // 3. Buat Akun Kasir Depan
        $kasir = User::create([
            'nama' => 'Kasir Utama',
            'username' => 'kasir1',
            'password' => Hash::make('kasir321'),
            'alamat' => 'Toko Cabang Utama',
            'status' => 'aktif',
            'role' => 'kasir',
            'catatan' => 'Akun Kasir Shift 1',
        ]);

        // 4. akun pengguna
        $user = User::create([
            'nama' => 'Pengguna',
            'username' => 'pengguna',
            'password' => Hash::make('pengguna321'),
            'alamat' => 'Toko Cabang Utama',
            'status' => 'aktif',
            'role' => 'pelanggan',
            'catatan' => 'Akun Pengguna',
        ]);
    }
}