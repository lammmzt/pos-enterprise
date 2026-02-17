<nav
    class="sticky top-0 z-50 transition-colors duration-300 border-b border-gray-200 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md dark:border-gray-800">
    <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl lg:px-8">
        <div class="flex items-center gap-2.5">
            <div
                class="flex items-center justify-center w-8 h-8 text-white shadow-lg rounded-xl bg-gradient-to-br from-brand-red to-orange-500 shadow-orange-500/20 rotate-3">
                <i class="text-sm fa-solid fa-fire-flame-curved"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold leading-none tracking-tight text-gray-800 dark:text-white">
                    Seblak<span class="text-brand-red">Bucin</span>
                </h1>
            </div>
        </div>

        <div class="items-center hidden gap-8 text-sm font-semibold text-gray-500 lg:flex dark:text-gray-400">
            <a href="Home.html" class="text-brand-red">Home</a>
            <a href="index.html" class="transition-colors hover:text-brand-red">Menu & Pesan</a>
            <a href="Riwayat.html" class="transition-colors hover:text-brand-red">Riwayat</a>
            <a href="profile.html" class="transition-colors hover:text-brand-red">Akun Saya</a>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="toggleDarkMode()"
                class="flex items-center justify-center text-gray-500 transition-all bg-gray-100 border border-transparent rounded-full w-9 h-9 dark:bg-gray-800 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-gray-700 active:scale-95 dark:border-gray-700">
                <i id="dark-mode-icon" class="text-sm fa-solid fa-moon"></i>
            </button>

            <button onclick="toggleMenu()"
                class="flex items-center justify-center text-white transition-all rounded-full shadow-md lg:hidden w-9 h-9 bg-brand-red hover:bg-red-700 active:scale-95 shadow-red-500/30">
                <i id="menu-icon" class="text-sm fa-solid fa-bars"></i>
            </button>

            <a href="login.html"
                class="hidden px-5 py-2 text-sm font-bold text-white transition-colors rounded-full shadow-lg lg:block bg-brand-red hover:bg-red-700 shadow-red-500/20">
                Login
            </a>
        </div>
    </div>

    <div id="mobile-menu"
        class="lg:hidden absolute top-[60px] left-0 right-0 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-xl transform scale-y-0 origin-top transition-transform duration-200 ease-out z-[-1]">
        <div class="flex flex-col gap-2 p-4">
            <a href="Home.html"
                class="flex items-center gap-3 p-3 font-semibold rounded-xl bg-red-50 dark:bg-red-900/20 text-brand-red">
                <i class="w-6 text-center fa-solid fa-house"></i> Home
            </a>
            <a href="index.html"
                class="flex items-center gap-3 p-3 font-medium text-gray-600 rounded-xl dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="w-6 text-center fa-solid fa-utensils"></i> Menu & Pesan
            </a>
            <a href="riwayat.html"
                class="flex items-center gap-3 p-3 font-medium text-gray-600 rounded-xl dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="w-6 text-center fa-solid fa-clock-rotate-left"></i> Riwayat
            </a>
        </div>
    </div>
</nav>
