<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? '' }} | {{ config('app.name', 'Seblak Bucin') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style type="text/css">
         html {
            --livewire-progress-bar-color: #E11D48 !important;
        }

        [wire\:navigate-progress-bar] {
            height: 4px !important;
            box-shadow: 0 0 12px var(--livewire-progress-bar-color) !important;
        }

        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        .glass-header { 
            background: rgba(255, 255, 255, 0.90); 
            backdrop-filter: blur(8px); 
        }
        .dark .glass-header {
            background: rgba(17, 24, 39, 0.90);
            border-bottom-color: #374151;
        }

        .hide-scroll::-webkit-scrollbar { display: none; }
        .tap-highlight-transparent { -webkit-tap-highlight-color: transparent; }
        .glass-header { 
            background: rgba(255, 255, 255, 0.90); 
            backdrop-filter: blur(8px); 
        }
        .dark .glass-header {
            background: rgba(17, 24, 39, 0.90);
            border-bottom-color: #374151;
        }
        .tap-highlight-transparent { -webkit-tap-highlight-color: transparent; }
        
        .glass-nav { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(12px); 
        }
        .dark .glass-nav {
            background: rgba(17, 24, 39, 0.85);
            border-bottom-color: #374151;
        }
        /* Cart Transition - Only for mobile view */
        @media (max-width: 1023px) {
            #cart-sheet {
                transition: transform 0.3s cubic-bezier(0.33, 1, 0.68, 1);
            }
        }
        
    </style>
    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans text-gray-800 transition-colors duration-300 select-none bg-gray-50 dark:bg-gray-900 dark:text-gray-100 tap-highlight-transparent">
    
    {{ $slot }}

    {{-- modal --}}
    <div id="modal-container" class="fixed inset-0 z-[999] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm" ></div>
        <div class="relative z-10 w-full max-w-sm overflow-hidden transition-all duration-200 transform scale-95 bg-white border border-gray-200 shadow-2xl dark:bg-gray-800 rounded-2xl animate-fade-in dark:text-white dark:border-gray-700">
            <button onclick="closeModal()" class="absolute z-20 flex items-center justify-center w-8 h-8 text-gray-400 bg-gray-100 rounded-full top-3 right-3 hover:text-gray-600 dark:bg-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div id="modal-body" class="p-0"></div>
        </div>
    </div>

    
    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>
        <div class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl text-green-600 bg-green-100 rounded-full dark:bg-green-900/30 animate-bounce">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Berhasil Disimpan!</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Data profil Anda telah diperbarui.</p>
            <button onclick="closeModals()" class="w-full py-3 font-bold text-gray-800 transition-colors bg-gray-100 dark:bg-gray-700 dark:text-white rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600">
                Tutup
            </button>
        </div>
    </div>

    <!-- Alert Modal (Replaces alert()) -->
    <div id="alert-modal" class="fixed inset-0 z-[70] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm" onclick="closeAlertModal()"></div>
        <div class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-fade-in">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl bg-red-100 rounded-full dark:bg-red-900/30 text-brand-red">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Perhatian</h3>
            <p id="alert-message" class="mb-6 text-sm text-gray-500 dark:text-gray-400">Pesan alert disini.</p>
            <button onclick="closeAlertModal()" class="w-full py-3 font-bold text-gray-800 transition-colors bg-gray-100 dark:bg-gray-700 dark:text-white rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600">
                Tutup
            </button>
        </div>
    </div>

    <!-- Confirmation Modal (Replaces confirm()) -->
    <div id="confirm-modal" class="fixed inset-0 z-[70] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-fade-in">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl bg-orange-100 rounded-full dark:bg-orange-900/30 text-brand-orange">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h3 id="confirm-title" class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Konfirmasi</h3>
            <p id="confirm-message" class="mb-6 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin?</p>
            <div class="flex gap-3">
                <button onclick="closeConfirmModal()" class="flex-1 py-3 font-bold text-gray-800 transition-colors bg-gray-100 dark:bg-gray-700 dark:text-white rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button id="confirm-yes-btn" class="flex-1 py-3 font-bold text-white transition-colors shadow-lg bg-brand-red rounded-xl hover:bg-red-700 shadow-red-500/20">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    @livewireScripts
    
    <script data-navigate-once type="text/javascript">
        // 1. Gunakan 'window.' sebagai pengganti 'let' agar kebal terhadap error redeklarasi Livewire
        window.isDarkMode = window.isDarkMode || false;
        window.isMenuOpen = false;

        function initAPP() {
            const theme = localStorage.getItem('appThems');
            window.isDarkMode = (theme === 'dark');
            applyTheme(); // Terapkan tema, BUKAN membalikkannya (toggle)
        }

        // --- FUNGSI KHUSUS UNTUK MENERAPKAN TEMA ---
        function applyTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('dark-mode-icon');
            
            if (!icon) return; // Mencegah error jika elemen tidak ada di halaman tertentu

            if (window.isDarkMode) {
                html.classList.add('dark');
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                html.classList.remove('dark');
                icon.classList.replace('fa-sun', 'fa-moon');
            }
        }

        // --- FUNGSI SAAT TOMBOL DIKLIK ---
        function toggleDarkMode() {
            window.isDarkMode = !window.isDarkMode; // Balikkan status
            localStorage.setItem('appThems', window.isDarkMode ? 'dark' : 'light');
            applyTheme();
        }

        // --- MOBILE MENU TOGGLE ---
        function toggleMenu() {
            window.isMenuOpen = !window.isMenuOpen;
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            
            if (!menu || !icon) return;

            if (window.isMenuOpen) {
                menu.classList.remove('scale-y-0');
                icon.classList.replace('fa-bars', 'fa-xmark');
            } else {
                menu.classList.add('scale-y-0');
                icon.classList.replace('fa-xmark', 'fa-bars');
            }
        }

        // --- MODAL ---
        function showModal(html) {
            document.getElementById('modal-body').innerHTML = html;
            document.getElementById('modal-container').classList.remove('hidden');
        }
        
        function closeModal() { 
            document.getElementById('modal-container').classList.add('hidden'); 
        }

        // --- Profile Dropdown Logic (Desktop) ---
        let isProfileOpen = false;
        function toggleProfileDropdown() {
            isProfileOpen = !isProfileOpen;
            const dropdown = document.getElementById('profile-dropdown');
            if (isProfileOpen) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('flex');
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('flex');
            }
        }
        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profile-dropdown');
            const button = document.querySelector('[onclick="toggleProfileDropdown()"]');
            if (isProfileOpen && !dropdown.contains(e.target) && !button.contains(e.target)) {
                toggleProfileDropdown();
            }
        });

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
        // --- INIT PERTAMA KALI MUAT ---
        initAPP();
        
        // --- INIT SAAT PINDAH HALAMAN (wire:navigate) ---
        document.addEventListener('livewire:navigated', () => {
            window.isMenuOpen = false; // Pastikan menu mobile tertutup saat ganti halaman
            initAPP(); // Panggil initAPP agar mengecek memori, bukan toggleDarkMode
        });

        
    </script>
    <!-- Stack Scripts untuk Javascript per halaman -->
    @stack('scripts')
</body>
</html>