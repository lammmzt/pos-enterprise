<?php

namespace App\Livewire\Admin\Stok;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pembelian;
use App\Models\Pemasok;
use App\Models\Produk;
use App\Livewire\Forms\Admin\Stok\PembelianForm;

class PembelianIndex extends Component
{
    use WithPagination;

    public PembelianForm $form;

    public $search = '';
    public $view = 10;
    
    public $isModalOpen = false;
    public $isConfirmModalOpen = false;
    public $isDetailModalOpen = false;
    public $detailData = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingView() { $this->resetPage(); }

    public function render()
    {
        $data['pembelians'] = Pembelian::with(['pemasok', 'user'])
            ->where('nomor_referensi', 'like', '%' . $this->search . '%')
            ->orderBy('tanggal_pembelian', 'desc')
            ->paginate($this->view);

        $data['pemasoks'] = Pemasok::orderBy('nama', 'asc')->get();
        $data['produks'] = Produk::where('status', 'aktif')->orderBy('nama', 'asc')->get();

        $data['title'] = 'Data Pembelian (PO)';
        $data['desc_page'] = 'Kelola pengadaan barang dan pencatatan batch produk masuk.';

        return view('livewire.admin.stok.pembelian-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->initForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    // METHOD BARU: Ter-trigger dari Blade saat Select2 Produk berubah
    public function productSelected($index, $id_produk)
    {
        $this->form->items[$index]['id_produk'] = $id_produk;
        
        if ($id_produk) {
            $produk = Produk::find($id_produk);
            if ($produk) {
                // Auto-fill harga dasar & harga jual
                $this->form->items[$index]['harga_satuan'] = $produk->harga_dasar;
                $this->form->items[$index]['harga_jual'] = $produk->harga_jual;
            }
        }
    }

    public function addItem()
    {
        $this->form->items[] = ['id_produk' => '', 'jumlah' => 1, 'harga_satuan' => 0, 'harga_jual' => 0, 'tanggal_kedaluwarsa' => ''];
    }

    public function removeItem($index)
    {
        unset($this->form->items[$index]);
        $this->form->items = array_values($this->form->items);
    }

    // METHOD BARU: Memicu validasi lalu membuka Modal Konfirmasi
    public function confirmSave()
    {
        $this->form->validate(); // Pastikan valid dulu sebelum konfirmasi
        $this->isConfirmModalOpen = true;
    }

    // Eksekusi Simpan setelah dikonfirmasi
    public function executeSave()
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Pembelian Berhasil! Stok, Master Harga & Batch telah terupdate.');
        $this->closeModal();
    }

    // METHOD BARU: Menampilkan Detail PO
    public function showDetail($id)
    {
        $this->detailData = Pembelian::with(['pemasok', 'user', 'detailPembelian.produk'])->find($id);
        $this->isDetailModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isConfirmModalOpen = false;
        $this->isDetailModalOpen = false;
        $this->detailData = null;
    }
}