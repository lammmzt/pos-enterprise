<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengaturan')->insert([
            [
                'id_pengaturan' => 1,
                'kunci' => 'fonnte_token',
                'nilai' => 'bfYWN676xwgHuvH9zjgV',
                'status' => 'aktif',
                'created_at' => '2026-02-27 22:42:35',
                'updated_at' => '2026-03-01 22:18:36',
            ],
            [
                'id_pengaturan' => 2,
                'kunci' => 'jam_buka_toko',
                'nilai' => '07.00-23.00',
                'status' => 'aktif',
                'created_at' => '2026-03-01 22:41:43',
                'updated_at' => '2026-03-01 22:41:43',
            ],
            [
                'id_pengaturan' => 3,
                'kunci' => 'hari_buka_toko',
                'nilai' => 'Senin-Minggu',
                'status' => 'aktif',
                'created_at' => '2026-03-01 22:42:09',
                'updated_at' => '2026-03-01 23:22:12',
            ],
            [
                'id_pengaturan' => 4,
                'kunci' => 'status_aktif_toko',
                'nilai' => 'aktif',
                'status' => 'aktif',
                'created_at' => '2026-03-01 22:43:01',
                'updated_at' => '2026-03-01 23:22:32',
            ],
        ]);
    }
}