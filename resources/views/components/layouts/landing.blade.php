<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Seblak Bucin') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
    
    <script type="text/javascript">
        let isDarkMode = false;
        let isMenuOpen = false;
        function initAPP() {
            const theme = localStorage.getItem('seblakTheme');
            if (theme === 'dark') {
                toggleDarkMode();
            }
        }
        // --- DARK MODE SYSTEM ---
        function toggleDarkMode() {
            isDarkMode = !isDarkMode;
            const html = document.documentElement;
            const icon = document.getElementById('dark-mode-icon');
            if (isDarkMode) {
                html.classList.add('dark');
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('seblakTheme', 'dark');
            } else {
                html.classList.remove('dark');
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('seblakTheme', 'light');
            }
        }

        // --- MOBILE MENU TOGGLE ---
        function toggleMenu() {
            isMenuOpen = !isMenuOpen;
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            if (isMenuOpen) {
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
        function closeModal() { document.getElementById('modal-container').classList.add('hidden'); }

        // --- INIT ---
        initAPP();
    </script>
    <!-- Stack Scripts untuk Javascript per halaman -->
    @stack('scripts')
</body>
</html>