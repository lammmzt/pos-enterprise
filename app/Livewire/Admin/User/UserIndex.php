<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Livewire\Forms\Admin\User\UserForm;

class UserIndex extends Component
{
    use WithPagination;

    public UserForm $form;

    // Filter & Datatable State
    public $search = '';
    public $view = 5; // Item per page
    public $sortColumn = 'nama';
    public $sortDirection = 'asc';

    // Modal State
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $userIdToDelete = null;

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
        
        $data['users'] = $users = User::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('alamat', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->view);
        $data['title'] = 'Manajemen Pengguna';
        $data['desc_page'] = 'Kelola master data pengguna, hak akses, dan status akun di sini.';
        return view('livewire.admin.user.user-index', $data);
    }

    public function create()
    {
        $this->form->resetForm();
        $this->form->resetValidation();
        $this->isModalOpen = true;
    }

    public function edit(User $user)
    {
        $this->form->resetValidation();
        $this->form->setForm($user);
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('toast', type: 'success', message: 'Data pengguna berhasil disimpan!');
        $this->isModalOpen = false;
    }

    public function deleteConfirm($id)
    {
        $this->userIdToDelete = $id;
        $this->isDeleteModalOpen = true;
    }

    public function destroy()
    {
        if ($this->userIdToDelete) {
            User::findOrFail($this->userIdToDelete)->delete();
            session()->flash('success', 'Data pengguna berhasil dihapus.');
        }
        $this->isDeleteModalOpen = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
    }
}