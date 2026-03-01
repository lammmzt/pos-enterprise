<?php

namespace App\Livewire\Admin\Pemasok;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemasok;
use App\Livewire\Forms\Admin\Pemasok\PemasokForm;

class PemasokIndex extends Component
{
    use WithPagination;

    public PemasokForm $form;

    // Filter & Datatable State
    public $search = '';
    public $view = 5; // Item per page
    public $sortColumn = 'nama';
    public $sortDirection = 'asc';

    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $pemasokIdToDelete = null;

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
        $data['pemasoks'] = Pemasok::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->view);
        $data['title'] = 'Manajemen Pemasok';
        $data['desc_page'] = 'Kelola master data pemasok.';
        return view('livewire.admin.pemasok.pemasok-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->resetForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Pemasok $pemasok)
    {
        $this->form->resetValidation();
        $this->form->setForm($pemasok);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Data pemasok berhasil disimpan!');
        $this->isModalOpen = false;
    }

    public function deleteConfirm($id)
    {
        $this->pemasokIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        if ($this->pemasokIdToDelete) {
            Pemasok::findOrFail($this->pemasokIdToDelete)->delete();
            $this->dispatch('toast', type: 'success', message: 'Data pemasok berhasil dihapus!');
        }
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
}