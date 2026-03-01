<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanIndex extends Component
{
    public $tanggalMulai;
    public $tanggalAkhir;

    public function mount()
    {
        $this->tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $start = $this->tanggalMulai . ' 00:00:00';
        $end = $this->tanggalAkhir . ' 23:59:59';

        // Base Query untuk Pesanan Selesai
        $pesananSelesai = Pesanan::where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$start, $end]);

        // 1. METRIK UTAMA
        $data['totalTransaksi'] = $pesananSelesai->count();
        $data['totalPendapatan'] = $pesananSelesai->sum('total_harga');
        $data['rataRataTransaksi'] = $data['totalTransaksi'] > 0 ? $data['totalPendapatan'] / $data['totalTransaksi'] : 0;
        
        // Total Produk Terjual (Join Detail)
        $data['totalItemTerjual'] = DetailPesanan::whereHas('mangkuk.pesanan', function($q) use ($start, $end) {
            $q->where('status_pesanan', 'selesai')->whereBetween('created_at', [$start, $end]);
        })->sum('jumlah');

        // 2. BREAKDOWN BERDASARKAN TIPE PESANAN
        $data['laporanTipePesanan'] = Pesanan::select('tipe_pesanan', DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))
            ->where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('tipe_pesanan')
            ->get();

        // 3. BREAKDOWN BERDASARKAN METODE PEMBAYARAN
        $data['laporanMetodeBayar'] = Pesanan::select('metode_pembayaran', DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))
            ->where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('metode_pembayaran')
            ->get();

        // 4. TREN PENJUALAN HARIAN
        $data['trenHarian'] = Pesanan::select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))
            ->where('status_pesanan', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $data['title'] = 'Laporan Penjualan';
        $data['desc_page'] = 'Ringkasan performa transaksi, metode pembayaran, dan tren harian restoran.';

        return view('livewire.admin.laporan.laporan-penjualan-index', $data)->layout('components.layouts.app', $data);
    }

    public function cetakLaporan()
    {
        $this->dispatch('cetak-laporan-penjualan', start: $this->tanggalMulai, end: $this->tanggalAkhir);
    }
}