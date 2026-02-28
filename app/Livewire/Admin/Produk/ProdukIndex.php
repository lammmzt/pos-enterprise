<?php

namespace App\Livewire\Admin\Produk;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Produk;
use App\Models\Kategori;
use App\Livewire\Forms\Admin\Produk\ProdukForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukIndex extends Component
{
    use WithPagination, WithFileUploads;

    public ProdukForm $form;

    public $search = '';
    public $view = 5;
    public $sortColumn = 'nama';
    public $sortDirection = 'asc';

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $produkIdToDelete = null;
    public $gambar_baru; 

    public function updatingSearch() { $this->resetPage(); }
    public function updatingView() { $this->resetPage(); }

    // Fitur: Auto Generate SKU saat Kategori Dipilih
    public function updatedFormIdKategori($value)
    {
        // Hanya generate jika sedang tambah data baru (bukan edit)
        // Atau jika Anda ingin tetap berubah saat edit, hapus kondisi is_null ini
        if (is_null($this->form->produk) && $value) {
            $kategori = Kategori::find($value);
            if ($kategori) {
                // Format: PREFIX_KATEGORI-TAHUNBULAN-RANDOM
                // Contoh: MNN-2310-4829 (MNN dari Minuman)
                $prefix = strtoupper(Str::limit($kategori->nama ?? 'PRD', 3, ''));
                $this->form->sku = $prefix . '-' . date('ym') . '-' . mt_rand(1000, 9999);
            }
        }
    }

    public function sort($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        // Gunakan with('kategori') untuk eager loading (N+1 Query fix)
        $data['produks'] = Produk::with('kategori')
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->view);
            
        // Kirim data kategori ke View untuk dropdown
        $data['kategoris'] = Kategori::all();

        $data['title'] = 'Manajemen Produk';
        $data['desc_page'] = 'Kelola master data produk.';
        
        return view('livewire.admin.produk.produk-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->resetForm();
        $this->reset('gambar_baru');
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Produk $produk)
    {
        $this->form->resetValidation();
        $this->reset('gambar_baru');
        $this->form->setForm($produk);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $pathGambar = null;

        if ($this->gambar_baru) {
            $this->validate(['gambar_baru' => 'image|max:2048']); 
            $pathGambar = $this->gambar_baru->store('produk', 'public');
            
            if ($this->form->produk && $this->form->produk->gambar) {
                Storage::disk('public')->delete($this->form->produk->gambar);
            }
        }

        // Jika ada gambar baru, assign ke form. Jika tidak, abaikan (pakai gambar lama)
        if ($pathGambar) {
            $this->form->gambar = $pathGambar;
        }

        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Data produk berhasil disimpan!');
        $this->isModalOpen = false;
    }

    public function deleteConfirm($id)
    {
        $this->produkIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        // Perbaikan: Sebelumnya $this->deleteId, diubah menjadi $this->produkIdToDelete
        $produk = Produk::find($this->produkIdToDelete);
        
        if ($produk) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $produk->delete();
            $this->dispatch('toast', type: 'success', message: 'Data produk berhasil dihapus!');
        }
        
        $this->isDeleteModalOpen = false;
        $this->produkIdToDelete = null;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset('gambar_baru');
    }
}