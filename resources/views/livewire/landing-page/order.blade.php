<div x-data="{ 
        isCartOpen: false, 
        showInfoModal: false, 
        activeInfo: { nama: '', deskripsi: '', gambar: '', harga: '' },
        openInfo(nama, deskripsi, gambar, harga) {
            this.activeInfo = { nama, deskripsi, gambar, harga };
            this.showInfoModal = true;
        }
    }" 
    class="relative w-full">
    
    {{-- Header --}}
    @include('livewire.landing-page.layout.header', ['active' => $active])
    
    <header class="sticky top-[61px] z-30 glass-header shadow-sm border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="mx-auto max-w-7xl">
            <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                <div>
                    <h2 class="flex items-center gap-1 text-sm font-bold leading-tight text-gray-700 dark:text-gray-200">
                        <i class="text-xs fa-solid fa-location-dot text-brand-red"></i> Cabang Dago Atas
                    </h2>
                    <p class="text-[10px] text-gray-400">Buka: 10.00 - 22.00 WIB</p>
                </div>
            </div>

            <div class="flex gap-4 pb-0 pl-4 overflow-x-auto border-b border-gray-200 lg:pl-8 hide-scroll dark:border-gray-700">
                @foreach($bowls as $index => $bowl)
                    <button wire:click="setActiveBowl({{ $index }})" class="pb-3 border-b-2 {{ $activeBowlIndex === $index ? 'border-brand-red text-brand-red' : 'border-transparent text-gray-400 hover:text-gray-600 dark:hover:text-gray-200' }} font-semibold whitespace-nowrap flex items-center gap-2 text-sm transition-all">
                        <span class="{{ $activeBowlIndex === $index ? 'bg-brand-red text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }} w-6 h-6 rounded-full flex items-center justify-center text-xs">{{ $index + 1 }}</span>
                        {{ $bowl['nama_pemesan'] }}
                    </button>
                @endforeach
                <button wire:click="addBowl" class="flex items-center gap-1 pb-3 pr-4 text-sm font-medium border-b-2 border-transparent text-brand-orange whitespace-nowrap hover:text-orange-600"><i class="fa-solid fa-circle-plus"></i> Tambah</button>
            </div>
            
            <div class="flex flex-col gap-3 px-4 py-3 transition-colors duration-300 bg-white lg:px-8 dark:bg-gray-900 md:flex-row md:items-center">
                <div class="relative w-full md:w-80">
                    <i class="absolute text-sm text-gray-400 transform -translate-y-1/2 fa-solid fa-magnifying-glass left-3 top-1/2"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari topping favoritmu..." class="w-full bg-gray-100 dark:bg-gray-800 dark:text-white text-sm rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-brand-red text-gray-700 border border-transparent">
                </div>
                
                <div class="flex flex-1 gap-2 overflow-x-auto hide-scroll md:justify-end">
                    <button wire:click="setCategory('all')" class="px-4 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all {{ $activeCategory === 'all' ? 'bg-brand-red text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 border border-gray-200 dark:border-gray-700' }}">Semua</button>
                    @foreach($kategoris as $kat)
                        <button wire:click="setCategory({{ $kat->id_kategori }})" class="px-4 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all {{ $activeCategory == $kat->id_kategori ? 'bg-brand-red text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 border border-gray-200 dark:border-gray-700' }}">{{ $kat->nama }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <div class="relative flex flex-col gap-8 px-4 pt-4 pb-32 mx-auto max-w-7xl lg:pb-8 lg:px-8 lg:flex-row">
        
        <main class="flex-1 min-w-0">
            <div class="flex items-center justify-between pl-1 mb-4 border-l-4 border-brand-orange">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-200">Menu Tersedia</h2>
            </div>
            
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4 auto-rows-fr">
                @forelse($produks as $item)
                    @php
                        // Cek apakah item ini sudah ada di mangkuk aktif
                        $qtyInBowl = $bowls[$activeBowlIndex]['items'][$item->id_produk]['qty'] ?? 0;
                        // Format sanitasi teks agar aman dimasukkan ke Javascript
                        $safeNama = addslashes($item->nama);
                        $safeDeskripsi = addslashes(preg_replace('/\r|\n/', ' ', $item->deskripsi ?? 'Belum ada deskripsi untuk produk ini.'));
                        $gambarUrl = $item->gambar ? asset('storage/'.$item->gambar) : '';
                        $formatHarga = 'Rp ' . number_format($item->harga_jual, 0, ',', '.');
                    @endphp
                    <div class="relative flex flex-col justify-between h-full p-3 transition-colors bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 group hover:shadow-md">
                        
                        <button @click.prevent="openInfo('{{ $safeNama }}', '{{ $safeDeskripsi }}', '{{ $gambarUrl }}', '{{ $formatHarga }}')" 
                                class="absolute z-10 flex items-center justify-center text-gray-500 transition-colors rounded-full shadow-sm top-5 right-5 w-7 h-7 bg-white/90 dark:bg-gray-800/90 backdrop-blur hover:text-brand-red dark:hover:text-brand-red hover:scale-110">
                            <i class="fa-solid fa-circle-info"></i>
                        </button>

                        <div class="relative mb-3">
                            <div class="flex items-center justify-center w-full overflow-hidden bg-gray-200 cursor-pointer aspect-square dark:bg-gray-700 rounded-xl" @click.prevent="openInfo('{{ $safeNama }}', '{{ $safeDeskripsi }}', '{{ $gambarUrl }}', '{{ $formatHarga }}')">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/'.$item->gambar) }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <i class="text-3xl text-gray-400 transition-transform duration-300 fa-solid fa-bowl-food group-hover:scale-110"></i>
                                @endif
                            </div>
                            <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur text-[10px] px-2 py-1 rounded-lg text-gray-600 dark:text-gray-300 font-bold shadow-sm">{{ $formatHarga }}</span>
                        </div>
                        <div>
                            <h3 class="mb-1 text-sm font-bold leading-tight text-gray-800 dark:text-gray-100 line-clamp-2">{{ $item->nama }}</h3>
                            <div class="mt-3">
                                @if($qtyInBowl == 0)
                                    <button wire:click="updateItem({{ $item->id_produk }}, 1)" class="flex items-center justify-center w-full gap-1 px-3 py-2 text-xs font-bold text-white transition-transform rounded-lg shadow-sm bg-brand-red active:scale-95"><i class="fa-solid fa-plus"></i> Tambah</button>
                                @else
                                    <div class="flex items-center justify-between w-full p-1 rounded-lg bg-gray-50 dark:bg-gray-700">
                                        <button wire:click="updateItem({{ $item->id_produk }}, -1)" class="flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded dark:bg-gray-600 text-brand-red"><i class="text-xs fa-solid fa-minus"></i></button>
                                        <span class="text-sm font-bold text-gray-700 dark:text-white">{{ $qtyInBowl }}</span>
                                        <button wire:click="updateItem({{ $item->id_produk }}, 1)" class="flex items-center justify-center w-8 h-8 text-white rounded shadow-sm bg-brand-red"><i class="text-xs fa-solid fa-plus"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400 border border-gray-300 border-dashed col-span-full rounded-2xl dark:border-gray-700">Menu tidak ditemukan.</div>
                @endforelse
            </div>
        </main>

        <aside 
            :class="isCartOpen ? 'translate-y-0' : 'translate-y-[calc(100%-70px)]'"
            class="
            fixed bottom-0 left-0 right-0 z-30 
            max-w-md mx-auto flex flex-col h-[85vh] 
            transition-transform duration-300 ease-in-out
            bg-white dark:bg-gray-800 
            rounded-t-3xl shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.3)] 
            border-t border-gray-200 dark:border-gray-700
            
            lg:transform-none lg:translate-y-0
            lg:sticky lg:top-40 lg:right-0 lg:bottom-auto
            lg:w-[380px] lg:h-[calc(100vh-180px)] lg:max-h-[800px]
            lg:rounded-2xl lg:shadow-xl lg:border
            lg:z-30 lg:mx-0
        ">
            <div @click="isCartOpen = !isCartOpen" class="w-full pt-3 pb-2 transition-colors bg-white border-b border-gray-100 cursor-pointer dark:bg-gray-800 rounded-t-3xl lg:rounded-t-2xl lg:cursor-default dark:border-gray-700">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto mb-2 lg:hidden"></div>
                <div class="flex items-center justify-between px-5">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                        <i class="fa-solid fa-basket-shopping text-brand-red"></i> Pesananmu
                    </h2>
                    <span x-text="isCartOpen ? 'Tutup' : 'Lihat Detail'" class="text-sm font-bold text-brand-red lg:hidden"></span>
                </div>
            </div>

            <div class="flex-1 p-5 pb-24 overflow-y-auto lg:pb-5 bg-gray-50 dark:bg-gray-900 lg:bg-white lg:dark:bg-gray-800 hide-scroll">
                <div class="space-y-4">
                    @foreach($bowls as $index => $bowl)
                        @php $bowlTotal = collect($bowl['items'])->sum(fn($i) => $i['qty'] * $i['harga']); @endphp
                        
                        @if($activeBowlIndex === $index)
                            <div class="relative overflow-hidden transition-colors bg-white border-2 shadow-sm border-brand-red/10 dark:bg-gray-800 rounded-xl">
                                <div class="flex items-center justify-between p-3 border-b border-red-100 bg-red-50/50 dark:bg-red-900/10">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-brand-red"><i class="fa-solid fa-user"></i></div>
                                        <input type="text" wire:model.live.debounce.300ms="bowls.{{$index}}.nama_pemesan" class="font-bold text-gray-800 dark:text-white bg-transparent outline-none border-b border-dashed border-gray-300 dark:border-gray-600 max-w-[120px]">
                                    </div>
                                    <button wire:click="removeBowl({{ $index }})" class="px-2 text-gray-400 hover:text-red-500"><i class="fa-regular fa-trash-can"></i></button>
                                </div>
                                <div class="p-4">
                                    <ul class="pr-1 mb-4 space-y-1">
                                        @forelse($bowl['items'] as $id => $item)
                                            <li class="flex items-start justify-between py-2 border-b border-gray-100 border-dashed dark:border-gray-700">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-brand-red font-bold text-xs bg-red-50 px-1.5 rounded">{{ $item['qty'] }}x</span>
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $item['nama'] }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                                                    <button wire:click="updateItem({{ $id }}, -1)" class="w-5 h-5 text-gray-400 bg-gray-100 rounded hover:text-red-500"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="py-4 text-sm italic text-center text-gray-400 border border-gray-200 border-dashed rounded-lg">Belum ada menu dipilih</li>
                                        @endforelse
                                    </ul>
                                    
                                    <div class="grid grid-cols-1 gap-3 p-3 border border-gray-100 rounded-lg bg-gray-50 dark:bg-gray-900 dark:border-gray-700">
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-gray-400">Kuah</label>
                                            <select wire:model.live="bowls.{{$index}}.tipe_kuah" class="w-full p-2 mt-1 text-xs text-gray-800 bg-white border border-gray-200 rounded-lg outline-none dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                                                <option value="Kuah Kencur (Original)">Kuah Kencur (Original)</option>
                                                <option value="Kuah Jeletot (Merah)">Kuah Jeletot (Merah)</option>
                                                <option value="Kuah Kacang">Kuah Kacang</option>
                                                <option value="Goreng (Tanpa Kuah)">Goreng (Tanpa Kuah)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Level Pedas</label>
                                            <div class="flex gap-1 overflow-x-auto">
                                                @for($i=0; $i<=5; $i++)
                                                    <button wire:click="$set('bowls.{{$index}}.level_pedas', {{ $i }})" class="flex-1 h-7 rounded-md text-[10px] font-bold {{ $bowl['level_pedas'] == $i ? 'bg-brand-red text-white' : 'bg-white dark:bg-gray-800 text-gray-500 border border-gray-200 dark:border-gray-600' }}">{{ $i }}</button>
                                                @endfor
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-gray-400"><i class="fa-solid fa-pencil"></i> Catatan</label>
                                            <textarea wire:model.live.debounce.500ms="bowls.{{$index}}.catatan" class="w-full p-2 mt-1 text-xs bg-white border border-gray-200 rounded outline-none dark:bg-gray-800 dark:text-white" rows="1" placeholder="Cth: Jangan pakai daun bawang..."></textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 mt-3">
                                        <span class="text-xs text-gray-500">Subtotal Mangkuk</span>
                                        <span class="text-lg font-bold text-brand-red">Rp {{ number_format($bowlTotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div wire:click="setActiveBowl({{ $index }})" class="flex items-center justify-between p-3 transition-all bg-white border border-gray-200 shadow-sm cursor-pointer dark:bg-gray-800 rounded-xl opacity-70 hover:opacity-100">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 text-xs font-bold text-gray-500 bg-gray-200 rounded-full dark:bg-gray-700">{{ $index + 1 }}</div>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">{{ $bowl['nama_pemesan'] }}</h3>
                                        <span class="text-[10px] text-gray-500">{{ count($bowl['items']) }} Items | Lvl {{ $bowl['level_pedas'] }}</span>
                                    </div>
                                </div>
                                <span class="pl-2 text-sm font-bold text-gray-700 dark:text-gray-300">Rp {{ number_format($bowlTotal, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="absolute lg:relative bottom-0 left-0 right-0 bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700 shadow-[0_-4px_15px_-3px_rgba(0,0,0,0.1)] lg:shadow-none lg:rounded-b-2xl">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col flex-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total ({{ count($bowls) }} Mangkuk)</span>
                        <span class="text-lg font-bold text-brand-dark dark:text-white">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                    </div>
                    <button wire:click="checkout" wire:loading.attr="disabled" class="flex items-center justify-center flex-1 gap-2 px-4 py-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl active:scale-95 disabled:opacity-50">
                        <span wire:loading.remove>Checkout Pesanan</span>
                        <span wire:loading><i class="fa-solid fa-spinner fa-spin"></i> Loading...</span>
                    </button>
                </div>
            </div>
        </aside>
    </div>

    <div x-show="isCartOpen" @click="isCartOpen = false" x-transition.opacity class="fixed inset-0 z-20 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div x-show="showInfoModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div x-show="showInfoModal" x-transition.opacity @click="showInfoModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div x-show="showInfoModal" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-sm bg-white dark:bg-gray-900 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-hidden">
            
            <button @click="showInfoModal = false" class="absolute z-10 flex items-center justify-center w-8 h-8 text-gray-500 transition-colors rounded-full shadow-sm top-4 right-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur hover:bg-gray-200 dark:hover:bg-gray-700">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="relative flex items-center justify-center w-full mb-5 overflow-hidden bg-gray-100 border border-gray-100 aspect-square dark:bg-gray-800 rounded-2xl dark:border-gray-700">
                <template x-if="activeInfo.gambar">
                    <img :src="activeInfo.gambar" class="object-cover w-full h-full">
                </template>
                <template x-if="!activeInfo.gambar">
                    <i class="text-6xl text-gray-300 fa-solid fa-bowl-food dark:text-gray-600"></i>
                </template>
            </div>

            <div class="px-1">
                <h3 class="mb-1 text-xl font-black leading-tight text-gray-900 dark:text-white" x-text="activeInfo.nama"></h3>
                <p class="mb-4 text-lg font-black text-brand-red" x-text="activeInfo.harga"></p>
                
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700 max-h-[150px] overflow-y-auto hide-scroll">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5"><i class="mr-1 fa-solid fa-circle-info"></i> Deskripsi Topping</p>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300" x-text="activeInfo.deskripsi"></p>
                </div>

                <button @click="showInfoModal = false" class="w-full mt-5 py-3.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>