<?php

namespace App\Livewire\Admin\Pengaturan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pengaturan;
use App\Livewire\Forms\Admin\Pengaturan\PengaturanForm;

class PengaturanIndex extends Component
{
    use WithPagination;

    public PengaturanForm $form;

    // Filter & Datatable State
    public $search = '';
    public $view = 5; // Item per page
    public $sortColumn = 'kunci';
    public $sortDirection = 'asc';

    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $pengaturanIdToDelete = null;

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
        $data['pengaturans'] = Pengaturan::where('kunci', 'like', '%' . $this->search . '%')
            ->orWhere('nilai', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->view);
        $data['title'] = 'Manajemen Pengaturan';
        $data['desc_page'] = 'Kelola master data pengaturan.';
        return view('livewire.admin.pengaturan.pengaturan-index', $data)->layout('components.layouts.app', $data);
    }

    public function create()
    {
        $this->form->resetForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(Pengaturan $pengaturan)
    {
        $this->form->resetValidation();
        $this->form->setForm($pengaturan);
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
        $this->pengaturanIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        if ($this->pengaturanIdToDelete) {
            Pengaturan::findOrFail($this->pengaturanIdToDelete)->delete();
            $this->dispatch('toast', type: 'success', message: 'Data pengaturan berhasil dihapus!');
        }
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
}