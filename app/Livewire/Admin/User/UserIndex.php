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
    public $isQrModalOpen = false; // STATE BARU UNTUK QR CODE
    
    public $userIdToDelete = null;
    public $qrUser = null; // MENYIMPAN DATA USER YANG DI-QR

    protected $paginationTheme = 'tailwind';

    // state untuk qrcode 
    public $selectedUsers = []; 
    public $qrUsersToPrint = [];

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
        // 1. Cek apakah user yang login adalah admin (mengembalikan true/false)
        $isAdmin = auth()->user()->role === 'admin';

        // 2. Inisialisasi awal query
        $query = User::query();

        // 3. Jika admin, KUNCI data agar hanya bisa melihat role 'pelanggan'
        if ($isAdmin) {
            $query->where('role', 'pelanggan');
        }

        // 4. Terapkan fitur pencarian (Gunakan Closure / Grouping agar tidak bocor)
        $query->where(function($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
              ->orWhere('username', 'like', '%' . $this->search . '%')
              ->orWhere('alamat', 'like', '%' . $this->search . '%');
        });

        // 5. Eksekusi pengurutan dan paginasi
        $data['users'] = $query->orderBy($this->sortColumn, $this->sortDirection)
                               ->paginate($this->view);
            
        $data['title'] = 'Manajemen Pengguna';
        $data['desc_page'] = 'Kelola master data pengguna, hak akses, dan status akun di sini.';
        
        return view('livewire.admin.user.user-index', $data)->layout('components.layouts.app', $data);
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
            $this->dispatch('toast', type: 'success', message: 'Data user berhasil dihapus!');
        }
        $this->isDeleteModalOpen = false;
    }

    // FUNGSI BARU: MENAMPILKAN MODAL QR CODE
    public function showQrCode(User $user)
    {
        $this->qrUsersToPrint = collect([$user]); // Jadikan collection agar bisa dilooping
        $this->isQrModalOpen = true;
    }

    public function printSelectedQr()
    {
        if (count($this->selectedUsers) > 0) {
            $this->qrUsersToPrint = User::whereIn('id_user', $this->selectedUsers)->get();
            $this->isQrModalOpen = true;
        }
    }

    // FUNGSI BARU: MENUTUP MODAL QR CODE
    public function closeQrModal()
    {
        $this->isQrModalOpen = false;
        $this->qrUsersToPrint = [];
        $this->selectedUsers = [];
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->isQrModalOpen = false;
    }
}