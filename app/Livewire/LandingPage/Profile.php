<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Profile extends Component
{
    public $nama, $username, $no_hp, $alamat, $password, $password_confirmation;
    public $isAntrean = false;

    public function mount()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('Auth');

        $this->nama = $user->nama;
        $this->username = $user->username;
        $this->no_hp = $user->nomor_hp ?? $user->no_hp;
        $this->alamat = $user->alamat ?? '';

        // Deteksi akun kasir/antrean
        $this->isAntrean = str_starts_with(strtolower($this->username), 'antrean_');
    }

    public function updateProfile()
    {
        if ($this->isAntrean) {
            $this->dispatch('toast', type: 'error', message: 'Akses ditolak: Akun Antrean tidak dapat diubah.');
            return;
        }

        $this->validate([
            'nama' => 'required|min:3',
            'no_hp' => 'required|numeric',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'nama' => $this->nama,
            'nomor_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui ✨');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('Auth');
    }

    public function render()
    {
        $data['title'] = 'Akun Saya';
        $data['active'] = 'Profile';
        return view('livewire.landing-page.profile', $data)->layout('components.layouts.guest', $data);
    }
}