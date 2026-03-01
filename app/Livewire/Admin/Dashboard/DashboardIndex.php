<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\BatchProduk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardIndex extends Component
{
    // Filter State
    public $tanggalMulai;
    public $tanggalAkhir;

    public function mount()
    {
        // Default: 7 Hari Terakhir agar grafik langsung terlihat bagus
        $this->tanggalMulai = Carbon::now()->subDays(6)->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $start = $this->tanggalMulai . ' 00:00:00';
        $end = $this->tanggalAkhir . ' 23:59:59';

        // 1. METRIK BERDASARKAN FILTER TANGGAL
        $data['pendapatanPeriode'] = Pesanan::where('status_pesanan', 'selesai')
                                            ->whereBetween('created_at', [$start, $end])
                                            ->sum('total_harga');

        $data['transaksiPeriode'] = Pesanan::where('status_pesanan', 'selesai')
                                           ->whereBetween('created_at', [$start, $end])
                                           ->count();

        // 2. METRIK REAL-TIME (Tidak terpengaruh filter tanggal)
        $data['antreanDapur'] = Pesanan::where('status_pesanan', 'proses')->count();
        $data['menungguPembayaran'] = Pesanan::where('status_pembayaran', 'belum_bayar')
                                             ->where('status_pesanan', '!=', 'dibatalkan')
                                             ->count();

        // 3. DATA UNTUK GRAFIK (CHART) PENJUALAN HARIAN
        $chartQuery = Pesanan::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_harga) as total'))
            ->where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $data['chartDates'] = $chartQuery->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d M');
        })->toArray();
        $data['chartTotals'] = $chartQuery->pluck('total')->toArray();

        // 4. TRANSAKSI TERKINI & AMARAN INVENTORI
        $data['transaksiTerkini'] = Pesanan::with('pelanggan')->orderBy('created_at', 'desc')->take(6)->get();

        $data['stokTipis'] = Produk::where('stok', '<=', 10)->where('status', 'aktif')->orderBy('stok', 'asc')->take(5)->get();

        $data['hampirKedaluwarsa'] = BatchProduk::with('produk')
                                                ->where('jumlah', '>', 0)
                                                ->whereNotNull('tanggal_kedaluwarsa')
                                                ->whereDate('tanggal_kedaluwarsa', '<=', Carbon::now()->addDays(7))
                                                ->orderBy('tanggal_kedaluwarsa', 'asc')
                                                ->take(5)->get();

        $data['title'] = 'Dashboard';
        $data['desc_page'] = 'Analitik penjualan, antrean real-time, dan amaran inventori.';

        // Mengirim data terbaru ke JS untuk update grafik tanpa reload
        $this->dispatch('update-chart', chartData: [
            'dates' => $data['chartDates'],
            'totals' => $data['chartTotals']
        ]);

        return view('livewire.admin.dashboard.dashboard-index', $data)->layout('components.layouts.app', $data);
    }
}