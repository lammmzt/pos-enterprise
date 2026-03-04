<?php

namespace App\Livewire\Admin\Testimoni;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Testimoni;
use App\Livewire\Forms\Admin\Testimoni\TestimoniForm;

class TestimoniIndex extends Component
{
    use WithPagination;

    public TestimoniForm $form;

    // Filter & Datatable State
    public $search = '';
    public $view = 5; // Item per page
    public $sortColumn = 'rating';
    public $sortDirection = 'asc';

    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $TestimoniIdToDelete = null;

    // Reset pagination saat pencarian atau view berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingView() { $this->resetPage(); }

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
    // Mengamankan variabel search
    $search = $this->search;

    $data['testimonis'] = Testimoni::with(['pesanan', 'user'])
        // Menggunakan when() agar pencarian hanya berjalan jika $search tidak kosong
        ->when($search, function ($query) use ($search) {
            // Bungkus dalam parameter closure agar orWhere tidak bocor ke query lain
            $query->where(function ($q) use ($search) {
                // Mencari berdasarkan nama di tabel relasi 'pesanan'
                $q->whereHas('pesanan', function ($qRel) use ($search) {
                    $qRel->where('nama', 'like', '%' . $search . '%');
                })
                // ATAU mencari di kolom tabel 'testimonis' itu sendiri
                ->orWhere('id_pesanan', 'like', '%' . $search . '%')
                ->orWhere('ulasan', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($this->view);

    $data['title'] = 'Manajemen Testimoni';
    $data['desc_page'] = 'Kelola master data Testimoni.';

    return view('livewire.admin.testimoni.testimoni-index', $data)
        ->layout('components.layouts.app', $data);
}

    public function create()
    {
        $this->form->resetForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Testimoni $testimoni)
    {
        $this->form->resetValidation();
        $this->form->setForm($testimoni);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Data pengaturan berhasil disimpan!');
        $this->isModalOpen = false;
    }

    public function deleteConfirm($id)
    {
        $this->TestimoniIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        if ($this->TestimoniIdToDelete) {
            Testimoni::findOrFail($this->TestimoniIdToDelete)->delete();
            $this->dispatch('toast', type: 'success', message: 'Data testimoni berhasil dihapus!');
        }
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
}