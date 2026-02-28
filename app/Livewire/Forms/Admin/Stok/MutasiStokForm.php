<?php

namespace App\Livewire\Forms\Admin\Stok;

use Livewire\Form;
use App\Models\MutasiStok;
use App\Models\BatchProduk;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MutasiStokForm extends Form
{
    public $id_produk = '';
    public $id_batch = '';
    public $tipe = 'kedaluwarsa'; // enum: masuk, keluar, penyesuaian, kedaluwarsa
    public $jumlah = 1;
    public $catatan = '';

    public function rules()
    {
        return [
            'id_produk' => 'required|exists:produk,id_produk',
            'id_batch'  => 'required|exists:batch_produk,id_batch',
            'tipe'      => 'required|in:masuk,keluar,penyesuaian,kedaluwarsa',
            'jumlah'    => 'required|numeric|min:1',
            'catatan'   => 'required|string|max:255',
        ];
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $batch = BatchProduk::lockForUpdate()->find($this->id_batch);
            $produk = Produk::lockForUpdate()->find($this->id_produk);

            // Tentukan apakah ini operasi pengurangan atau penambahan stok
            $isPengurangan = in_array($this->tipe, ['keluar', 'kedaluwarsa', 'penyesuaian_kurang']);

            // Jika ini penyesuaian (generic), kita asumsikan PRD Anda menggunakan 'penyesuaian' untuk kurang/tambah.
            // Di UI nanti kita mapping: 'kedaluwarsa' (kurang), 'keluar' (rusak/hilang - kurang), 'masuk' (tambah manual).
            
            if (in_array($this->tipe, ['keluar', 'kedaluwarsa'])) {
                if ($this->jumlah > $batch->jumlah) {
                    throw ValidationException::withMessages([
                        'form.jumlah' => 'Jumlah mutasi melebihi stok yang ada di Batch ini! (Maks: ' . $batch->jumlah . ')'
                    ]);
                }
                
                $batch->decrement('jumlah', $this->jumlah);
                $produk->decrement('stok', $this->jumlah);
            } else {
                // Tipe 'masuk' atau 'penyesuaian' (tambah)
                $batch->increment('jumlah', $this->jumlah);
                $produk->increment('stok', $this->jumlah);
            }

            // Catat riwayat mutasi
            MutasiStok::create([
                'id_produk'      => $this->id_produk,
                'id_batch'       => $this->id_batch,
                'id_user'        => Auth::id(),
                'tipe'           => $this->tipe,
                'jumlah'         => $this->jumlah,
                'tipe_referensi' => 'Manual',
                'catatan'        => $this->catatan,
            ]);
        });

        $this->reset();
    }
}