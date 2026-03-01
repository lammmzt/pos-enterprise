<?php

namespace App\Livewire\Admin\Kategori;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kategori;
use App\Livewire\Forms\Admin\Kategori\KategoriForm;

class KategoriIndex extends Component
{
    use WithPagination;

    public KategoriForm $form;

    // Filter & Datatable State
    public $search = '';
    public $view = 5; // Item per page
    public $sortColumn = 'nama';
    public $sortDirection = 'asc';

    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $kategoriIdToDelete = null;

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
        $data['kategoris'] =  Kategori::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->view);
        $data['title'] = 'Manajemen Kategori';
        $data['desc_page'] = 'Kelola master data kategori.';
        return view('livewire.admin.kategori.ketegori-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->resetForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Kategori $kategori)
    {
        $this->form->resetValidation();
        $this->form->setForm($kategori);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Data kategori berhasil disimpan!');
        $this->isModalOpen = false;
    }

    public function deleteConfirm($id)
    {
        $this->kategoriIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        if ($this->kategoriIdToDelete) {
            Kategori::findOrFail($this->kategoriIdToDelete)->delete();
            session()->flash('success', 'Data kategori berhasil dihapus.');
        }
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
}