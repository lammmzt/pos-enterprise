<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'id_kategori' => 1,
                'nama' => 'Aneka Krupuk',
                'deskripsi' => 'Toping Krupuk Seblak',
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:38:19',
                'updated_at' => '2026-02-28 03:38:19',
            ],
            [
                'id_kategori' => 2,
                'nama' => 'Suki & Seafood',
                'deskripsi' => 'Toping Frozen Food Suki & Seafood',
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:39:46',
                'updated_at' => '2026-02-28 03:40:15',
            ],
            [
                'id_kategori' => 3,
                'nama' => 'Bakso & Sosis',
                'deskripsi' => "Toping \nBakso Sosis",
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:40:05',
                'updated_at' => '2026-02-28 03:40:05',
            ],
            [
                'id_kategori' => 4,
                'nama' => 'Sayuran & Jamur',
                'deskripsi' => "Toping sayuran dan jamur-jamuran\n",
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:40:38',
                'updated_at' => '2026-02-28 03:40:38',
            ],
            [
                'id_kategori' => 5,
                'nama' => 'Minuman',
                'deskripsi' => "Menu minuman yang ada\n",
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:40:56',
                'updated_at' => '2026-02-28 03:40:56',
            ],
            [
                'id_kategori' => 6,
                'nama' => 'Cemilan',
                'deskripsi' => "Cemilan selain seblak\n",
                'status' => 'aktif',
                'created_at' => '2026-02-28 03:41:43',
                'updated_at' => '2026-02-28 03:41:43',
            ],
            [
                'id_kategori' => 7,
                'nama' => 'Toping',
                'deskripsi' => 'Toping lainnya',
                'status' => 'aktif',
                'created_at' => '2026-02-28 06:40:28',
                'updated_at' => '2026-02-28 06:40:28',
            ],
        ]);
    }
}