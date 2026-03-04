<?php

namespace App\Livewire\Forms\Admin\Testimoni;

use Livewire\Form;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Hash;

class TestimoniForm extends Form
{
    public ?Testimoni $testimoni = null;

    public $id_pesanan = '';
    public $rating = '';
    public $ulasan = '';
    public $status_tampil = '1';

    // Membuat rule validasi dinamis (menyesuaikan mode create/edit)
    public function rules()
    {
        $testimoniId = $this->testimoni ? $this->testimoni->id_testimoni : 'NULL';
        
        $rules = [
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'rating' => 'required|numeric|max:5',
            'ulasan' => 'required|string',
            'status_tampil' => 'required|boolean',

        ];

        return $rules;
    }

    public function setForm(Testimoni $testimoni)
    {
        
        $this->testimoni = $testimoni;
        $this->id_pesanan = $testimoni->id_pesanan;
        $this->rating = $testimoni->rating;
        $this->ulasan = $testimoni->ulasan; 
        $this->status_tampil = $testimoni->status_tampil; 
    }

    public function resetForm()
    {
        $this->reset();
        $this->testimoni = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'id_pesanan'=> $this->id_pesanan,
            'rating'=> $this->rating,
            'ulasan'=> $this->ulasan,
            'status_tampil'=> $this->status_tampil,
        ];

        if ($this->testimoni) {
            $this->testimoni->update($data); // Update
        } else {
            Testimoni::create($data); // Create
        }

        $this->resetForm();
    }
}