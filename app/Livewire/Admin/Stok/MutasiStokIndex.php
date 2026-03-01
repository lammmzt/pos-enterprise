<?php

namespace App\Livewire\Admin\Stok;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MutasiStok;
use App\Models\Produk;
use App\Models\BatchProduk;
use App\Livewire\Forms\Admin\Stok\MutasiStokForm;

class MutasiStokIndex extends Component
{
    use WithPagination;

    public MutasiStokForm $form;

    public $search = '';
    public $view = 10;
    
    public $isModalOpen = false;
    public $isConfirmModalOpen = false;

    // Untuk menyimpan list batch yang muncul dinamis saat produk dipilih
    public $availableBatches = []; 

    public function updatingSearch() { $this->resetPage(); }
    public function updatingView() { $this->resetPage(); }

    public function render()
    {
        $data['mutasis'] = MutasiStok::with(['produk', 'batchProduk', 'user'])
            ->whereHas('produk', function($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orWhere('catatan', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->view);

        $data['produks'] = Produk::where('status', 'aktif')->orderBy('nama', 'asc')->get();

        $data['title'] = 'Penyesuaian & Mutasi Stok';
        $data['desc_page'] = 'Kelola penyesuaian stok, barang rusak, hilang, atau kedaluwarsa per Batch.';

        return view('livewire.admin.stok.mutasi-stok-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->availableBatches = [];
        $this->isModalOpen = true;
    }

    // Dipanggil oleh Alpine JS Select2 saat Produk dipilih
    public function productSelected($id_produk)
    {
        $this->form->id_produk = $id_produk;
        $this->form->id_batch = ''; // Reset pilihan batch
        
        if ($id_produk) {
            // Ambil semua batch dari produk ini (termasuk yang stok 0 jika butuh penyesuaian tambah)
            $this->availableBatches = BatchProduk::where('id_produk', $id_produk)
                                        ->orderBy('tanggal_kedaluwarsa', 'asc')
                                        ->get();
        } else {
            $this->availableBatches = [];
        }
    }

    public function confirmSave()
    {
        $this->form->validate();
        $this->isConfirmModalOpen = true;
    }

    public function executeSave()
    {
        try {
            $this->form->store();
            $this->dispatch('toast', type: 'success', message: 'Mutasi stok berhasil dicatat dan stok telah diupdate!');
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isConfirmModalOpen = false;
            // Lemparkan error kembali ke form jika validasi stok gagal
            foreach ($e->errors() as $key => $value) {
                $this->addError($key, $value[0]);
            }
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isConfirmModalOpen = false;
    }
}