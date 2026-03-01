<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanKeuanganIndex extends Component
{
    use WithPagination;

    public $tanggalMulai;
    public $tanggalAkhir;
    public $view = 10;

    public function mount()
    {
        // Default: Tampilkan data bulan ini
        $this->tanggalMulai = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingTanggalMulai() { $this->resetPage(); }
    public function updatingTanggalAkhir() { $this->resetPage(); }

    public function render()
    {
        // Pastikan format range tanggal mencakup hingga jam 23:59:59 di hari terakhir
        $start = $this->tanggalMulai . ' 00:00:00';
        $end = $this->tanggalAkhir . ' 23:59:59';

        // 1. MENGHITUNG METRIK GLOBAL (KARTU ATAS)
        $ringkasan = DetailPesanan::join('mangkuk_pesanan', 'detail_pesanan.id_mangkuk', '=', 'mangkuk_pesanan.id_mangkuk')
            ->join('pesanan', 'mangkuk_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->leftJoin('batch_produk', 'detail_pesanan.id_batch', '=', 'batch_produk.id_batch')
            ->where('pesanan.status_pesanan', 'selesai')
            ->whereBetween('pesanan.created_at', [$start, $end])
            ->select(
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                // HPP = Qty Terjual * Harga Beli Batch (Jika batch dihapus/null, fallback ke 0)
                DB::raw('SUM(detail_pesanan.jumlah * COALESCE(batch_produk.harga_beli, 0)) as total_hpp')
            )->first();

        $data['totalPendapatan'] = $ringkasan->total_pendapatan ?? 0;
        $data['totalHpp'] = $ringkasan->total_hpp ?? 0;
        $data['labaKotor'] = $data['totalPendapatan'] - $data['totalHpp'];
        $data['marginPersen'] = $data['totalPendapatan'] > 0 
                                ? round(($data['labaKotor'] / $data['totalPendapatan']) * 100, 1) 
                                : 0;

        // 2. BREAKDOWN PERFORMA PRODUK (TABEL BAWAH)
        $data['laporanProduk'] = DetailPesanan::join('mangkuk_pesanan', 'detail_pesanan.id_mangkuk', '=', 'mangkuk_pesanan.id_mangkuk')
            ->join('pesanan', 'mangkuk_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('produk', 'detail_pesanan.id_produk', '=', 'produk.id_produk')
            ->leftJoin('batch_produk', 'detail_pesanan.id_batch', '=', 'batch_produk.id_batch')
            ->where('pesanan.status_pesanan', 'selesai')
            ->whereBetween('pesanan.created_at', [$start, $end])
            ->select(
                'produk.nama as nama_produk',
                DB::raw('SUM(detail_pesanan.jumlah) as qty_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as pendapatan_produk'),
                DB::raw('SUM(detail_pesanan.jumlah * COALESCE(batch_produk.harga_beli, 0)) as hpp_produk')
            )
            ->groupBy('produk.id_produk', 'produk.nama')
            ->orderBy('pendapatan_produk', 'desc')
            ->paginate($this->view);

        $data['title'] = 'Laporan Keuangan & Margin';
        $data['desc_page'] = 'Analisis Laba/Rugi berdasarkan metode HPP FEFO per produk.';

        return view('livewire.admin.laporan.laporan-keuangan-index', $data)
            ->layout('components.layouts.app', $data);
    }

    public function cetakLaporan()
    {
        // Mengirim instruksi ke browser (JS) beserta parameter tanggal
        $this->dispatch('cetak-laporan', start: $this->tanggalMulai, end: $this->tanggalAkhir);
    }
}