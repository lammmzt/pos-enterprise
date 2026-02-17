<div>
    <div
        class="relative flex flex-col items-center justify-center min-h-screen overflow-hidden font-sans text-gray-800 transition-colors duration-300 select-none bg-gray-50 dark:bg-gray-900 dark:text-gray-100 tap-highlight-transparent">
        <div class="relative flex flex-col items-center justify-center overflow-hidden">
            <!-- Background Elements (Decorative) -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
                <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-brand-red/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-brand-orange/10 rounded-full blur-3xl">
                </div>
            </div>

            <!-- NAVBAR (Simple Version) -->
            <nav
                class="fixed top-0 left-0 right-0 z-50 transition-colors duration-300 border-b border-gray-200 glass-header dark:border-gray-800">
                <div class="flex items-center justify-between max-w-md px-4 py-3 mx-auto">
                    <a wire:navigate href="{{ route('Order') }}"
                        class="flex items-center gap-2 text-gray-600 transition-colors dark:text-gray-300 hover:text-brand-red">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span class="text-sm font-bold">Kembali</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <div
                            class="flex items-center justify-center w-8 h-8 text-white rounded-lg shadow-md bg-gradient-to-br from-brand-red to-orange-500 rotate-3">
                            <i class="text-xs fa-solid fa-fire-flame-curved"></i>
                        </div>
                        <h1 class="hidden text-lg font-extrabold text-gray-800 dark:text-white sm:block">
                            Seblak<span class="text-brand-red">Bucin</span>
                        </h1>
                    </div>
                    <button onclick="toggleDarkMode()"
                        class="flex items-center justify-center text-gray-500 transition-all bg-gray-100 border border-transparent rounded-full w-9 h-9 dark:bg-gray-800 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-gray-700 active:scale-95 dark:border-gray-700">
                        <i id="dark-mode-icon" class="text-sm fa-solid fa-moon"></i>
                    </button>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="w-full max-w-md p-6 pt-24 pb-10 animate-slide-up">

                <!-- Auth Card -->
                <div
                    class="relative overflow-hidden transition-colors bg-white border border-gray-100 shadow-xl dark:bg-gray-800 rounded-3xl dark:border-gray-700">

                    <!-- Tabs Header -->
                    <div class="flex border-b border-gray-100 dark:border-gray-700">
                        <button onclick="switchTab('login')" id="tab-login"
                            class="flex-1 py-4 text-sm font-bold text-center transition-all border-b-2 text-brand-red border-brand-red hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            Masuk
                        </button>
                        <button onclick="switchTab('register')" id="tab-register"
                            class="flex-1 py-4 text-sm font-bold text-center text-gray-400 transition-all border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            Daftar
                        </button>
                    </div>

                    <div class="p-6 sm:p-8">

                        <!-- LOGIN FORM -->
                        <form id="form-login" class="space-y-5" onsubmit="handleLogin(event)">
                            <div class="mb-6 text-center">
                                <h2 class="mb-1 text-2xl font-extrabold text-gray-900 dark:text-white">Selamat Datang!
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Masuk untuk mulai memesan seblak
                                    favoritmu.</p>
                            </div>

                            <div>
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase dark:text-gray-300">Nomor
                                    WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                            class="text-lg fa-brands fa-whatsapp"></i></span>
                                    <input type="tel" required placeholder="0812xxxx"
                                        class="w-full py-3 pr-4 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block mb-2 text-xs font-bold tracking-wide text-gray-700 uppercase dark:text-gray-300">Kata
                                    Sandi</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                            class="fa-solid fa-lock"></i></span>
                                    <input id="login-pass" type="password" required placeholder="••••••••"
                                        class="w-full py-3 pr-10 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                    <button type="button" onclick="togglePassword('login-pass', this)"
                                        class="absolute text-gray-400 transition-colors -translate-y-1/2 right-4 top-1/2 hover:text-brand-red">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-1 text-right">
                                    <a href="#" onclick="openForgotModal(event)"
                                        class="text-xs text-brand-red hover:underline">Lupa kata sandi?</a>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-red hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-500/30 transition-all active:scale-95 flex justify-center items-center gap-2">
                                <span>Masuk Sekarang</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>

                        <!-- REGISTER FORM (Hidden by default) -->
                        <form id="form-register" class="hidden space-y-4" onsubmit="handleRegister(event)">
                            <div class="mb-6 text-center">
                                <h2 class="mb-1 text-2xl font-extrabold text-gray-900 dark:text-white">Buat Akun Baru
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Isi data diri untuk bergabung jadi
                                    Bucin.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Nomor
                                    WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                            class="text-lg fa-brands fa-whatsapp"></i></span>
                                    <input type="tel" required placeholder="0812xxxx"
                                        class="w-full py-3 pr-4 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Kata
                                    Sandi</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                            class="fa-solid fa-lock"></i></span>
                                    <input id="reg-pass" type="password" required placeholder="Buat kata sandi aman"
                                        class="w-full py-3 pr-10 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                    <button type="button" onclick="togglePassword('reg-pass', this)"
                                        class="absolute text-gray-400 transition-colors -translate-y-1/2 right-4 top-1/2 hover:text-brand-red">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Konfirmasi
                                    Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                            class="fa-solid fa-lock"></i></span>
                                    <input id="reg-pass-confirm" type="password" required
                                        placeholder="Ulangi kata sandi"
                                        class="w-full py-3 pr-10 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                    <button type="button" onclick="togglePassword('reg-pass-confirm', this)"
                                        class="absolute text-gray-400 transition-colors -translate-y-1/2 right-4 top-1/2 hover:text-brand-red">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Alamat
                                    Rumah Lengkap</label>
                                <div class="relative">
                                    <span class="absolute text-gray-400 left-4 top-3"><i
                                            class="fa-solid fa-map-location-dot"></i></span>
                                    <textarea required rows="3" placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan..."
                                        class="w-full py-3 pr-4 text-sm transition-all border border-gray-200 outline-none resize-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white"></textarea>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" id="data-check" required
                                    class="w-4 h-4 border-gray-300 rounded text-brand-red focus:ring-brand-red bg-gray-50 dark:bg-gray-800 dark:border-gray-600">
                                <label for="data-check"
                                    class="text-xs text-gray-600 cursor-pointer select-none dark:text-gray-400">
                                    Saya menyatakan data di atas sudah benar.
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-dark hover:bg-gray-800 dark:bg-brand-red dark:hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-gray-500/20 dark:shadow-red-500/30 transition-all active:scale-95 mt-2">
                                Simpan & Daftar
                            </button>
                        </form>

                    </div>
                </div>

                <p class="mt-6 text-xs text-center text-gray-400">&copy; 2026 Seblak Bucin. All rights reserved.</p>
            </div>

            <!-- OTP MODAL OVERLAY -->
            <div id="otp-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4"  >
                <!-- Backdrop -->
                <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm">
                </div>

                <!-- Content -->
                <div
                    class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-slide-up">
                    <div
                        class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl text-green-600 bg-green-100 rounded-full dark:bg-green-900/30">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>

                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Verifikasi OTP</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Kode OTP telah dikirim ke WhatsApp Anda. Silakan masukkan kode 4 digit di bawah ini.
                    </p>

                    <div class="flex justify-center gap-3 mb-6">
                        <input type="text" maxlength="1"
                            class="w-12 text-xl font-bold text-center transition-all border border-gray-300 outline-none otp-input h-14 rounded-xl dark:border-gray-600 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 bg-gray-50 dark:bg-gray-900 dark:text-white"
                            oninput="focusNext(this)">
                        <input type="text" maxlength="1"
                            class="w-12 text-xl font-bold text-center transition-all border border-gray-300 outline-none otp-input h-14 rounded-xl dark:border-gray-600 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 bg-gray-50 dark:bg-gray-900 dark:text-white"
                            oninput="focusNext(this)">
                        <input type="text" maxlength="1"
                            class="w-12 text-xl font-bold text-center transition-all border border-gray-300 outline-none otp-input h-14 rounded-xl dark:border-gray-600 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 bg-gray-50 dark:bg-gray-900 dark:text-white"
                            oninput="focusNext(this)">
                        <input type="text" maxlength="1"
                            class="w-12 text-xl font-bold text-center transition-all border border-gray-300 outline-none otp-input h-14 rounded-xl dark:border-gray-600 focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 bg-gray-50 dark:bg-gray-900 dark:text-white"
                            oninput="focusNext(this)">
                    </div>

                    <button onclick="verifyOtp()"
                        class="w-full py-3 mb-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl active:scale-95">
                        Konfirmasi
                    </button>
                    <button onclick="closeOtp()" class="text-sm text-gray-400 transition-colors hover:text-brand-red">
                        Batal / Kirim Ulang
                    </button>
                </div>
            </div>

            <!-- FORGOT PASSWORD MODAL (Input WA) -->
            <div id="forgot-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4"  >
                <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm">
                </div>
                <div
                    class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-slide-up">
                    <div
                        class="flex items-center justify-center mx-auto mb-4 text-2xl bg-orange-100 rounded-full w-14 h-14 dark:bg-orange-900/30 text-brand-orange">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Lupa Kata Sandi?</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Masukkan nomor WhatsApp Anda untuk menerima
                        kode verifikasi OTP.</p>

                    <form onsubmit="handleForgotRequest(event)">
                        <div class="relative mb-6">
                            <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                    class="text-lg fa-brands fa-whatsapp"></i></span>
                            <input type="tel" required placeholder="0812xxxx"
                                class="w-full py-3 pr-4 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                        </div>
                        <button type="submit"
                            class="w-full py-3 mb-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl active:scale-95">
                            Kirim OTP
                        </button>
                        <button type="button" onclick="closeForgotModal()"
                            class="text-sm text-gray-400 transition-colors hover:text-brand-red">
                            Batal
                        </button>
                    </form>
                </div>
            </div>

            <!-- RESET PASSWORD MODAL (New Password) -->
            <div id="reset-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4"  >
                <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>
                <div
                    class="relative z-10 w-full max-w-sm p-6 transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-slide-up">
                    <h3 class="mb-4 text-xl font-bold text-center text-gray-900 dark:text-white">Buat Kata Sandi Baru
                    </h3>

                    <form onsubmit="handleResetSubmit(event)" class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Kata
                                Sandi Baru</label>
                            <div class="relative">
                                <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                        class="fa-solid fa-lock"></i></span>
                                <input id="new-pass" type="password" required placeholder="Kata sandi baru"
                                    class="w-full py-3 pr-10 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                <button type="button" onclick="togglePassword('new-pass', this)"
                                    class="absolute text-gray-400 transition-colors -translate-y-1/2 right-4 top-1/2 hover:text-brand-red">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1.5">Konfirmasi
                                Kata Sandi Baru</label>
                            <div class="relative">
                                <span class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2"><i
                                        class="fa-solid fa-lock"></i></span>
                                <input id="new-pass-confirm" type="password" required placeholder="Ulangi kata sandi"
                                    class="w-full py-3 pr-10 text-sm transition-all border border-gray-200 outline-none pl-11 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red dark:text-white">
                                <button type="button" onclick="togglePassword('new-pass-confirm', this)"
                                    class="absolute text-gray-400 transition-colors -translate-y-1/2 right-4 top-1/2 hover:text-brand-red">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3 mt-2 font-bold text-white transition-all shadow-lg bg-brand-dark hover:bg-gray-800 dark:bg-brand-red dark:hover:bg-red-700 rounded-xl active:scale-95">
                            Simpan Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- JAVASCRIPT LOGIC -->
    <script>
        window.currentOtpAction = window.currentOtpAction || 'register';
        // --- Tab Switching Logic ---
        function switchTab(tab) {
            const loginForm = document.getElementById('form-login');
            const registerForm = document.getElementById('form-register');
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabLogin.classList.add('text-brand-red', 'border-brand-red');
                tabLogin.classList.remove('text-gray-400', 'border-transparent');
                tabRegister.classList.add('text-gray-400', 'border-transparent');
                tabRegister.classList.remove('text-brand-red', 'border-brand-red');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                tabRegister.classList.add('text-brand-red', 'border-brand-red');
                tabRegister.classList.remove('text-gray-400', 'border-transparent');
                tabLogin.classList.add('text-gray-400', 'border-transparent');
                tabLogin.classList.remove('text-brand-red', 'border-brand-red');
            }
        }

        // --- Password Visibility Toggle ---
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // --- Form Handlers ---
        function handleLogin(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Memuat...`;
            btn.disabled = true;
            setTimeout(() => {
                alert("Login Berhasil! (Simulasi)");
                window.location.href = 'index.html';
            }, 1500);
        }

        function handleRegister(e) {
            e.preventDefault();
            const pass = document.getElementById('reg-pass').value;
            const confirmPass = document.getElementById('reg-pass-confirm').value;

            if (pass !== confirmPass) {
                alert("Konfirmasi kata sandi tidak cocok!");
                return;
            }

            currentOtpAction = 'register';
            document.getElementById('otp-modal').classList.remove('hidden');
            setTimeout(() => document.querySelector('.otp-input').focus(), 100);
        }

        // --- Forgot Password Flow ---
        function openForgotModal(e) {
            e.preventDefault();
            document.getElementById('forgot-modal').classList.remove('hidden');
        }

        function closeForgotModal() {
            document.getElementById('forgot-modal').classList.add('hidden');
        }
        
        function handleForgotRequest(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim...`;

            setTimeout(() => {
                // Simulate Sending OTP
                closeForgotModal();
                currentOtpAction = 'reset';
                document.getElementById('otp-modal').classList.remove('hidden');
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
                btn.innerHTML = originalText;
            }, 1000);
        }

        function handleResetSubmit(e) {
            e.preventDefault();
            const pass = document.getElementById('new-pass').value;
            const confirmPass = document.getElementById('new-pass-confirm').value;

            if (pass !== confirmPass) {
                alert("Konfirmasi kata sandi baru tidak cocok!");
                return;
            }

            // Simulate Reset Success
            alert("Kata sandi berhasil diubah! Silakan masuk.");
            document.getElementById('reset-modal').classList.add('hidden');
            switchTab('login');
        }

        // --- OTP Logic ---
        function focusNext(input) {
            if (input.value.length === 1) {
                const next = input.nextElementSibling;
                if (next) next.focus();
            }
        }

        function verifyOtp() {
            const inputs = document.querySelectorAll('.otp-input');
            let otp = '';
            inputs.forEach(i => otp += i.value);

            if (otp.length < 4) {
                alert("Masukkan 4 digit kode OTP");
                return;
            }

            const btn = document.querySelector('#otp-modal button');
            const originalText = btn.innerText;
            btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Verifikasi...`;

            setTimeout(() => {
                if (currentOtpAction === 'register') {
                    alert("Verifikasi Berhasil! Akun telah dibuat.");
                    window.location.href = 'index.html';
                } else if (currentOtpAction === 'reset') {
                    closeOtp();
                    document.getElementById('reset-modal').classList.remove('hidden');
                }

                // Reset inputs & button
                inputs.forEach(i => i.value = '');
                btn.innerText = originalText;
            }, 1500);
        }

        function closeOtp() {
            document.getElementById('otp-modal').classList.add('hidden');
        }
    </script>
