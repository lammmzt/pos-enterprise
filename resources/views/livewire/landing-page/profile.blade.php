<div>
    @include('livewire.landing-page.layout.header')
   <!-- Main Content -->
    <main class="flex-1 w-full max-w-lg p-4 pb-24 mx-auto">
        
        <!-- Profile Header -->
        <div class="flex items-center gap-4 mb-8 animate-fade-in">
            <div class="flex items-center justify-center w-16 h-16 text-3xl text-gray-400 bg-gray-200 border-2 border-white rounded-full shadow-md dark:bg-gray-700 dark:text-gray-500 dark:border-gray-800">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Budi Santoso</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">0812-3456-7890</p>
                <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-[10px] font-bold rounded-md uppercase tracking-wider">Member Gold</span>
            </div>
        </div>

        <!-- Form Container -->
        <form onsubmit="handleSaveProfile(event)" class="space-y-6 animate-slide-up">
            
            <!-- Section: Data Diri -->
            <div class="p-5 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <h3 class="flex items-center gap-2 mb-4 font-bold text-gray-800 dark:text-white">
                    <i class="fa-solid fa-address-card text-brand-red"></i> Data Pengiriman
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Alamat Lengkap</label>
                        <textarea id="address-input" rows="3" class="w-full p-3 text-sm text-gray-800 transition-all border border-gray-200 outline-none resize-none bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-xl dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red" placeholder="Masukkan alamat lengkap...">Jl. Dago Atas No. 123, RT 01/RW 02, Coblong, Bandung</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Catatan Alamat (Opsional)</label>
                        <input type="text" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red outline-none transition-all" placeholder="Contoh: Pagar hitam, rumah tingkat 2">
                    </div>
                </div>
            </div>

            <!-- Section: Keamanan -->
            <div class="p-5 bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <h3 class="flex items-center gap-2 mb-4 font-bold text-gray-800 dark:text-white">
                    <i class="fa-solid fa-lock text-brand-red"></i> Ubah Kata Sandi
                </h3>
                <p class="mb-4 text-xs text-gray-400 dark:text-gray-500">Kosongkan jika tidak ingin mengubah kata sandi.</p>

                <div class="space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input id="current-pass" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl pl-3 pr-10 py-2.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red outline-none transition-all" placeholder="••••••••">
                            <button type="button" onclick="togglePassword('current-pass', this)" class="absolute text-gray-400 transition-colors -translate-y-1/2 right-3 top-1/2 hover:text-brand-red">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Kata Sandi Baru</label>
                        <div class="relative">
                            <input id="new-pass" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl pl-3 pr-10 py-2.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red outline-none transition-all" placeholder="Minimal 6 karakter">
                            <button type="button" onclick="togglePassword('new-pass', this)" class="absolute text-gray-400 transition-colors -translate-y-1/2 right-3 top-1/2 hover:text-brand-red">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input id="confirm-pass" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl pl-3 pr-10 py-2.5 text-sm text-gray-800 dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red outline-none transition-all" placeholder="Ulangi kata sandi baru">
                            <button type="button" onclick="togglePassword('confirm-pass', this)" class="absolute text-gray-400 transition-colors -translate-y-1/2 right-3 top-1/2 hover:text-brand-red">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 pt-4">
                <button type="submit" class="w-full bg-brand-red hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all flex justify-center items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <button type="button" onclick="handleLogout()" class="w-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3.5 rounded-xl transition-all active:scale-95 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar Akun
                </button>
            </div>

        </form>

    </main>
</div>

<!-- JavaScript Logic -->
<script>
    
    // --- Password Toggle Logic ---
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
    // --- Modal Logic ---
    function showAlert(message) {
        document.getElementById('alert-message').innerText = message;
        document.getElementById('alert-modal').classList.remove('hidden');
    }
    function closeAlertModal() {
        document.getElementById('alert-modal').classList.add('hidden');
    }
    let confirmCallback = null;
    function showConfirm(title, message, callback) {
        document.getElementById('confirm-title').innerText = title;
        document.getElementById('confirm-message').innerText = message;
        document.getElementById('confirm-modal').classList.remove('hidden');
        confirmCallback = callback;
    }
    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        confirmCallback = null;
    }
    // Attach listener for confirm yes button once
    document.getElementById('confirm-yes-btn').addEventListener('click', function() {
        if (confirmCallback) confirmCallback();
        closeConfirmModal();
    });
    // --- Form Handling ---
    function handleSaveProfile(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        
        // Validate Password match if filled
        const newPass = document.getElementById('new-pass').value;
        const confirmPass = document.getElementById('confirm-pass').value;
        
        if (newPass && newPass !== confirmPass) {
            showAlert("Konfirmasi kata sandi baru tidak cocok!");
            return;
        }
        // Simulate Loading
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...`;
        btn.disabled = true;
        setTimeout(() => {
            document.getElementById('success-modal').classList.remove('hidden');
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Clear password fields
            document.getElementById('current-pass').value = '';
            document.getElementById('new-pass').value = '';
            document.getElementById('confirm-pass').value = '';
        }, 1500);
    }
    function closeModals() {
        document.getElementById('success-modal').classList.add('hidden');
    }
    function handleLogout() {
        showConfirm("Keluar Akun", "Apakah Anda yakin ingin keluar dari aplikasi?", function() {
            localStorage.removeItem('seblakAppStateV3'); 
            window.location.href = 'login.html';
        });
    }
</script>
