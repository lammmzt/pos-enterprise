<div class="pb-20 space-y-6" wire:poll.10s>
    
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Pesanan Aktif (Dapur)</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Daftar pesanan yang sedang menunggu untuk dibuat.</p>
        </div>
        
        <div class="flex items-center w-full gap-3 md:w-auto">
            <button wire:click="$refresh" title="Refresh Antrean" class="p-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:border-indigo-200 dark:hover:border-indigo-800 hover:text-indigo-600 transition-all active:scale-90 flex items-center group">
                <i class="text-lg transition-transform duration-500 ti ti-refresh" wire:loading.class="text-indigo-600 animate-spin"></i>
            </button>

            <div class="relative w-full md:w-72">
                <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl pl-11 pr-4 py-2.5 text-sm font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari No. Invoice / Pelanggan...">
            </div>
        </div>
    </div>

    <div wire:loading.flex class="fixed z-50 items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-indigo-600 shadow-lg top-4 right-4 rounded-xl animate-pulse">
        <i class="text-lg ti ti-loader-2 animate-spin"></i> Memperbarui Antrean...
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($pesanans as $pesanan)
            <div class="bg-white dark:bg-gray-900 border {{ $pesanan->tipe_pesanan === 'delivery' ? 'border-orange-300 dark:border-orange-800' : 'border-gray-200 dark:border-gray-800' }} rounded-2xl shadow-sm overflow-hidden flex flex-col">
                
                <div class="p-4 {{ $pesanan->tipe_pesanan === 'delivery' ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-gray-50 dark:bg-gray-800/50' }} border-b border-gray-100 dark:border-gray-800 flex justify-between items-start">
                    <div>
                        <div class="flex gap-2 mb-2">
                            <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase rounded-md {{ $pesanan->tipe_pesanan == 'dinein' ? 'bg-blue-100 text-blue-700' : ($pesanan->tipe_pesanan == 'takeaway' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700') }}">
                                {{ $pesanan->tipe_pesanan }}
                            </span>
                            @if($pesanan->status_pembayaran === 'belum_bayar')
                                <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-red-100 text-red-700 animate-pulse">Belum Bayar</span>
                            @else
                                <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-emerald-100 text-emerald-700">Lunas</span>
                            @endif
                        </div>
                        <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ $pesanan->nomor_invoice }}</h3>
                        <p class="text-xs font-medium text-gray-500">Oleh: <span class="text-gray-900 dark:text-gray-300">{{ $pesanan->pelanggan->nama ?? 'Walk-in' }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-gray-400">{{ $pesanan->created_at->diffForHumans() }}</p>
                        <p class="text-[10px] text-gray-500">{{ $pesanan->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex-1 p-4 space-y-4 overflow-y-auto max-h-[300px]">
                    @if($pesanan->catatan)
                        <div class="p-2 text-xs font-bold text-red-700 border border-red-100 rounded-lg bg-red-50 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50">
                            <i class="ti ti-alert-circle"></i> Catatan Global: {{ $pesanan->catatan }}
                        </div>
                    @endif

                    @if($pesanan->tipe_pesanan === 'delivery')
                        <div class="p-3 text-xs border border-orange-100 bg-orange-50 dark:bg-orange-900/10 dark:border-orange-800/50 rounded-xl">
                            <span class="font-bold text-orange-800 dark:text-orange-400"><i class="ti ti-truck"></i> Info Pengiriman:</span>
                            <p class="mt-1 text-gray-600 truncate dark:text-gray-400">{{ $pesanan->link_delivery ?: 'Belum ada alamat/link diset.' }}</p>
                        </div>
                    @endif

                    @foreach($pesanan->mangkuk as $index => $mangkuk)
                        <div class="relative p-3 bg-white border border-gray-100 dark:border-gray-700 dark:bg-gray-800 rounded-xl">
                            <div class="absolute flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-indigo-500 rounded-full shadow-sm -top-3 -left-2">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <h4 class="text-xs font-bold text-gray-900 dark:text-white">A/n: <span class="text-indigo-600 dark:text-indigo-400">{{ $mangkuk->nama_pemesan }}</span></h4>
                                <p class="text-[10px] text-gray-500 font-medium mb-2 pb-2 border-b border-dashed border-gray-200 dark:border-gray-700">
                                    {{ $mangkuk->tipe_kuah }} | Lvl: {{ $mangkuk->level_pedas }}
                                    @if($mangkuk->catatan) <br><span class="text-red-500">*Note: {{ $mangkuk->catatan }}</span> @endif
                                </p>
                                <ul class="space-y-1">
                                    @foreach($mangkuk->detailPesanan as $detail)
                                        <li class="flex justify-between items-center text-[11px]">
                                            <span class="font-bold text-gray-700 dark:text-gray-300">- {{ $detail->produk->nama }}</span>
                                            <span class="text-gray-500">x{{ $detail->jumlah }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-3 space-y-2 border-t border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                    @if($pesanan->status_pembayaran === 'belum_bayar')
                        <button wire:click="openModalPembayaran({{ $pesanan->id_pesanan }})" class="flex items-center justify-center w-full gap-2 py-2 text-xs font-bold text-white transition-all bg-yellow-500 shadow-sm rounded-xl hover:bg-yellow-600">
                            <i class="ti ti-cash"></i> Konfirmasi Bayar
                        </button>
                    @endif

                    @if($pesanan->tipe_pesanan === 'delivery')
                        <button wire:click="openModalDelivery({{ $pesanan->id_pesanan }}, '{{ $pesanan->link_delivery }}')" class="flex items-center justify-center w-full gap-2 py-2 text-xs font-bold text-indigo-700 transition-all bg-indigo-100 shadow-sm rounded-xl hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">
                            <i class="ti ti-link"></i> Update Link Pengiriman (Gojek/Grab)
                        </button>
                    @endif

                    <button wire:click="openModalSelesai({{ $pesanan->id_pesanan }})" class="w-full py-2.5 text-sm font-bold text-white transition-all {{ $pesanan->status_pembayaran === 'belum_bayar' ? 'bg-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 shadow-sm hover:shadow' }} rounded-xl flex justify-center items-center gap-2">
                        <i class="ti ti-check"></i> Pesanan Selesai
                    </button>
                </div>

            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400 bg-white border border-gray-200 border-dashed col-span-full dark:bg-gray-900 rounded-3xl dark:border-gray-800">
                <i class="mb-4 text-5xl text-gray-300 ti ti-soup dark:text-gray-700"></i>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Dapur Kosong!</h3>
                <p class="text-sm">Belum ada pesanan aktif saat ini.</p>
            </div>
        @endforelse
    </div>

    <div x-data="{ open: @entangle('showModalPembayaran') }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md p-6 text-center bg-white border border-gray-100 shadow-xl dark:bg-gray-900 rounded-2xl dark:border-gray-800">
            
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl text-yellow-500 bg-yellow-100 rounded-full"><i class="ti ti-cash"></i></div>
            <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Pembayaran</h3>
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Pastikan Anda telah menerima pembayaran dari pelanggan ini.</p>
            
            <div class="p-4 mb-6 space-y-3 text-left border border-gray-100 bg-gray-50 dark:bg-gray-800/50 rounded-xl dark:border-gray-700">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-gray-500">Pelanggan</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $paymentCustomer }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-gray-500">Metode</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $paymentMethod }}</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200 border-dashed dark:border-gray-700">
                    <span class="text-xs font-bold text-gray-500 uppercase">Total Tagihan</span>
                    <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($paymentTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex gap-3">
                <button wire:click="resetModal" class="flex-1 px-4 py-3 font-bold text-gray-700 transition-colors bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                <button wire:click="konfirmasiPembayaran" class="flex items-center justify-center flex-1 gap-2 px-4 py-3 font-bold text-white transition-colors bg-yellow-500 shadow-md rounded-xl hover:bg-yellow-600">
                    <i class="ti ti-printer"></i> Bayar & Cetak
                </button>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModalDelivery') }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md p-6 bg-white border border-gray-100 shadow-xl dark:bg-gray-900 rounded-2xl dark:border-gray-800">
            <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white"><i class="text-indigo-500 ti ti-truck"></i> Kirim Pesanan (Delivery)</h3>
            <p class="mb-4 text-xs text-gray-500">Menyimpan link resi akan otomatis mengubah status pesanan menjadi <span class="font-bold text-emerald-500">Selesai</span>.</p>
            
            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">Link Gojek / Grab / Resi</label>
            <textarea wire:model="inputLinkDelivery" rows="3" class="w-full px-4 py-3 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="https://gojek.com/track/..."></textarea>
            
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="resetModal" class="px-5 py-2.5 font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                <button wire:click="simpanLinkDelivery" class="px-5 py-2.5 font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md">Simpan & Selesai</button>
            </div>
        </div>
    </div>

    <div x-data="{ open: @entangle('showModalSelesai') }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md p-6 text-center bg-white border border-gray-100 shadow-xl dark:bg-gray-900 rounded-2xl dark:border-gray-800">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-3xl rounded-full bg-emerald-100 text-emerald-600"><i class="ti ti-check"></i></div>
            <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Pesanan Selesai?</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Pastikan pesanan sudah selesai dimasak dan siap disajikan/dikirim. Aksi ini akan menghapus pesanan dari antrean dapur.</p>
            <div class="flex gap-3">
                <button wire:click="resetModal" class="flex-1 px-4 py-2 font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                <button wire:click="selesaikanPesanan" class="flex-1 px-4 py-2 font-bold text-white shadow-md bg-emerald-600 rounded-xl hover:bg-emerald-700">Ya, Selesai</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK TRIGGER PRINT STRUK THERMAL OTOMATIS --}}
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cetak-struk', (event) => {
            let id = event.id_pesanan;
            let printWindow = window.open(`/admin/pos/struk/${id}`, 'Cetak Struk', 'width=400,height=600');
            printWindow.onload = function() {
                printWindow.print();
            };
        });
    });
</script>
@endpush