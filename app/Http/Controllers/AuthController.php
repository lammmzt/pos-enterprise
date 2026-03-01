<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Coba melakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // 3. Cek apakah akun aktif (Sesuai PRD)
            if ($user->status === 'tidak_aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->error('Pengguna tidak aktif', 404);
            }

            // 4. Jika sukses dan aktif, regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // 5. Redirect berdasarkan role
            // return $this->redirectBasedOnRole($user);
            return response()->success('Login berhasil', 200);
        }

        // 6. Jika gagal login
        return response()->error('Username atau password salah', 401);
    }

    /**
     * Memproses permintaan logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Helper untuk mengarahkan user berdasarkan role.
     */
    private function redirectBasedOnRole($user)
    {
        if (in_array($user->role, ['admin', 'owner', 'kasir'])) {
            return redirect()->intended(route('admin.dashboard'));
            // artinya adalah intended adalah redirect ke halaman sebelumnya
        }

        return redirect()->intended(route('home'));
    }
}