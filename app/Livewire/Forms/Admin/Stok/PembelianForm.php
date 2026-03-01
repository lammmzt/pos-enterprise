<?php

namespace App\Livewire\Forms\Admin\Stok;

use Livewire\Form;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\BatchProduk;
use App\Models\MutasiStok;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianForm extends Form
{
    public $id_pemasok;
    public $tanggal_pembelian;
    public $nomor_referensi;
    public $status = 'selesai';
    
    public $items = []; 

    public function rules()
    {
        return [
            'id_pemasok' => 'required|exists:pemasok,id_pemasok',
            'tanggal_pembelian' => 'required|date',
            'nomor_referensi' => 'required|unique:pembelian,nomor_referensi',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:1', // Wajib diisi (Harga Dasar Baru)
            'items.*.harga_jual' => 'required|numeric|min:1',   // Wajib diisi (Harga Jual Baru)
            'items.*.tanggal_kedaluwarsa' => 'required|date',   // Wajib diisi sesuai request
        ];
    }

    public function initForm()
    {
        $this->reset();
        $this->tanggal_pembelian = date('Y-m-d');
        $this->nomor_referensi = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $this->items = [
            ['id_produk' => '', 'jumlah' => 1, 'harga_satuan' => 0, 'harga_jual' => 0, 'tanggal_kedaluwarsa' => '']
        ];
    }

    public function store()
    {
        $this->validate(); // Validasi ketat akan berjalan di sini

        DB::transaction(function () {
            $total_harga = collect($this->items)->sum(function ($item) {
                return $item['jumlah'] * $item['harga_satuan'];
            });

            $pembelian = Pembelian::create([
                'id_pemasok' => $this->id_pemasok,
                'id_user' => Auth::id(),
                'nomor_referensi' => $this->nomor_referensi,
                'total_harga' => $total_harga,
                'status' => $this->status,
                'tanggal_pembelian' => $this->tanggal_pembelian,
            ]);

            foreach ($this->items as $item) {
                // Update Master Harga Produk berdasarkan inputan terbaru di PO
                $produk = Produk::find($item['id_produk']);
                if ($produk) {
                    $produk->update([
                        'harga_dasar' => $item['harga_satuan'],
                        'harga_jual' => $item['harga_jual'],
                        'stok' => $produk->stok + $item['jumlah'],
                    ]);
                }

                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_produk' => $item['id_produk'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['jumlah'] * $item['harga_satuan'],
                ]);

                $batch = BatchProduk::create([
                    'id_produk' => $item['id_produk'],
                    'id_pembelian' => $pembelian->id_pembelian,
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_satuan'],
                    'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                ]);

                MutasiStok::create([
                    'id_produk' => $item['id_produk'],
                    'id_batch' => $batch->id_batch,
                    'id_user' => Auth::id(),
                    'tipe' => 'masuk',
                    'jumlah' => $item['jumlah'],
                    'tipe_referensi' => 'Pembelian',
                    'id_referensi' => $pembelian->id_pembelian,
                    'catatan' => 'Restock dari Pembelian ' . $pembelian->nomor_referensi,
                ]);
            }
        });

        $this->reset();
    }
}