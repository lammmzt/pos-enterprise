<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function cetakStruk(String $id): View
    {
        // Tarik data Pesanan beserta relasi Mangkuk dan Detail Produknya
        $pesanan = Pesanan::with([
            'kasir', 
            'pelanggan', 
            'mangkuk.detailPesanan.produk'
        ])->findOrFail($id);

        return view('admin.pos.struk', compact('pesanan'));
    }
}