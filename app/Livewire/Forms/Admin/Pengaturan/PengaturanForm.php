<?php

namespace App\Livewire\Forms\Admin\Pengaturan;

use Livewire\Form;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Hash;

class PengaturanForm extends Form
{
    public ?Pengaturan $pengaturan = null;

    public $kunci = '';
    public $nilai = '';
    public $status = 'aktif';

    // Membuat rule validasi dinamis (menyesuaikan mode create/edit)
    public function rules()
    {
        $pengaturanId = $this->pengaturan ? $this->pengaturan->id_pengaturan : 'NULL';
        
        $rules = [
            'kunci' => 'required|string|unique:pengaturan,kunci,' . $pengaturanId . ',id_pengaturan',
            'nilai' => 'required|string',
            'status' => 'required|in:aktif,tidak_aktif',

        ];

        return $rules;
    }

    public function setForm(Pengaturan $pengaturan)
    {
        $this->pengaturan = $pengaturan;
        $this->kunci = $pengaturan->kunci;
        $this->nilai = $pengaturan->nilai; 
        $this->status = $pengaturan->status; 
    }

    public function resetForm()
    {
        $this->reset();
        $this->pengaturan = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'kunci'=> $this->kunci,
            'nilai'=> $this->nilai,
            'status'=> $this->status,
        ];

        if ($this->pengaturan) {
            $this->pengaturan->update($data); // Update
        } else {
            Pengaturan::create($data); // Create
        }

        $this->resetForm();
    }
}