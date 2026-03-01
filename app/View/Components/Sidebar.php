<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $menuGroups;

    public function __construct()
    {
        // Ambil role user yang sedang login. Sesuaikan dengan field di database Anda.
        // Jika belum login, kita beri default kosong agar tidak error.
        $userRole = auth()->check() ? auth()->user()->role : '';

        // 1. Definisikan semua menu beserta role yang diizinkan
        // Asumsi role: 'admin', 'kasir', 'owner' (Silakan sesuaikan dengan database Anda)
        $allMenus = [
            [
                'title' => 'Menu Utama',
                'items' => [
                    [
                        'name' => 'Dashboard', 
                        'icon' => 'smart-home', 
                        'activePattern' => 'admin.dashboard',
                        'route' => 'admin.dashboard',
                        'roles' => ['admin', 'owner', 'kasir'], // Semua role bisa lihat
                    ],
                ]
            ],
            [
                'title' => 'Aplikasi',
                'items' => [
                    [
                        'name' => 'POS Kasir',
                        'icon' => 'device-laptop', 
                        'activePattern' => 'pages.apps.pos',
                        'roles' => ['admin', 'kasir'], // Owner mungkin tidak perlu buka kasir
                        'subItems' => [
                            ['name' => 'Pos', 'route' => 'admin.pos', 'roles' => ['admin', 'kasir']],
                            ['name' => 'Pesanan Aktif', 'route' => 'admin.pesanan-aktif', 'roles' => ['admin', 'kasir']],
                            ['name' => 'Riwayat Pesanan', 'route' => 'admin.riwayat-pesanan', 'roles' => ['admin', 'kasir', 'owner']],
                        ],
                    ],
                    [
                        'name' => 'Manajemen Produk',
                        'icon' => 'shopping-cart', 
                        'activePattern' => 'ecommerce.*',
                        'roles' => ['admin', 'owner'], // Kasir tidak bisa atur produk
                        'subItems' => [
                            ['name' => 'Kategori', 'route' => 'admin.kategori', 'roles' => ['admin']],
                            ['name' => 'Produk', 'route' => 'admin.produk', 'roles' => ['admin', 'owner']],
                            ['name' => 'Pemasok', 'route' => 'admin.pemasok', 'roles' => ['admin']],
                            ['name' => 'Pembelian', 'route' => 'admin.pembelian', 'roles' => ['admin', 'owner']],
                            ['name' => 'Mutasi Stok', 'route' => 'admin.mutasi-stok', 'roles' => ['admin', 'owner']],
                        ]
                    ],
                    [
                        'name' => 'Laporan',
                        'icon' => 'report',
                        'activePattern' => 'laporan.*',
                        'roles' => ['admin', 'owner'], // Kasir tidak bisa lihat laporan keuangan
                        'subItems' => [
                            ['name' => 'Keuangan', 'route' => 'admin.laporan-keuangan', 'roles' => ['admin', 'owner']],
                            ['name' => 'Penjualan', 'route' => 'admin.laporan-penjualan', 'roles' => ['admin', 'owner']],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Pengaturan & Pengguna',
                'items' => [
                    [
                        'name' => 'Users', 
                        'icon' => 'users', 
                        'activePattern' => 'admin.user',
                        'route' => 'admin.user',
                        'roles' => ['admin'], // Hanya admin yang bisa atur user
                    ],
                    [
                        'name' => 'Pengaturan', 
                        'icon' => 'settings', 
                        'activePattern' => 'admin.pengaturan',
                        'route' => 'admin.pengaturan',
                        'roles' => ['admin'], // Hanya admin
                    ],
                ]
            ],
        ];

        // 2. Filter menu berdasarkan Role User
        $this->menuGroups = $this->filterMenusByRole($allMenus, $userRole);
    }

    /**
     * Fungsi untuk memfilter array menu berdasarkan role
     */
    private function filterMenusByRole(array $groups, string $userRole): array
    {
        $filteredGroups = [];

        foreach ($groups as $group) {
            $filteredItems = [];

            foreach ($group['items'] as $item) {
                // Cek apakah item ini diizinkan untuk role user saat ini
                if (isset($item['roles']) && !in_array($userRole, $item['roles'])) {
                    continue; // Lewati item ini (tidak dimasukkan ke menu)
                }

                // Jika item memiliki subItems, filter juga subItems-nya
                if (isset($item['subItems'])) {
                    $filteredSubItems = [];
                    foreach ($item['subItems'] as $subItem) {
                        if (!isset($subItem['roles']) || in_array($userRole, $subItem['roles'])) {
                            $filteredSubItems[] = $subItem;
                        }
                    }
                    
                    // Update subItems dengan yang sudah difilter
                    $item['subItems'] = $filteredSubItems;

                    // Jika setelah difilter ternyata subItems-nya kosong, jangan tampilkan parent-nya
                    if (empty($item['subItems'])) {
                        continue;
                    }
                }

                $filteredItems[] = $item;
            }

            // Jika group ini masih memiliki item setelah difilter, masukkan ke hasil akhir
            if (!empty($filteredItems)) {
                $group['items'] = $filteredItems;
                $filteredGroups[] = $group;
            }
        }

        return $filteredGroups;
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.layouts.sidebar');
    }
}