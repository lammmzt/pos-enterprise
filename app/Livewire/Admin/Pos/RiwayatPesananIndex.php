<?php

namespace App\Livewire\Admin\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pesanan;

class RiwayatPesananIndex extends Component
{
    use WithPagination;

    // Filter & Search
    public $search = '';
    public $filterStatus = '';
    public $filterTipe = '';
    public $filterTanggal = '';
    public $view = 10;

    // State Modal Detail
    public $isDetailModalOpen = false;
    public $detailPesanan = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterTipe() { $this->resetPage(); }
    public function updatingFilterTanggal() { $this->resetPage(); }

    public function render()
    {
        $query = Pesanan::with(['pelanggan', 'kasir', 'mangkuk.detailPesanan.produk']);

        // Filter Pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($userQuery) {
                      $userQuery->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter Status Pesanan
        if ($this->filterStatus) {
            $query->where('status_pesanan', $this->filterStatus);
        }

        // Filter Tipe Pesanan
        if ($this->filterTipe) {
            $query->where('tipe_pesanan', $this->filterTipe);
        }

        // Filter Tanggal
        if ($this->filterTanggal) {
            $query->whereDate('created_at', $this->filterTanggal);
        }

        $data['pesanans'] = $query->orderBy('created_at', 'desc')->paginate($this->view);
        $data['title'] = 'Riwayat Transaksi';
        $data['desc_page'] = 'Pantau seluruh riwayat pesanan, status pembayaran, dan cetak ulang struk.';

        return view('livewire.admin.pos.riwayat-pesanan-index', $data)
            ->layout('components.layouts.app', $data);
    }

    // --- FITUR LIHAT DETAIL ---
    public function showDetail($id)
    {
        $this->detailPesanan = Pesanan::with(['pelanggan', 'kasir', 'mangkuk.detailPesanan.produk', 'mangkuk.detailPesanan.batch'])->find($id);
        
        if ($this->detailPesanan) {
            $this->isDetailModalOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isDetailModalOpen = false;
        $this->detailPesanan = null;
    }

    // --- FITUR CETAK ULANG STRUK ---
    public function cetakUlangStruk($id_pesanan)
    {
        // Trigger event ke JS untuk buka popup print (sama seperti di POS)
        $this->dispatch('cetak-struk', id_pesanan: $id_pesanan);
    }
}