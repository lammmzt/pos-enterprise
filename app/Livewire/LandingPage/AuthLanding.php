<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\User;
use App\Models\VerifikasiOtp;
use App\Helpers\Fonnte;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthLanding extends Component
{
    public $tab = 'login'; // login, register, reset_password, reset_password

    // Form Properties
    public $nama, $no_hp, $password, $password_confirmation, $alamat, $catatan;
    
    // reset_password Password Properties
    public $reset_no_hp, $new_password, $new_password_confirmation;

    // OTP Properties
    public $showOtpModal = false;
    public $otp = ['', '', '', ''];
    public $tempUserId = null;
    public $otpContext = 'registrasi'; // 'registrasi' atau 'reset_password'

    public function render()
    {
        $data['title'] = 'Login & Daftar';
        $data['active'] = 'Auth';
        return view('livewire.landing-page.auth', $data)->layout('components.layouts.guest', $data);
    }

    public function switchTab($selectedTab)
    {
        $this->tab = $selectedTab;
        $this->resetValidation();
        session()->forget('error');
        session()->forget('success');
    }

    // --- REUSABLE METHOD UNTUK KIRIM OTP ---
    private function sendOtpCode($user, $konteks)
    {
        $kodeOtp = rand(1000, 9999);
        VerifikasiOtp::create([
            'id_user' => $user->id_user,
            'kode_otp' => $kodeOtp,
            'tipe' => $konteks,
            'waktu_kedaluwarsa' => Carbon::now()->addMinutes(5),
            'status_terpakai' => false,
        ]);

        $pesan = "*CUSTOMER SEBLAK-BUCIN*\n\nHalo {$user->nama}, ini adalah kode OTP Anda:\n\n*{$kodeOtp}*\n\nKode ini berlaku 5 menit. Jangan bagikan kepada siapapun.";
        Fonnte::send($user->no_hp, $pesan);

        $this->showOtpModal = true;
        // Trigger event ke Javascript (Alpine) untuk memulai hitung mundur 2 menit
        $this->dispatch('otp-sent'); 
    }

    // --- LOGIKA REGISTER ---
    public function register()
    {
        // jika no hp sudah ada dan satus tidak aktif, maka kirim ulang OTP
        $user = User::where('no_hp', $this->no_hp)->first();

        if ($user && $user->status == 'tidak_aktif') {
            $this->tempUserId = $user->id_user;
            $this->otpContext = 'registrasi';
            $this->sendOtpCode($user, 'registrasi');
            return;
        }
        
        $this->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|numeric|unique:users,no_hp',
            'password' => 'required|min:6|same:password_confirmation',
        ]);
        
        $user = User::create([
            'id_user' => uniqid(),
            'nama' => $this->nama,
            'no_hp' => $this->no_hp,
            'username' => $this->no_hp,
            'password' => Hash::make($this->password),
            'role' => 'pelanggan',
            'status' => 'tidak_aktif',
            'alamat' => $this->alamat,
            'catatan' => $this->catatan,
        ]);

        $this->tempUserId = $user->id_user;
        $this->otpContext = 'registrasi';
        $this->sendOtpCode($user, 'registrasi');
    }

    // --- LOGIKA LUPA PASSWORD ---
    public function forgotPassword()
    {
        $this->validate(['reset_no_hp' => 'required|numeric']);

        $user = User::where('no_hp', $this->reset_no_hp)->first();

        if (!$user) {
            session()->flash('error', 'Nomor HP tidak terdaftar di sistem kami.');
            return;
        }

        $this->tempUserId = $user->id_user;
        $this->otpContext = 'reset_password';
        $this->sendOtpCode($user, 'reset_password');
    }

    // --- LOGIKA KIRIM ULANG OTP ---
    public function resendOtp()
    {
        $user = User::find($this->tempUserId);
        if ($user) {
            $this->sendOtpCode($user, $this->otpContext);
        }
    }

    // --- LOGIKA VERIFIKASI OTP ---
    public function verifyOtp()
    {
        $inputOtp = implode('', $this->otp);

        if (strlen($inputOtp) < 4) {
            $this->addError('otp_error', 'Masukkan 4 digit OTP.');
            return;
        }

        $otpRecord = VerifikasiOtp::where('id_user', $this->tempUserId)
            ->where('kode_otp', $inputOtp)
            ->where('tipe', $this->otpContext)
            ->where('status_terpakai', false)
            ->latest()
            ->first();

        if (!$otpRecord || Carbon::now()->isAfter($otpRecord->waktu_kedaluwarsa)) {
            $this->addError('otp_error', 'Kode salah atau sudah kedaluwarsa.');
            return;
        }

        $otpRecord->update(['status_terpakai' => true]);
        
        if ($this->otpContext === 'registrasi') {
            $user = User::find($this->tempUserId);
            $user->save(['status' => 'aktif']);// kenapa erro disini? apa karena id buka int?
            Auth::login($user);
            return redirect()->route('Order'); 
        } elseif ($this->otpContext === 'reset_password') {
            $this->showOtpModal = false;
            $this->otp = ['', '', '', '']; // Reset OTP input
            $this->switchTab('reset_password'); // Buka form ganti password
        }
    }

    // --- LOGIKA SIMPAN PASSWORD BARU ---
    public function updatePassword()
    {
        $this->validate([
            'new_password' => 'required|min:6|same:new_password_confirmation'
        ]);

        $user = User::find($this->tempUserId);
        $user->update(['password' => Hash::make($this->new_password)]);
        
        session()->flash('success', 'Kata sandi berhasil diubah! Silakan masuk.');
        $this->switchTab('login');
    }

    // --- LOGIKA LOGIN ---
    public function login()
    {
        $this->validate(['no_hp' => 'required', 'password' => 'required']);
        $user = User::where('no_hp', $this->no_hp)->first();

        if ($user && Auth::attempt(['no_hp' => $this->no_hp, 'password' => $this->password])) {
            if ($user->status == 'aktif') {
                return redirect()->route('Order');
            }else{
                // mengaktifkan akun dengan otp
                $this->tempUserId = $user->id_user;
                $this->otpContext = 'login';
                $this->sendOtpCode($user, 'login');
            }
        }
        session()->flash('error', 'Nomor HP atau Password salah.');
    }
}