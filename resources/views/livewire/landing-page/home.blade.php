<div>
    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 transition-colors duration-300 border-b border-gray-200 glass-nav dark:border-gray-800">
        <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl lg:px-8">
            <!-- Brand -->
            <div class="flex items-center gap-2">
                <div class="flex items-center justify-center text-white shadow-lg w-9 h-9 rounded-xl bg-gradient-to-br from-brand-red to-orange-500 shadow-orange-500/20 rotate-3">
                    <i class="text-sm fa-solid fa-fire-flame-curved"></i>
                </div>
                <h1 class="text-xl font-extrabold leading-none tracking-tight text-gray-800 dark:text-white">
                    Seblak<span class="text-brand-red">Bucin</span>
                </h1>
            </div>

            <!-- Desktop Menu -->
            <div class="items-center hidden gap-8 text-sm font-semibold text-gray-600 md:flex dark:text-gray-300">
                <a href="#home" class="text-brand-red">Home</a>
                <a href="#about" class="transition-colors hover:text-brand-red">Tentang Kami</a>
                <a href="#reviews" class="transition-colors hover:text-brand-red">Testimoni</a>
                <a href="#lokasi" class="transition-colors hover:text-brand-red">Lokasi</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <button onclick="toggleDarkMode()" class="flex items-center justify-center w-10 h-10 text-gray-500 transition-all bg-gray-100 border border-transparent rounded-full dark:bg-gray-800 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-gray-700 active:scale-95 dark:border-gray-700">
                    <i id="dark-mode-icon" class="text-sm fa-solid fa-moon"></i>
                </button>
                <a href="{{ route('Order') }}" wire:navigate class="hidden sm:flex px-6 py-2.5 rounded-full bg-brand-red text-white text-sm font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-500/20 active:scale-95 items-center gap-2">
                    <span>Pesan Online</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="home" class="relative pt-12 pb-20 overflow-hidden lg:pt-24 lg:pb-32">
        <div class="relative z-10 px-4 mx-auto max-w-7xl lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <!-- Text Content -->
                <div class="flex-1 text-center lg:text-left animate-fade-in">
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 text-xs font-bold tracking-wider uppercase bg-orange-100 rounded-full dark:bg-orange-900/30 text-brand-orange">
                        <i class="fa-solid fa-fire"></i> Pedasnya Bikin Nagih
                    </div>
                    <h1 class="mb-6 text-4xl font-extrabold leading-tight text-gray-900 lg:text-6xl dark:text-white">
                        Rasakan Sensasi <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500">Seblak Prasmanan</span> <br>
                        Paling Nampol!
                    </h1>
                    <p class="max-w-2xl mx-auto mb-8 text-lg leading-relaxed text-gray-600 dark:text-gray-400 lg:mx-0">
                        Bebas pilih topping sesuka hati, tentukan level pedasmu, dan nikmati kuah rempah asli yang bikin kamu bucin sama rasanya. Mulai dari Rp 1.000-an aja!
                    </p>
                    <div class="flex flex-col justify-center gap-4 sm:flex-row lg:justify-start">
                        <a wire:navigate href="{{ route('Order') }}" class="flex items-center justify-center gap-2 px-8 py-4 text-lg font-bold text-white transition-all shadow-xl rounded-2xl bg-brand-red shadow-red-500/30 hover:bg-red-700 hover:scale-105">
                            <i class="fa-solid fa-utensils"></i>
                            Mulai Pesan
                        </a>
                        <a wire:navigate href="{{ route('Order') }}" class="flex items-center justify-center gap-2 px-8 py-4 text-lg font-bold text-gray-700 transition-all bg-white border border-gray-200 rounded-2xl dark:bg-gray-800 dark:text-white dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Lihat Menu
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="flex items-center justify-center gap-8 pt-8 mt-12 border-t border-gray-200 lg:justify-start dark:border-gray-800">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">50+</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pilihan Topping</div>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-800"></div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">5</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Level Pedas</div>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-800"></div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">4.8</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Rating Google</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Illustration -->
                <div class="relative flex-1 animate-float">
                    <div class="relative z-10 w-full max-w-lg p-2 mx-auto rounded-full shadow-2xl aspect-square bg-gradient-to-tr from-brand-red to-orange-400 shadow-orange-500/40">
                        <img src="https://placehold.co/600x600/png?text=Seblak+Bowl+HD" alt="Seblak Bowl" class="object-cover w-full h-full border-4 border-white rounded-full dark:border-gray-800">
                        
                        <!-- Floating Badge 1 -->
                        <div class="absolute flex items-center gap-3 p-4 bg-white shadow-xl top-10 -left-6 dark:bg-gray-800 rounded-2xl animate-bounce" style="animation-duration: 3s;">
                            <div class="flex items-center justify-center w-10 h-10 text-green-600 bg-green-100 rounded-full">
                                <i class="fa-solid fa-leaf"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Bahan</div>
                                <div class="font-bold text-gray-800 dark:text-white">Fresh & Halal</div>
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="absolute flex items-center gap-3 p-4 bg-white shadow-xl bottom-10 -right-6 dark:bg-gray-800 rounded-2xl animate-bounce" style="animation-duration: 4s;">
                            <div class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-100 rounded-full">
                                <i class="fa-solid fa-pepper-hot"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Kuah</div>
                                <div class="font-bold text-gray-800 dark:text-white">Kencur Asli</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Background blobs -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-brand-red/10 dark:bg-brand-red/5 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES / ABOUT SECTION -->
    <section id="about" class="py-20 transition-colors bg-white dark:bg-gray-800">
        <div class="px-4 mx-auto max-w-7xl lg:px-8">
            <div class="max-w-3xl mx-auto mb-16 text-center">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl dark:text-white">Kenapa Harus <span class="text-brand-red">Seblak Bucin?</span></h2>
                <p class="text-gray-600 dark:text-gray-400">Kami menyajikan pengalaman makan seblak yang berbeda. Bukan sekadar pedas, tapi rasa yang bikin nyaman di hati dan kantong.</p>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <!-- Feature 1 -->
                <div class="p-8 transition-all border border-gray-100 rounded-3xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 hover:shadow-xl group">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 text-2xl transition-transform bg-orange-100 rounded-2xl dark:bg-orange-900/30 text-brand-orange group-hover:scale-110">
                        <i class="fa-solid fa-bowl-food"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Konsep Prasmanan</h3>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-400">Ambil mangkukmu, pilih isian sesuka hati. Mulai dari kerupuk, seafood, sayuran, hingga topping premium.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 transition-all border border-gray-100 rounded-3xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 hover:shadow-xl group">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 text-2xl transition-transform bg-red-100 rounded-2xl dark:bg-red-900/30 text-brand-red group-hover:scale-110">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Kuah Rempah Asli</h3>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-400">Bumbu kencur dan cabai asli yang diulek dadakan. Tanpa bahan pengawet, pedasnya nendang tapi aman di perut.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 transition-all border border-gray-100 rounded-3xl bg-gray-50 dark:bg-gray-900 dark:border-gray-700 hover:shadow-xl group">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 text-2xl text-green-600 transition-transform bg-green-100 rounded-2xl dark:bg-green-900/30 group-hover:scale-110">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">Harga Mahasiswa</h3>
                    <p class="leading-relaxed text-gray-600 dark:text-gray-400">Mulai dari Rp 1.000 per topping. Kenyang enak gak perlu bikin dompet boncos. Cocok buat tanggal tua!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION (UPDATED: Slider) -->
    <section id="reviews" class="py-20 transition-colors border-t border-gray-200 bg-gray-50 dark:bg-gray-900 dark:border-gray-800">
        <div class="px-4 mx-auto max-w-7xl lg:px-8">
            <div class="flex flex-col items-end justify-between gap-6 mb-12 md:flex-row">
                <div class="max-w-2xl">
                    <div class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wider uppercase bg-red-100 rounded-full dark:bg-red-900/30 text-brand-red">Kata Mereka</div>
                    <h2 class="text-3xl font-extrabold text-gray-900 md:text-4xl dark:text-white">Apa Kata <span class="text-brand-red">Para Bucin?</span></h2>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Ulasan jujur dari pelanggan setia yang sudah jatuh cinta sama rasa Seblak Bucin.</p>
                </div>
                
                <!-- Navigasi Slider -->
                <div class="flex gap-2">
                    <button onclick="scrollReviews('left')" class="flex items-center justify-center w-12 h-12 text-gray-600 transition-colors border border-gray-300 rounded-full dark:border-gray-600 hover:bg-white dark:hover:bg-gray-800 dark:text-gray-300 active:scale-95">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <button onclick="scrollReviews('right')" class="flex items-center justify-center w-12 h-12 text-white transition-colors rounded-full shadow-lg bg-brand-red hover:bg-red-700 shadow-red-500/30 active:scale-95">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Slider Container (Updated) -->
            <div id="reviews-container" class="flex gap-6 pb-4 overflow-x-auto hide-scroll snap-x snap-mandatory scroll-smooth">
                
                <!-- Review Card 1 -->
                <div class="min-w-full md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)] snap-start bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative">
                    <i class="absolute text-4xl text-gray-100 fa-solid fa-quote-right top-6 right-6 dark:text-gray-700"></i>
                    <div class="flex items-center gap-1 mb-4 text-sm text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="mb-6 leading-relaxed text-gray-700 dark:text-gray-300">"Sumpah ini seblak terenak di Dago! Kuahnya kencur banget, pedesnya nampol tapi nagih. Wajib cobain dumpling kejunya lumer parah."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://placehold.co/100x100/orange/white?text=SF" class="object-cover w-12 h-12 border-2 rounded-full border-brand-red">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Siti Fatimah</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mahasiswi</p>
                        </div>
                    </div>
                </div>

                <!-- Review Card 2 -->
                <div class="min-w-full md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)] snap-start bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative">
                    <i class="absolute text-4xl text-gray-100 fa-solid fa-quote-right top-6 right-6 dark:text-gray-700"></i>
                    <div class="flex items-center gap-1 mb-4 text-sm text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <p class="mb-6 leading-relaxed text-gray-700 dark:text-gray-300">"Suka banget sama konsep prasmanannya, jadi bisa ngukur sendiri mau jajan berapa. Pilihan toppingnya banyak banget sampe bingung milih!"</p>
                    <div class="flex items-center gap-4">
                        <img src="https://placehold.co/100x100/red/white?text=BP" class="object-cover w-12 h-12 border-2 rounded-full border-brand-red">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Budi Pratama</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Freelancer</p>
                        </div>
                    </div>
                </div>

                <!-- Review Card 3 -->
                <div class="min-w-full md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)] snap-start bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative">
                    <i class="absolute text-4xl text-gray-100 fa-solid fa-quote-right top-6 right-6 dark:text-gray-700"></i>
                    <div class="flex items-center gap-1 mb-4 text-sm text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="mb-6 leading-relaxed text-gray-700 dark:text-gray-300">"Pesen lewat web gampang banget, tinggal klik-klik doang. Pas dateng pesanan udah siap, gak perlu antri lama. Mantap!"</p>
                    <div class="flex items-center gap-4">
                        <img src="https://placehold.co/100x100/green/white?text=AR" class="object-cover w-12 h-12 border-2 rounded-full border-brand-red">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Anisa Rahma</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Karyawan Swasta</p>
                        </div>
                    </div>
                </div>

                <!-- Review Card 4 (Added for demo scrolling) -->
                <div class="min-w-full md:min-w-[calc(50%-12px)] lg:min-w-[calc(33.333%-16px)] snap-start bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative">
                    <i class="absolute text-4xl text-gray-100 fa-solid fa-quote-right top-6 right-6 dark:text-gray-700"></i>
                    <div class="flex items-center gap-1 mb-4 text-sm text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="mb-6 leading-relaxed text-gray-700 dark:text-gray-300">"Rekomendasi banget buat yang suka pedes. Level 5 nya beneran bikin melek. Pelayanan juga ramah banget."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://placehold.co/100x100/purple/white?text=DN" class="object-cover w-12 h-12 border-2 rounded-full border-brand-red">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Doni Nugraha</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mahasiswa</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark dark:bg-black"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-brand-red/90 to-orange-600/90 mix-blend-multiply"></div>
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=');"></div>

        <div class="relative z-10 max-w-4xl px-4 mx-auto text-center">
            <h2 class="mb-6 text-3xl font-extrabold text-white md:text-5xl">Udah Ngiler Belum? 🤤</h2>
            <p class="max-w-2xl mx-auto mb-8 text-lg text-white/80">Jangan cuma dibayangin, yuk langsung pilih topping favoritmu sekarang. Mumpung stok masih lengkap!</p>
            <a wire:navigate href="{{ route('Order') }}" class="inline-flex items-center gap-3 px-8 py-4 text-lg font-bold transition-all bg-white shadow-xl rounded-2xl text-brand-red hover:bg-gray-100 hover:scale-105">
                <span>Pesan Sekarang</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="pt-16 pb-8 transition-colors border-t border-gray-200 bg-gray-50 dark:bg-gray-900 dark:border-gray-800">
        <div class="px-4 mx-auto max-w-7xl lg:px-8">
            <div class="grid gap-12 mb-12 md:grid-cols-4">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-br from-brand-red to-orange-500 rotate-3">
                            <i class="text-xs fa-solid fa-fire-flame-curved"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-gray-800 dark:text-white">
                            Seblak<span class="text-brand-red">Bucin</span>
                        </h2>
                    </div>
                    <p class="max-w-sm mb-6 text-gray-500 dark:text-gray-400">
                        Menyajikan seblak prasmanan dengan cita rasa otentik dan harga terbaik. Pedasnya bikin bucin, nagihnya gak main-main!
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-gray-200 rounded-full dark:bg-gray-800 dark:text-gray-400 hover:bg-brand-red hover:text-white"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-gray-200 rounded-full dark:bg-gray-800 dark:text-gray-400 hover:bg-green-500 hover:text-white"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" class="flex items-center justify-center w-10 h-10 text-gray-600 transition-colors bg-gray-200 rounded-full dark:bg-gray-800 dark:text-gray-400 hover:bg-blue-500 hover:text-white"><i class="fa-solid fa-map-location-dot"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="mb-6 font-bold text-gray-900 dark:text-white">Navigasi</h3>
                    <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#home" class="transition-colors hover:text-brand-red">Home</a></li>
                        <li><a href="#about" class="transition-colors hover:text-brand-red">Tentang Kami</a></li>
                        <li><a href="#reviews" class="transition-colors hover:text-brand-red">Testimoni</a></li>
                        <li><a wire:navigate href="{{ route('Order') }}" class="transition-colors hover:text-brand-red">Menu & Pesan</a></li>
                        <li><a href="#reviews" class="transition-colors hover:text-brand-red">Login Admin</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-6 font-bold text-gray-900 dark:text-white">Kontak & Lokasi</h3>
                    <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="mt-1 fa-solid fa-location-dot text-brand-red"></i>
                            <span>Jl. Dago Atas No. 12,<br>Pekalongan, Jawa Tengah</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-clock text-brand-red"></i>
                            <span>10.00 - 22.00 WIB</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-brand-red"></i>
                            <span>0812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 text-xs text-center text-gray-400 border-t border-gray-200 dark:border-gray-800 dark:text-gray-500">
                &copy; 2026 Seblak Bucin Prasmanan. All rights reserved.
            </div>
        </div>
    </footer>
</div>
<!-- Scripts -->
    <script>
        
        // Testimonial Scroll Logic
        function scrollReviews(direction) {
            const container = document.getElementById('reviews-container');
            // Check item width to determine scroll amount
            const item = container.querySelector('div');
            const scrollAmount = item ? item.clientWidth + 24 : 300; // 24 is gap-6

            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

    </script>
