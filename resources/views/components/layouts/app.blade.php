<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name', 'Seblak Bucin') }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
         html {
            /* indigo-600 == #E11D48 */
            --livewire-progress-bar-color: #432dd7 !important;
            z-index: 9999 !important;
        }

        [wire\:navigate-progress-bar] {
            height: 4px !important;
            box-shadow: 0 0 12px var(--livewire-progress-bar-color) !important;
            z-index: 9999 !important;
        }
        /* Custom scrollbar untuk sidebar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        [x-cloak] { display: none !important; }
    </style>

    {{-- FIX BUG 1: Theme Initializer dengan Livewire Listener --}}
    <script data-navigate-once>
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Jalankan saat pertama kali muat
        applyTheme();

        // Pastikan tema tetap terkunci setiap kali Livewire selesai berpindah halaman
        document.addEventListener('livewire:navigated', applyTheme);
    </script>

    {{-- Alpine Stores --}}
    <script data-navigate-once>
        document.addEventListener('alpine:init', () => {

            /* ===============================
            THEME STORE
            =============================== */
            Alpine.store('theme', {
                theme: localStorage.getItem('theme') || 'light',

                init() {
                    applyTheme();
                },

                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    applyTheme();
                }
            });

            /* ===============================
            SIDEBAR STORE (FIXED)
            =============================== */
            Alpine.store('sidebar', {

                isExpanded: localStorage.getItem('sidebar-expanded') !== 'false',
                isMobileOpen: false,
                isHovered: false,

                init() {
                    // FIX BUG 2: Pusatkan logika resize agar bisa mendeteksi perubahan bolak-balik (Mobile <-> Desktop)
                    const handleResize = () => {
                        if (window.innerWidth >= 1280) {
                            // Masuk Desktop: Tutup overlay mobile, kembalikan state sidebar dari localStorage
                            this.isMobileOpen = false;
                            this.isExpanded = localStorage.getItem('sidebar-expanded') !== 'false';
                        } else {
                            // Masuk Mobile: Matikan margin expand agar tidak merusak layout
                            this.isExpanded = false;
                        }
                    };

                    window.addEventListener('resize', handleResize);
                    
                    // Panggil sekali saat inisiasi untuk memastikan state awal yang benar
                    handleResize();
                },

                /* ---------- Desktop Expand ---------- */
                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    localStorage.setItem('sidebar-expanded', this.isExpanded);
                },

                /* ---------- Mobile Overlay ---------- */
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                closeMobile() {
                    this.isMobileOpen = false;
                },

                /* ---------- Hover Logic (Desktop Only) ---------- */
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });

        });
    </script>
</head>

{{-- Dihapus: @resize.window karena sudah ditangani secara komprehensif di dalam Alpine init() di atas --}}
<body
    class="h-full antialiased text-gray-900 transition-colors duration-300 bg-gray-50 dark:bg-gray-950 dark:text-gray-100"
    x-data>

    <div class="flex min-h-screen">

        {{-- Mobile Overlay (Backdrop) --}}
        <div x-show="$store.sidebar.isMobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sidebar.toggleMobileOpen()"
            class="fixed inset-0 z-[55] bg-gray-900/50 backdrop-blur-sm xl:hidden">
        </div>

        {{-- Pastikan di dalam komponen <x-sidebar /> sudah ditambahkan @persist('sidebar') --}}
        <x-sidebar />

        {{-- Main Area --}}
        <div class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-72': $store.sidebar.isExpanded,
                'xl:ml-20': !$store.sidebar.isExpanded,
            }">

            {{-- Header Include --}}
            <x-header />

            {{-- Page Content --}}
            <main class="flex-1 p-4 md:p-8">
                <div class="mx-auto max-w-screen-2xl">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer (Opsional) --}}
            <footer class="p-6 text-xs font-medium text-center text-gray-400">
                <p class="text-xs text-gray-500">
                   &copy; {{ date('Y') }}, Built with ❤️ by <a href="#"
                        class="font-bold hover:text-indigo-600">Seblak Bucin Team</a>
                </p>
            </footer>
        </div>
    </div>
    
    <x-toast />
    @livewireScripts
    @stack('scripts')
</body>

</html>