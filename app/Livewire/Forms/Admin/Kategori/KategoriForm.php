<?php

namespace App\Livewire\Forms\Admin\Kategori;

use Livewire\Form;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;

class KategoriForm extends Form
{
    public ?Kategori $kategori = null;

    public $nama = '';
    public $deskripsi = '';
    public $status = 'aktif';

    // Membuat rule validasi dinamis (menyesuaikan mode create/edit)
    public function rules()
    {
        $kategoriId = $this->kategori ? $this->kategori->id_kategori : 'NULL';
        
        $rules = [
            'nama' => 'required|string|unique:kategori,nama,' . $kategoriId . ',id_kategori',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,tidak_aktif',

        ];

        return $rules;
    }

    public function setForm(Kategori $kategori)
    {
        $this->kategori = $kategori;
        $this->nama = $kategori->nama;
        $this->deskripsi = $kategori->deskripsi; 
        $this->status = $kategori->status; 
    }

    public function resetForm()
    {
        $this->reset();
        $this->kategori = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'nama'=> $this->nama,
            'deskripsi'=> $this->deskripsi,
            'status'=> $this->status,
        ];

        if ($this->kategori) {
            $this->kategori->update($data); // Update
        } else {
            Kategori::create($data); // Create
        }

        $this->resetForm();
    }
}