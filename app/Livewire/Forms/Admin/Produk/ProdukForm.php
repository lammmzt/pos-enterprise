<?php

namespace App\Livewire\Forms\Admin\Produk;

use Livewire\Form;
use App\Models\Produk;

class ProdukForm extends Form
{
    public ?Produk $produk = null;

    public $nama;
    public $id_kategori;
    public $sku;
    public $deskripsi;
    public $harga_dasar;
    public $harga_jual;
    public $stok;
    public $gambar;
    public $status = 'aktif';

    public function rules()
    {
        $produkId = $this->produk ? $this->produk->id_produk : 'NULL';
        
        return [
            'nama' => 'required|string|unique:produk,nama,' . $produkId . ',id_produk',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'sku' => 'required|string|unique:produk,sku,' . $produkId . ',id_produk',
            'deskripsi' => 'required|string',
            'harga_dasar' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            // Gambar tidak perlu divalidasi di sini jika sudah divalidasi di Component (gambar_baru)
            'status' => 'required|in:aktif,tidak_aktif',
        ];
    }

    public function setForm(Produk $produk)
    {
        $this->produk = $produk;
        
        // Mapping data dari database ke properti form
        $this->nama = $produk->nama;
        $this->id_kategori = $produk->id_kategori;
        $this->sku = $produk->sku;
        $this->deskripsi = $produk->deskripsi;
        $this->harga_dasar = $produk->harga_dasar;
        $this->harga_jual = $produk->harga_jual;
        $this->stok = $produk->stok;
        $this->status = $produk->status;
        $this->gambar = $produk->gambar;
    }

    public function resetForm()
    {
        $this->reset();
        $this->produk = null;
    }

    public function store()
    {
        $this->validate();
        
        $data = [
            'nama'=> $this->nama,
            'id_kategori'=> $this->id_kategori,
            'sku'=> $this->sku,
            'deskripsi'=> $this->deskripsi,
            'harga_dasar'=> $this->harga_dasar,
            'harga_jual'=> $this->harga_jual,
            'stok'=> $this->stok,
            'status'=> $this->status,
        ];

        // Hanya update gambar jika ada gambar baru (pathGambar tidak null dari component)
        if ($this->gambar) {
            $data['gambar'] = $this->gambar;
        }

        if ($this->produk) {
            $this->produk->update($data);
        } else {
            Produk::create($data);
        }

        $this->resetForm();
    }
}