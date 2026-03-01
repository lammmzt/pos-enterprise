<div x-data="{ tab: @entangle('tab') }">
    <div class="relative flex flex-col items-center justify-center min-h-screen overflow-hidden font-sans text-gray-800 transition-colors duration-300 select-none bg-gray-50 dark:bg-gray-900 dark:text-gray-100 tap-highlight-transparent">
        
        <div class="relative z-10 flex flex-col items-center justify-center w-full max-w-md px-4 pt-24 pb-12 overflow-hidden">
            
            <nav class="fixed top-0 left-0 right-0 z-50 transition-colors duration-300 border-b border-gray-200 glass-header dark:border-gray-800">
                <div class="flex items-center justify-between max-w-md px-4 py-3 mx-auto">
                    <a wire:navigate href="{{ route('Order') }}" class="flex items-center gap-2 text-gray-600 transition-colors dark:text-gray-300 hover:text-brand-red">
                        <i class="text-xl fa-solid fa-arrow-left"></i>
                        <span class="font-bold">Kembali</span>
                    </a>
                </div>
            </nav>

            <div class="w-full mb-8 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-2xl bg-white shadow-lg rounded-2xl dark:bg-gray-800 text-brand-red shadow-red-500/10">
                   <i class="text-3xl fa-solid fa-fire-flame-curved"></i>
                </div>
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-brand-orange">Seblak-Bucin</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Masuk atau daftar untuk pesan makan lebih mudah.</p>
            </div>

            @if (session()->has('error'))
                <div class="w-full p-3 mb-4 text-sm font-bold text-red-700 bg-red-100 border border-red-200 rounded-xl dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50">
                    <i class="mr-1 fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif
            @if (session()->has('success'))
                <div class="w-full p-3 mb-4 text-sm font-bold text-green-700 bg-green-100 border border-green-200 rounded-xl dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50">
                    <i class="mr-1 fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="w-full bg-white border border-gray-100 shadow-xl dark:bg-gray-800 rounded-[2rem] p-6 sm:p-8 dark:border-gray-700">
                
                <div x-show="tab === 'login' || tab === 'register'" class="flex p-1 mb-8 bg-gray-100 rounded-2xl dark:bg-gray-900">
                    <button wire:click="switchTab('login')" :class="tab === 'login' ? 'bg-white dark:bg-gray-800 shadow text-brand-red' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all">Masuk</button>
                    <button wire:click="switchTab('register')" :class="tab === 'register' ? 'bg-white dark:bg-gray-800 shadow text-brand-red' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all">Daftar</button>
                </div>

                <form wire:submit="login" x-show="tab === 'login'" x-transition.opacity>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Nomor HP / WhatsApp</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-phone left-4 top-1/2"></i>
                                <input type="number" wire:model="no_hp" placeholder="Contoh: 08123456789" class="w-full py-3 pl-11 pr-4 bg-gray-50 border @error('no_hp') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                            </div>
                            @error('no_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-lock left-4 top-1/2"></i>
                                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="••••••••" class="w-full py-3 pl-11 pr-12 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                                <button type="button" @click="show = !show" class="absolute text-gray-400 transform -translate-y-1/2 right-4 top-1/2 hover:text-brand-red focus:outline-none">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end mt-2">
                            <a href="#" wire:click.prevent="switchTab('forgot')" class="text-xs font-semibold text-brand-red hover:text-red-700">Lupa Password?</a>
                        </div>
                        
                        <button type="submit" wire:loading.attr="disabled" class="w-full py-3.5 mt-2 font-bold text-white transition-all shadow-lg rounded-xl bg-brand-red hover:bg-red-700 active:scale-95 shadow-red-500/20 disabled:opacity-50">
                            <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                            <span wire:loading wire:target="login"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </form>

                <form wire:submit="register" x-show="tab === 'register'" x-transition.opacity style="display: none;">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-user left-4 top-1/2"></i>
                                <input type="text" wire:model="nama" placeholder="Nama Anda" class="w-full py-3 pr-4 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            </div>
                            @error('nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Nomor WhatsApp Aktif</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-brands fa-whatsapp left-4 top-1/2"></i>
                                <input type="number" wire:model="no_hp" placeholder="08..." class="w-full py-3 pr-4 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            </div>
                            <p class="mt-1 text-[10px] text-gray-500">*Pastikan nomor aktif untuk menerima OTP</p>
                            @error('no_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div x-data="{ show: false }">
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Buat Password</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-lock left-4 top-1/2"></i>
                                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Min. 6 Karakter" class="w-full py-3 pr-12 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                <button type="button" @click="show = !show" class="absolute text-gray-400 transform -translate-y-1/2 right-4 top-1/2">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div x-data="{ show: false }">
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Ulangi Password</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-lock left-4 top-1/2"></i>
                                <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="Ulangi Password" class="w-full py-3 pr-12 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full py-3.5 mt-4 font-bold text-white transition-all shadow-lg rounded-xl bg-brand-red hover:bg-red-700 active:scale-95 shadow-red-500/20 disabled:opacity-50">
                            <span wire:loading.remove wire:target="register">Daftar Akun</span>
                            <span wire:loading wire:target="register"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Mengirim OTP...</span>
                        </button>
                    </div>
                </form>

                <form wire:submit="forgotPassword" x-show="tab === 'forgot'" x-transition.opacity style="display: none;">
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold dark:text-white">Lupa Kata Sandi?</h2>
                        <p class="text-sm text-gray-500">Masukkan nomor WA Anda yang terdaftar, kami akan mengirimkan OTP untuk reset password.</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Nomor WhatsApp Terdaftar</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-brands fa-whatsapp left-4 top-1/2"></i>
                                <input type="number" wire:model="reset_no_hp" placeholder="Contoh: 08123456789" class="w-full py-3 pl-11 pr-4 bg-gray-50 border @error('reset_no_hp') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                            </div>
                            @error('reset_no_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full py-3.5 mt-4 font-bold text-white transition-all shadow-lg rounded-xl bg-brand-red hover:bg-red-700 active:scale-95 disabled:opacity-50">
                            <span wire:loading.remove wire:target="forgotPassword">Kirim Kode OTP</span>
                            <span wire:loading wire:target="forgotPassword"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Mencari Nomor...</span>
                        </button>

                        <button type="button" wire:click="switchTab('login')" class="w-full py-3 font-bold text-gray-500 transition-all rounded-xl hover:text-gray-800 dark:hover:text-white">
                            Kembali ke Masuk
                        </button>
                    </div>
                </form>

                <form wire:submit="updatePassword" x-show="tab === 'reset_password'" x-transition.opacity style="display: none;">
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold dark:text-white">Buat Sandi Baru</h2>
                        <p class="text-sm text-gray-500">Silakan buat kata sandi baru untuk akun Anda.</p>
                    </div>
                    <div class="space-y-4">
                        <div x-data="{ show: false }">
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-lock left-4 top-1/2"></i>
                                <input :type="show ? 'text' : 'password'" wire:model="new_password" placeholder="Min. 6 Karakter" class="w-full py-3 pr-12 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                <button type="button" @click="show = !show" class="absolute text-gray-400 transform -translate-y-1/2 right-4 top-1/2">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('new_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div x-data="{ show: false }">
                            <label class="block mb-1.5 text-sm font-bold text-gray-700 dark:text-gray-300">Konfirmasi Sandi Baru</label>
                            <div class="relative">
                                <i class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-lock left-4 top-1/2"></i>
                                <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation" placeholder="Ulangi Sandi" class="w-full py-3 pr-12 border border-gray-200 outline-none pl-11 bg-gray-50 rounded-xl focus:ring-2 focus:ring-brand-red dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full py-3.5 mt-4 font-bold text-white transition-all shadow-lg rounded-xl bg-brand-red hover:bg-red-700 active:scale-95 disabled:opacity-50">
                            <span wire:loading.remove wire:target="updatePassword">Simpan Kata Sandi</span>
                            <span wire:loading wire:target="updatePassword"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div x-data="{ 
            timer: 120, 
            canResend: false, 
            interval: null,
            startTimer() {
                this.timer = 120;
                this.canResend = false;
                clearInterval(this.interval);
                this.interval = setInterval(() => {
                    if (this.timer > 0) {
                        this.timer--;
                    } else {
                        this.canResend = true;
                        clearInterval(this.interval);
                    }
                }, 1000);
            },
            formatTime(seconds) {
                let m = Math.floor(seconds / 60).toString().padStart(2, '0');
                let s = (seconds % 60).toString().padStart(2, '0');
                return m + ':' + s;
            },
            focusNext(event, index) {
                if (event.key === 'Backspace' && index > 0) {
                    $refs['otp' + (index - 1)].focus();
                } else if (event.target.value.length === 1 && index < 3) {
                    $refs['otp' + (index + 1)].focus();
                }
            }
         }" 
         @otp-sent.window="startTimer()" 
         x-show="$wire.showOtpModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak>
         
        <div x-show="$wire.showOtpModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm p-8 text-center bg-white shadow-2xl dark:bg-gray-800 rounded-3xl">
            
            <button @click="$wire.showOtpModal = false" class="absolute text-gray-400 top-4 right-5 hover:text-gray-600 dark:hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>

            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-2xl rounded-full bg-brand-red/10 text-brand-red">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            
            <h3 class="mb-2 text-xl font-bold dark:text-white">Verifikasi OTP</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                Masukkan 4 digit kode yang dikirim ke <br><span class="font-bold text-gray-800 dark:text-gray-200">{{ $otpContext === 'forgot' ? $reset_no_hp : $no_hp }}</span>
            </p>

            @error('otp_error')
                <div class="mb-4 text-xs font-bold text-red-500">{{ $message }}</div>
            @enderror

            <div class="flex justify-center gap-3 mb-6" dir="ltr">
                <input type="text" inputmode="numeric" maxlength="1" x-ref="otp0" wire:model="otp.0" @keyup="focusNext($event, 0)" class="w-12 h-14 text-2xl font-black text-center text-gray-800 bg-gray-100 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                <input type="text" inputmode="numeric" maxlength="1" x-ref="otp1" wire:model="otp.1" @keyup="focusNext($event, 1)" class="w-12 h-14 text-2xl font-black text-center text-gray-800 bg-gray-100 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                <input type="text" inputmode="numeric" maxlength="1" x-ref="otp2" wire:model="otp.2" @keyup="focusNext($event, 2)" class="w-12 h-14 text-2xl font-black text-center text-gray-800 bg-gray-100 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
                <input type="text" inputmode="numeric" maxlength="1" x-ref="otp3" wire:model="otp.3" @keyup="focusNext($event, 3)" class="w-12 h-14 text-2xl font-black text-center text-gray-800 bg-gray-100 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-brand-red outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-white transition-all">
            </div>

            <button wire:click="verifyOtp" wire:loading.attr="disabled" class="w-full py-3.5 font-bold text-white transition-all rounded-xl bg-brand-red hover:bg-red-700 shadow-lg shadow-red-500/20 active:scale-95 disabled:opacity-50">
                <span wire:loading.remove wire:target="verifyOtp">Verifikasi OTP</span>
                <span wire:loading wire:target="verifyOtp"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Memeriksa...</span>
            </button>
            
            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                <template x-if="!canResend">
                    <span>Kirim ulang kode dalam <span x-text="formatTime(timer)" class="font-bold text-gray-700 dark:text-gray-200"></span></span>
                </template>
                <template x-if="canResend">
                    <span>Belum menerima kode? <a href="#" wire:click.prevent="resendOtp" class="font-bold text-brand-red hover:text-red-700">Kirim Ulang</a></span>
                </template>
            </p>
        </div>
    </div>
</div>