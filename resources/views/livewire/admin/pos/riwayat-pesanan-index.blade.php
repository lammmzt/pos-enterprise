<div class="pb-20 space-y-10">
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $desc_page }}</p>
    </div>

    <section class="space-y-4">
        <div class="overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
            
            <div class="flex flex-col items-center justify-between gap-4 p-6 border-b border-gray-100 dark:border-gray-800 lg:flex-row bg-gray-50/50 dark:bg-gray-800/20">
                <div class="flex flex-wrap items-center w-full gap-3 lg:w-auto">
                    <select wire:model.live="filterStatus" class="px-4 py-2 text-xs font-bold transition-all bg-white border border-gray-200 border-none outline-none appearance-none cursor-pointer dark:bg-gray-800 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="proses">Proses (Dapur)</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    
                    <select wire:model.live="filterTipe" class="px-4 py-2 text-xs font-bold transition-all bg-white border border-gray-200 border-none outline-none appearance-none cursor-pointer dark:bg-gray-800 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                        <option value="">Semua Tipe</option>
                        <option value="dinein">Dine In</option>
                        <option value="takeaway">Takeaway</option>
                        <option value="delivery">Delivery</option>
                    </select>

                    <input type="date" wire:model.live="filterTanggal" class="px-4 py-2 text-xs font-bold transition-all bg-white border border-gray-200 border-none outline-none cursor-pointer dark:bg-gray-800 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">

                    @if($filterStatus || $filterTipe || $filterTanggal || $search)
                        <button wire:click="$set('filterStatus', ''); $set('filterTipe', ''); $set('filterTanggal', ''); $set('search', '')" class="px-3 py-2 text-xs font-bold text-red-500 transition-colors bg-red-50 rounded-xl hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40">
                            Reset Filter
                        </button>
                    @endif
                </div>

                <div class="relative w-full lg:w-80">
                    <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl pl-11 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari No. Invoice / Pelanggan...">
                </div>
            </div>

            <div class="relative overflow-x-auto font-mono text-xs min-h-[400px]">
                <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center transition-all bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                    <div class="flex flex-col items-center gap-2">
                        <i class="text-4xl text-indigo-600 ti ti-loader-2 animate-spin"></i>
                        <span class="text-[10px] font-bold tracking-widest text-indigo-600 uppercase">Loading Data...</span>
                    </div>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase">Waktu / Invoice</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase">Pelanggan & Kasir</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase">Tipe Pesanan</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase">Total / Pembayaran</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase">Status Pesanan</th>
                            <th class="px-6 py-4 font-bold text-center text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($pesanans as $item)
                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $item->nomor_invoice }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $item->user->nama ?? 'Walk-in (Umum)' }}</span>
                                        <span class="text-[10px] text-gray-500">Kasir: {{ $item->kasir->nama ?? 'Sistem' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md 
                                        {{ $item->tipe_pesanan == 'dinein' ? 'bg-blue-100 text-blue-700' : ($item->tipe_pesanan == 'takeaway' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700') }}">
                                        {{ $item->tipe_pesanan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-black text-gray-900 dark:text-white">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                                        <div class="flex gap-2">
                                            <span class="text-[10px] text-gray-500">{{ $item->metode_pembayaran }}</span>
                                            <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase {{ $item->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $item->status_pembayaran }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status_pesanan === 'selesai')
                                        <span class="px-3 py-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 rounded-full dark:bg-emerald-500/10"><i class="ti ti-check"></i> Selesai</span>
                                    @elseif($item->status_pesanan === 'proses')
                                        <span class="px-3 py-1 text-[10px] font-bold text-yellow-600 bg-yellow-50 rounded-full dark:bg-yellow-500/10 animate-pulse"><i class="ti ti-loader"></i> Dapur</span>
                                    @else
                                        <span class="px-3 py-1 text-[10px] font-bold text-red-600 bg-red-50 rounded-full dark:bg-red-500/10"><i class="ti ti-x"></i> Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="showDetail({{ $item->id_pesanan }})" title="Lihat Detail Transaksi" class="p-2 text-indigo-500 transition-colors bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50">
                                            <i class="ti ti-file-invoice"></i>
                                        </button>
                                        <button wire:click="cetakUlangStruk({{ $item->id_pesanan }})" title="Cetak Ulang Struk Thermal" class="p-2 text-gray-500 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">
                                            <i class="ti ti-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 font-medium text-center text-gray-400">
                                    Tidak ada data pesanan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-gray-100 dark:border-gray-800">{{ $pesanans->links() }}</div>
        </div>
    </section>

    {{-- MODAL DETAIL TRANSAKSI --}}
    <section x-data="{ open: @entangle('isDetailModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10002] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-3xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    
                    @if($detailPesanan)
                        <div class="flex items-start justify-between pb-4 mb-6 border-b border-gray-100 dark:border-gray-800">
                            <div>
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white">{{ $detailPesanan->nomor_invoice }}</h4>
                                <p class="mt-1 text-xs text-gray-500">{{ $detailPesanan->created_at->format('d F Y, H:i:s') }} | Kasir: {{ $detailPesanan->kasir->nama ?? 'Sistem' }}</p>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="cetakUlangStruk({{ $detailPesanan->id_pesanan }})" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-indigo-600 transition-colors bg-indigo-50 rounded-xl hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    <i class="ti ti-printer"></i> Cetak Struk
                                </button>
                                <button wire:click="closeModal" class="p-2 text-gray-400 transition-colors rounded-xl hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 border border-gray-100 bg-gray-50 dark:bg-gray-800/50 rounded-2xl dark:border-gray-700">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Data Pelanggan</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $detailPesanan->user->nama ?? 'Walk-in Customer' }}</p>
                                <p class="mt-1 text-xs text-gray-500">Tipe: <span class="font-bold text-indigo-500 uppercase">{{ $detailPesanan->tipe_pesanan }}</span></p>
                                @if($detailPesanan->tipe_pesanan === 'delivery')
                                    <p class="text-[10px] text-orange-500 mt-2"><i class="ti ti-truck"></i> {{ $detailPesanan->link_delivery ?: 'Tidak ada detail alamat' }}</p>
                                @endif
                            </div>
                            <div class="p-4 border border-gray-100 bg-gray-50 dark:bg-gray-800/50 rounded-2xl dark:border-gray-700">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status Transaksi</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-1 rounded font-bold uppercase {{ $detailPesanan->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $detailPesanan->status_pembayaran }}</span>
                                    <span class="text-xs px-2 py-1 rounded font-bold uppercase {{ $detailPesanan->status_pesanan === 'selesai' ? 'bg-indigo-100 text-indigo-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $detailPesanan->status_pesanan }}</span>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Metode: <span class="font-bold">{{ $detailPesanan->metode_pembayaran }}</span></p>
                            </div>
                        </div>

                        <div class="space-y-4 font-mono">
                            <h5 class="pb-2 text-sm font-bold text-gray-900 border-b border-gray-200 dark:text-white dark:border-gray-700">Rincian Pemesanan ({{ count($detailPesanan->mangkuk) }} Mangkuk)</h5>
                            
                            @foreach($detailPesanan->mangkuk as $idx => $mangkuk)
                                <div class="relative p-4 border border-gray-100 dark:border-gray-700 rounded-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h6 class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#{{ $idx + 1 }} - A/n: {{ $mangkuk->nama_pemesan }}</h6>
                                            <p class="text-[10px] text-gray-500">{{ $mangkuk->tipe_kuah }} | Lvl Pedas: {{ $mangkuk->level_pedas }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 space-y-2 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                        @foreach($mangkuk->detailPesanan as $detail)
                                            <div class="flex items-center justify-between text-xs">
                                                <div class="flex-1">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $detail->produk->nama }}</span>
                                                    <span class="ml-2 text-gray-500">x{{ $detail->jumlah }}</span>
                                                    <span class="text-[9px] text-gray-400 block" title="Data Batch FEFO">Batch: #{{ $detail->id_batch }} | HPP: Rp {{ number_format($detail->batch->harga_beli ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between pt-4 mt-6 border-t border-gray-200 border-dashed dark:border-gray-700">
                            <span class="text-sm font-bold tracking-widest text-gray-500 uppercase">Grand Total</span>
                            <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($detailPesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    @endif
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
            let printWindow = window.open(`/admin/pos/struk/${id}`, 'Cetak Struk', 'width=400,height=600');
            printWindow.onload = function() {
                printWindow.print();
            };
        });
    });
</script>
@endpush