<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function cetakKeuangan(Request $request)
    {
        $start = $request->start . ' 00:00:00';
        $end = $request->end . ' 23:59:59';

        // 1. MENGHITUNG METRIK GLOBAL
        $ringkasan = DetailPesanan::join('mangkuk_pesanan', 'detail_pesanan.id_mangkuk', '=', 'mangkuk_pesanan.id_mangkuk')
            ->join('pesanan', 'mangkuk_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->leftJoin('batch_produk', 'detail_pesanan.id_batch', '=', 'batch_produk.id_batch')
            ->where('pesanan.status_pesanan', 'selesai')
            ->whereBetween('pesanan.created_at', [$start, $end])
            ->select(
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                DB::raw('SUM(detail_pesanan.jumlah * COALESCE(batch_produk.harga_beli, 0)) as total_hpp')
            )->first();

        $totalPendapatan = $ringkasan->total_pendapatan ?? 0;
        $totalHpp = $ringkasan->total_hpp ?? 0;
        $labaKotor = $totalPendapatan - $totalHpp;
        $marginPersen = $totalPendapatan > 0 ? round(($labaKotor / $totalPendapatan) * 100, 1) : 0;

        // 2. BREAKDOWN PERFORMA PRODUK (Semua Data Tanpa Pagination)
        $laporanProduk = DetailPesanan::join('mangkuk_pesanan', 'detail_pesanan.id_mangkuk', '=', 'mangkuk_pesanan.id_mangkuk')
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
            ->get();

        $tanggalMulai = Carbon::parse($request->start)->translatedFormat('d F Y');
        $tanggalAkhir = Carbon::parse($request->end)->translatedFormat('d F Y');

        return view('admin.laporan.cetak-keuangan', compact(
            'tanggalMulai', 'tanggalAkhir', 
            'totalPendapatan', 'totalHpp', 'labaKotor', 'marginPersen', 
            'laporanProduk'
        ));
    }

    public function cetakPenjualan(Request $request)
    {
        $start = $request->start . ' 00:00:00';
        $end = $request->end . ' 23:59:59';

        $pesananSelesai = Pesanan::where('status_pesanan', 'selesai')->whereBetween('created_at', [$start, $end]);

        $totalTransaksi = (clone $pesananSelesai)->count();
        $totalPendapatan = (clone $pesananSelesai)->sum('total_harga');
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;
        
        $totalItemTerjual = DetailPesanan::whereHas('mangkuk.pesanan', function($q) use ($start, $end) {
            $q->where('status_pesanan', 'selesai')->whereBetween('created_at', [$start, $end]);
        })->sum('jumlah');

        $laporanTipePesanan = (clone $pesananSelesai)->select('tipe_pesanan', DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))->groupBy('tipe_pesanan')->get();
        $laporanMetodeBayar = (clone $pesananSelesai)->select('metode_pembayaran', DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))->groupBy('metode_pembayaran')->get();
        
        $trenHarian = (clone $pesananSelesai)->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as jumlah_transaksi'), DB::raw('sum(total_harga) as total_pendapatan'))
            ->groupBy('tanggal')->orderBy('tanggal', 'asc')->get();

        $tanggalMulai = Carbon::parse($request->start)->translatedFormat('d F Y');
        $tanggalAkhir = Carbon::parse($request->end)->translatedFormat('d F Y');

        return view('admin.laporan.cetak-penjualan', compact(
            'tanggalMulai', 'tanggalAkhir', 'totalTransaksi', 'totalPendapatan', 'totalItemTerjual', 'rataRataTransaksi',
            'laporanTipePesanan', 'laporanMetodeBayar', 'trenHarian'
        ));
    }
}