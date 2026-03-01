<?php

namespace App\Livewire\Admin\Pos;

use Livewire\Component;
use App\Models\Pesanan;

class PesananAktifIndex extends Component
{
    public $search = '';

    // State untuk Modal
    public $showModalPembayaran = false;
    public $showModalSelesai = false;
    public $showModalDelivery = false;

    // State untuk Data yang dipilih
    public $selectedPesananId = null;
    public $inputLinkDelivery = '';
    
    // State tambahan untuk info di Modal Pembayaran
    public $paymentTotal = 0;
    public $paymentMethod = '';
    public $paymentCustomer = '';

    public function render()
    {
        $query = Pesanan::with(['mangkuk.detailPesanan.produk', 'pelanggan', 'kasir'])
            ->where('status_pesanan', 'proses');

        if ($this->search) {
            $query->where('nomor_invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
        }

        $pesanans = $query->orderBy('created_at', 'asc')->get();

        return view('livewire.admin.pos.pesanan-aktif-index', [
            'pesanans' => $pesanans
        ])->layout('components.layouts.app', ['title' => 'Dapur: Pesanan Aktif']);
    }

    // --- FITUR PEMBAYARAN ---
    public function openModalPembayaran($id)
    {
        $this->selectedPesananId = $id;
        $pesanan = Pesanan::with('pelanggan')->find($id);
        
        if ($pesanan) {
            $this->paymentTotal = $pesanan->total_harga;
            $this->paymentMethod = $pesanan->metode_pembayaran ?? 'Belum ditentukan';
            $this->paymentCustomer = $pesanan->pelanggan->nama ?? 'Pelanggan Umum (Walk-in)';
            $this->showModalPembayaran = true;
        }
    }

    public function konfirmasiPembayaran()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        if ($pesanan) {
            $pesanan->update(['status_pembayaran' => 'lunas']);
            $this->dispatch('toast', type: 'success', message: 'Pembayaran berhasil dikonfirmasi!');
            
            // Trigger buka tab cetak struk otomatis
            $this->dispatch('cetak-struk', id_pesanan: $pesanan->id_pesanan);
        }
        $this->resetModal();
    }

    // --- FITUR UPDATE LINK DELIVERY ---
    public function openModalDelivery($id, $currentLink)
    {
        $this->selectedPesananId = $id;
        $this->inputLinkDelivery = $currentLink;
        $this->showModalDelivery = true;
    }

    public function simpanLinkDelivery()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        if ($pesanan) {
            // Update link dan otomatis selesaikan pesanan (karena sudah diserahkan ke kurir)
            $pesanan->update([
                'link_delivery' => $this->inputLinkDelivery,
                'status_pesanan' => 'selesai' 
            ]);
            $this->dispatch('toast', type: 'success', message: 'Link pengiriman disimpan & Pesanan selesai!');
        }
        $this->resetModal();
    }

    // --- FITUR SELESAI PESANAN (DINE-IN / TAKEAWAY) ---
    public function openModalSelesai($id)
    {
        $this->selectedPesananId = $id;
        $this->showModalSelesai = true;
    }

    public function selesaikanPesanan()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        
        if ($pesanan) {
            if ($pesanan->status_pembayaran === 'belum_bayar') {
                $this->dispatch('toast', type: 'error', message: 'Gagal! Pastikan pelanggan sudah membayar.');
                $this->resetModal();
                return;
            }

            $pesanan->update(['status_pesanan' => 'selesai']);
            $this->dispatch('toast', type: 'success', message: 'Pesanan ' . $pesanan->nomor_invoice . ' diselesaikan!');
        }
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->showModalPembayaran = false;
        $this->showModalSelesai = false;
        $this->showModalDelivery = false;
        $this->selectedPesananId = null;
        $this->inputLinkDelivery = '';
    }
}