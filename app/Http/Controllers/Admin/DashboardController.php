<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total data dasar
        $totalProduk = Produk::count();
        $totalPesanan = Pesanan::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        
        // Menghitung pendapatan bulan ini (dari pesanan yang sudah lunas)
        $pendapatanBulanIni = Pesanan::where('status_pembayaran', 'lunas')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_harga');

        // Mengirim data ke view
        return view('admin.dashboard', compact(
            'totalProduk', 
            'totalPesanan', 
            'totalPelanggan', 
            'pendapatanBulanIni'
        ));
    }
}