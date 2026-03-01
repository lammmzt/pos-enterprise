<?php

namespace App\Livewire\Forms\Admin\Pemasok;

use Livewire\Form;
use App\Models\Pemasok;

class PemasokForm extends Form
{
    public ?Pemasok $pemasok = null;

    public $nama = '';
    public $telepon = '';
    public $email = '';
    public $alamat = '';
    
    // Membuat rule validasi dinamis (menyesuaikan mode create/edit)
    public function rules()
    {
        $pemasokId = $this->pemasok ? $this->pemasok->id_pemasok : 'NULL';
        
        $rules = [
            'nama' => 'required|string|unique:pemasok,nama,' . $pemasokId . ',id_pemasok',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
        ];

        return $rules;
    }

    public function setForm(Pemasok $pemasok)
    {
        $this->pemasok = $pemasok;
        $this->nama = $pemasok->nama;
        $this->telepon = $pemasok->telepon; 
        $this->email = $pemasok->email; 
        $this->alamat = $pemasok->alamat; 
    }

    public function resetForm()
    {
        $this->reset();
        $this->pemasok = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'nama'=> $this->nama,
            'telepon'=> $this->telepon,
            'email'=> $this->email,
            'alamat'=> $this->alamat,
        ];

        if ($this->pemasok) {
            $this->pemasok->update($data); // Update
        } else {
            Pemasok::create($data); // Create
        }

        $this->resetForm();
    }
}