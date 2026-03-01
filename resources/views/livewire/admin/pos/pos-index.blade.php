<div class="flex flex-col gap-6 pb-20 lg:flex-row">
    
    <div class="flex-1 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kasir (POS)</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pilih produk ke mangkuk yang aktif.</p>
            </div>
            
            <div class="flex flex-col gap-3 md:flex-row">
                <select wire:model.live="filterKategori" class="px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none appearance-none transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>

                <div class="relative w-full md:w-64">
                    <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl pl-11 pr-4 py-2.5 text-sm font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari nama / SKU...">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
            @forelse($produks as $produk)
                <button wire:click="addToCart({{ $produk->id_produk }})" class="relative flex flex-col p-4 text-left transition-all bg-white border border-gray-100 shadow-sm dark:bg-gray-900 rounded-2xl dark:border-gray-800 hover:shadow-md hover:border-indigo-500 dark:hover:border-indigo-500 group">
                    <div class="flex items-center justify-center w-full h-24 mb-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" class="object-cover w-full h-full rounded-xl">
                        @else
                            <i class="text-4xl text-gray-300 transition-transform ti ti-photo dark:text-gray-600 group-hover:scale-110"></i>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 truncate dark:text-white">{{ $produk->nama }}</h3>
                    <p class="mt-1 text-xs text-gray-500 line-clamp-1">{{ $produk->kategori->nama ?? '-' }}</p>
                    <div class="flex items-end justify-between mt-3">
                        <span class="text-base font-extrabold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $produk->stok > 10 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10' : 'bg-red-50 text-red-600 dark:bg-red-500/10' }}">
                            Sisa: {{ $produk->stok }}
                        </span>
                    </div>
                </button>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-gray-400 bg-white border border-gray-100 border-dashed col-span-full dark:bg-gray-900 rounded-3xl dark:border-gray-800">
                    <i class="mb-3 text-4xl ti ti-box-off"></i>
                    <p class="text-sm font-bold">Produk tidak ditemukan atau stok habis.</p>
                </div>
            @endforelse
        </div>
        
        <div>{{ $produks->links() }}</div>
    </div>

    <div class="w-full lg:w-[450px] flex flex-col bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden h-fit sticky top-24">
        
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="mr-2 ti ti-soup"></i> Pesanan
            </h2>
            <button wire:click="addBowl" class="px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg hover:bg-indigo-100 transition-colors">
                + Tambah Mangkuk
            </button>
        </div>

        <div class="flex-1 p-4 overflow-y-auto max-h-[60vh] space-y-3 bg-gray-50/50 dark:bg-gray-900/50">
            @foreach($bowls as $index => $bowl)
                <div class="transition-all bg-white border shadow-sm dark:bg-gray-800 rounded-2xl overflow-hidden {{ $activeBowlIndex === $index ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300' }}">
                    
                    <div wire:click="setActiveBowl({{ $index }})" class="flex items-center justify-between p-4 cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $activeBowlIndex === $index ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30' : 'bg-gray-100 text-gray-500 dark:bg-gray-700' }}">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $bowl['nama_pemesan'] }}</h4>
                                <p class="text-[10px] text-gray-500">{{ count($bowl['items']) }} Item | Lvl: {{ $bowl['level_pedas'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if($activeBowlIndex === $index)
                                <span class="px-2 py-1 text-[10px] font-bold text-indigo-600 bg-indigo-50 rounded-md dark:bg-indigo-500/10">Aktif</span>
                            @endif
                            <button wire:click.stop="removeBowl({{ $index }})" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30" title="Hapus Mangkuk">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>

                    @if($activeBowlIndex === $index)
                        <div class="p-4 space-y-4 border-t border-gray-100 bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Nama Pemesan / Label</label>
                                    <input type="text" wire:model.live.debounce.300ms="bowls.{{ $index }}.nama_pemesan" class="w-full px-3 py-2 mt-1 text-xs font-bold bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Kuah</label>
                                    <select wire:model.live="bowls.{{ $index }}.tipe_kuah" class="w-full px-3 py-2 mt-1 text-xs font-bold bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="Original">Original</option>
                                        <option value="Seblak Kencur">Seblak Kencur</option>
                                        <option value="Kuah Kacang">Kuah Kacang</option>
                                        <option value="Goreng (Tanpa Kuah)">Goreng</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Level Pedas</label>
                                    <select wire:model.live="bowls.{{ $index }}.level_pedas" class="w-full px-3 py-2 mt-1 text-xs font-bold bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="0">0 (Tidak Pedas)</option>
                                        <option value="1">1 (Sedang)</option>
                                        <option value="2">2 (Pedas)</option>
                                        <option value="3">3 (Ekstra Pedas)</option>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Catatan</label>
                                    <input type="text" wire:model.live.debounce.300ms="bowls.{{ $index }}.catatan" placeholder="Cth: Jangan pakai bawang..." class="w-full px-3 py-2 mt-1 text-xs text-gray-500 bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                </div>
                            </div>

                            <div class="pt-2 mt-2 border-t border-gray-200 border-dashed dark:border-gray-700">
                                <h5 class="mb-3 text-xs font-bold text-gray-900 dark:text-white">Topping & Minuman</h5>
                                
                                <div class="space-y-2">
                                    @forelse($bowl['items'] as $id => $item)
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 pr-2">
                                                <p class="text-xs font-bold text-gray-800 truncate dark:text-gray-200">{{ $item['nama'] }}</p>
                                                <p class="text-[10px] font-bold text-indigo-500">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="flex items-center bg-white border border-gray-200 rounded-lg dark:bg-gray-900 dark:border-gray-700">
                                                <button wire:click="updateQty({{ $id }}, 'decrease')" class="px-2 py-1 text-gray-500 hover:text-red-500"><i class="ti ti-minus text-[10px]"></i></button>
                                                <span class="w-6 text-xs font-bold text-center">{{ $item['qty'] }}</span>
                                                <button wire:click="updateQty({{ $id }}, 'increase')" class="px-2 py-1 text-gray-500 hover:text-indigo-500"><i class="ti ti-plus text-[10px]"></i></button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-3 text-center bg-white border border-gray-100 border-dashed rounded-xl dark:bg-gray-900 dark:border-gray-700">
                                            <span class="text-[10px] font-bold text-gray-400">Pilih menu dari daftar sebelah kiri</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="p-5 bg-white border-t border-gray-100 dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Total Tagihan</span>
                <span class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
            </div>
            
            <button wire:click="openCheckoutModal" class="w-full py-3.5 text-sm font-bold text-white transition-all bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                <i class="ti ti-cash"></i> Checkout Transaksi
            </button>
        </div>
    </div>

    <section x-data="{ open: @entangle('isCheckoutModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10005] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi & Pembayaran</h4>
                        <button type="button" wire:click="$set('isCheckoutModalOpen', false)" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>
                    
                    <form class="space-y-5 font-mono text-sm">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Pilih Pelanggan <span class="text-red-500">*</span></label>
                                
                                <div wire:ignore class="mt-1" x-data="{
                                    init() {
                                        $($refs.select).select2({ placeholder: '-- Pilih Pelanggan --', width: '100%', dropdownParent: $($el) })
                                        .on('change', (e) => { 
                                            $wire.set('id_pelanggan', e.target.value); 
                                        });
                                        $watch('$wire.id_pelanggan', (value) => { $($refs.select).val(value).trigger('change.select2'); });
                                    }
                                }">
                                    <select x-ref="select" class="w-full">
                                        <option value="">-- Cari & Pilih Pelanggan --</option>
                                        @foreach($pelanggans as $pel)
                                            <option value="{{ $pel->id_user }}">{{ $pel->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Tipe Pesanan</label>
                                <div class="relative mt-1">
                                    <select wire:model.live="tipe_pesanan" class="w-full py-2 pl-4 pr-10 border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="dinein">Makan di Tempat (Dine In)</option>
                                        <option value="takeaway">Bawa Pulang (Take-away)</option>
                                        <option value="delivery">Pengiriman (Delivery)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none"><i class="text-gray-400 ti ti-chevron-down"></i></div>
                                </div>
                            </div>
                        </div>

                        @if($tipe_pesanan === 'delivery')
                            <div class="p-4 space-y-4 border border-indigo-100 rounded-2xl bg-indigo-50/50 dark:bg-indigo-900/10 dark:border-indigo-800/30">
                                <h5 class="text-xs font-bold tracking-widest text-indigo-600 uppercase dark:text-indigo-400"><i class="ti ti-truck"></i> Informasi Pengiriman</h5>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block font-bold text-gray-700 dark:text-gray-300">No. HP / WhatsApp</label>
                                        <input type="text" wire:model="nomor_hp_pengiriman" class="w-full px-4 py-2 mt-1 bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="08123456789">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block font-bold text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                                        <textarea wire:model="alamat_pengiriman" rows="2" class="w-full px-4 py-2 mt-1 bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Alamat pengiriman pelanggan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="p-5 mt-4 border border-gray-100 bg-gray-50 rounded-2xl dark:bg-gray-800/50 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-bold text-gray-500 dark:text-gray-400">Total Pembayaran</span>
                                <span class="text-3xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block font-bold text-gray-700 dark:text-gray-300">Metode Bayar</label>
                                    <div class="relative mt-1">
                                        <select wire:model.live="metode_pembayaran" class="w-full py-3 pl-4 pr-10 bg-white border-none outline-none appearance-none cursor-pointer dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                            <option value="Tunai">Tunai (Cash)</option>
                                            <option value="QRIS (Kasir)">QRIS (Scan di Kasir)</option>
                                            </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none"><i class="text-gray-400 ti ti-chevron-down"></i></div>
                                    </div>
                                </div>

                                @if($metode_pembayaran === 'Tunai')
                                    <div>
                                        <label class="block font-bold text-gray-700 dark:text-gray-300">Uang Diterima (Rp)</label>
                                        <div class="mt-1" x-data="{ 
                                                raw: @entangle('uang_diterima').live, 
                                                display: '',
                                                init() {
                                                    this.display = this.formatRibuan(this.raw);
                                                    $watch('raw', value => this.display = this.formatRibuan(value));
                                                },
                                                formatRibuan(angka) {
                                                    if (!angka) return '';
                                                    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                },
                                                updateRaw() {
                                                    this.raw = this.display.replace(/\./g, '');
                                                }
                                            }">
                                            <input type="text" x-model="display" @input="updateRaw()" class="w-full px-4 py-3 text-lg font-bold text-right bg-white border-none outline-none dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="0">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($metode_pembayaran === 'Tunai')
                                <div class="flex items-center justify-between p-4 mt-4 bg-white dark:bg-gray-900 rounded-xl">
                                    <span class="font-bold text-gray-500">Kembalian</span>
                                    <span class="text-xl font-black {{ $kembalian >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                                        Rp {{ number_format($kembalian, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300">Catatan Global (Opsional)</label>
                            <input type="text" wire:model="catatan_global" placeholder="Cth: Tolong selesaikan cepat..." class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                        </div>

                        <div class="flex justify-end gap-3 pt-6 mt-6 border-t dark:border-gray-800">
                            <button type="button" wire:click="$set('isCheckoutModalOpen', false)" class="px-6 py-3 text-sm font-bold text-gray-700 transition-all bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded-xl hover:bg-gray-200">Batal</button>
                            <button type="button" wire:click="prosesCheckout" class="px-6 py-3 text-sm font-bold text-white transition-all bg-indigo-600 shadow-md rounded-xl hover:bg-indigo-700">
                                <i class="mr-2 ti ti-printer"></i> Bayar & Cetak Struk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </section>
</div>

{{-- SCRIPT UNTUK TRIGGER PRINT STRUK THERMAL --}}
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cetak-struk', (event) => {
            let id = event.id_pesanan;
            // Membuka route khusus cetak struk
            let printWindow = window.open(`/admin/pos/struk/${id}`, 'Cetak Struk', 'width=400,height=600'); 
            // Tunggu hingga konten load lalu jalankan print otomatis
            printWindow.onload = function() {
                printWindow.print();
            };
        });
    });
</script>
@endpush