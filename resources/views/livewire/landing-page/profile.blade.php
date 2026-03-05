<div class="min-h-screen pb-12 font-sans text-gray-800 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    
   {{-- include header --}}
    @include('livewire.landing-page.layout.header', ['active' => $active])

    <main class="max-w-5xl px-4 pt-24 mx-auto">
        
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            
            <div class="space-y-6 lg:col-span-1">
                <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center group">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-brand-red to-brand-orange opacity-10"></div>
                    
                    <div class="relative">
                        <div class="inline-flex items-center justify-center mb-4 text-4xl text-white transition-transform duration-500 transform shadow-2xl w-28 h-28 bg-gradient-to-tr from-brand-red to-brand-orange rounded-3xl group-hover:scale-105">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <h3 class="text-xl font-black truncate">{{ $nama }}</h3>
                        <p class="mb-6 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $no_hp }}</p>
                        
                        <div class="flex flex-col gap-2">
                            <div class="px-4 py-2 text-xs italic font-bold text-gray-600 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                @if($isAntrean)
                                    <i class="mr-1 fa-solid fa-lock"></i> Akun Antrean (Terbatas)
                                @else
                                    <i class="mr-1 text-green-500 fa-solid fa-check-circle"></i> Akun Pelanggan Aktif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <button onclick="handleLogout()" class="flex items-center justify-between w-full p-5 font-bold text-red-500 transition-all bg-white border border-gray-100 shadow-lg dark:bg-gray-800 rounded-3xl dark:border-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 group">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-xl dark:bg-red-900/30">
                            <i class="fa-solid fa-power-off"></i>
                        </span>
                        Keluar Akun
                    </div>
                    <i class="text-xs transition-transform fa-solid fa-chevron-right group-hover:translate-x-1"></i>
                </button>
            </div>

            <div class="space-y-6 lg:col-span-2">
                
                @if (session()->has('success'))
                    <div class="flex items-center gap-3 p-4 text-sm font-bold text-green-700 border border-green-200 bg-green-50 rounded-2xl dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50 animate-bounce">
                        <i class="text-xl fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if($isAntrean)
                    <div class="flex gap-4 p-5 border bg-amber-50 border-amber-200 rounded-3xl dark:bg-amber-900/20 dark:border-amber-800/50">
                        <div class="text-2xl text-amber-500"><i class="fa-solid fa-circle-info"></i></div>
                        <div class="text-sm">
                            <p class="font-bold text-amber-800 dark:text-amber-500">Informasi Penting</p>
                            <p class="text-amber-700 dark:text-amber-400">Profil akun kasir/antrean dikelola oleh sistem dan tidak dapat diedit secara mandiri untuk keperluan keamanan data.</p>
                        </div>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 p-8 sm:p-10">
                    <form wire:submit.prevent="updateProfile" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="ml-1 text-sm font-bold">Nama Lengkap</label>
                                <div class="relative group">
                                    <i class="absolute text-gray-400 transition-colors -translate-y-1/2 left-4 top-1/2 fa-solid fa-id-card group-focus-within:text-brand-red"></i>
                                    <input type="text" wire:model="nama" {{ $isAntrean ? 'disabled' : '' }} 
                                        class="w-full py-3.5 pl-12 pr-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl outline-none focus:ring-4 focus:ring-brand-red/10 focus:border-brand-red transition-all disabled:opacity-50">
                                </div>
                                @error('nama') <span class="ml-1 text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="ml-1 text-sm font-bold">Nomor WhatsApp</label>
                                <div class="relative group">
                                    <i class="absolute text-gray-400 transition-colors -translate-y-1/2 left-4 top-1/2 fa-brands fa-whatsapp group-focus-within:text-brand-red"></i>
                                    <input type="text" wire:model="no_hp" {{ $isAntrean ? 'disabled' : '' }} 
                                        class="w-full py-3.5 pl-12 pr-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl outline-none focus:ring-4 focus:ring-brand-red/10 focus:border-brand-red transition-all disabled:opacity-50">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="ml-1 text-sm font-bold">Alamat Pengiriman</label>
                            <div class="relative group">
                                <i class="absolute text-gray-400 transition-colors left-4 top-5 fa-solid fa-map-location-dot group-focus-within:text-brand-red"></i>
                                <textarea wire:model="alamat" {{ $isAntrean ? 'disabled' : '' }} rows="3"
                                    class="w-full py-3.5 pl-12 pr-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl outline-none focus:ring-4 focus:ring-brand-red/10 focus:border-brand-red transition-all disabled:opacity-50"></textarea>
                            </div>
                        </div>

                        @if(!$isAntrean)
                        <div class="pt-6 border-t dark:border-gray-700">
                            <h4 class="mb-6 text-xs italic font-black tracking-widest text-gray-400 uppercase">Ganti Kata Sandi (Opsional)</h4>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="relative group" x-data="{ show: false }">
                                    <i class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2 fa-solid fa-lock"></i>
                                    <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="Sandi Baru" 
                                        class="w-full py-3.5 pl-12 pr-12 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl outline-none focus:ring-4 focus:ring-brand-red/10 focus:border-brand-red transition-all">
                                    <button type="button" @click="show = !show" class="absolute text-gray-400 -translate-y-1/2 right-4 top-1/2">
                                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <div class="relative group" x-data="{ show: false }">
                                    <i class="absolute text-gray-400 -translate-y-1/2 left-4 top-1/2 fa-solid fa-shield-check"></i>
                                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" placeholder="Ulangi Sandi" 
                                        class="w-full py-3.5 pl-12 pr-12 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl outline-none focus:ring-4 focus:ring-brand-red/10 focus:border-brand-red transition-all">
                                    <button type="button" @click="show = !show" class="absolute text-gray-400 -translate-y-1/2 right-4 top-1/2">
                                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password') <p class="mt-2 ml-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="w-full py-4 bg-gradient-to-r from-brand-red to-brand-orange text-white font-black rounded-2xl shadow-xl shadow-red-500/30 hover:shadow-red-500/50 active:scale-[0.98] transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                            <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                            <span wire:loading wire:target="updateProfile"><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>