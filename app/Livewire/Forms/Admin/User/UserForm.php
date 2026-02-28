<?php

namespace App\Livewire\Forms\Admin\User;

use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserForm extends Form
{
    public ?User $user = null;

    public $nama = '';
    public $username = '';
    public $alamat = '';
    public $password = '';
    public $no_hp = '';
    public $catatan = '';
    public $role = 'kasir';
    public $status = 'aktif';

    // Membuat rule validasi dinamis (menyesuaikan mode create/edit)
    public function rules()
    {
        $userId = $this->user ? $this->user->id_user : 'NULL';
        
        $rules = [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $userId . ',id_user',
            'alamat' => 'nullable|string|max:255',
            'role' => 'required|in:admin,owner,kasir,pelanggan',
            'no_hp' => 'required|numeric|min:10',
            'catatan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ];

        // Jika mode tambah, password wajib. Jika mode edit, password opsional
        $rules['password'] = $this->user ? 'nullable|string|min:6' : 'required|string|min:6';

        return $rules;
    }

    public function setForm(User $user)
    {
        $this->user = $user;
        $this->nama = $user->nama;
        $this->username = $user->username;
        $this->alamat = $user->alamat ?? '';
        $this->role = $user->role;
        $this->catatan = $user->catatan ?? '';
        $this->no_hp = $user->no_hp ?? '';
        $this->status = $user->status;
        $this->password = ''; // Dikosongkan, hanya diisi jika ingin diganti
    }

    public function resetForm()
    {
        $this->reset();
        $this->user = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'username' => $this->username,
            'alamat' => $this->alamat,
            'role' => $this->role,
            'no_hp' => $this->no_hp,
            'catatan' => $this->catatan,
            'status' => $this->status,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user) {
            $this->user->update($data); // Update
        } else {
            User::create($data); // Create
        }

        $this->resetForm();
    }
}