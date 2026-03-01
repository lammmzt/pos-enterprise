<div class="pb-20 space-y-8">
    
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $desc_page }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 p-2 bg-white border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800 rounded-2xl">
                <div class="flex flex-col px-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Dari Tanggal</span>
                    <input type="date" wire:model.live="tanggalMulai" class="p-0 text-sm font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer dark:text-gray-200 focus:ring-0">
                </div>
                <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                <div class="flex flex-col px-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Sampai Tanggal</span>
                    <input type="date" wire:model.live="tanggalAkhir" class="p-0 text-sm font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer dark:text-gray-200 focus:ring-0">
                </div>
            </div>

            <button wire:click="cetakLaporan" class="px-5 py-3.5 text-sm font-bold text-white transition-all bg-indigo-600 rounded-2xl hover:bg-indigo-700 shadow-sm flex items-center gap-2">
                <i class="ti ti-printer"></i> Cetak PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="relative flex flex-col justify-between p-6 overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800 rounded-3xl group">
            <div class="absolute transition-transform -right-6 -top-6 text-indigo-50 dark:text-indigo-900/20 group-hover:scale-110">
                <i class="ti ti-wallet text-9xl"></i>
            </div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Total Pendapatan</p>
                <h3 class="mb-1 text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-emerald-500 font-bold bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-md inline-block">Omset Penjualan Kotor</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800 rounded-3xl group">
            <div class="absolute transition-transform -right-6 -top-6 text-red-50 dark:text-red-900/20 group-hover:scale-110">
                <i class="ti ti-receipt-2 text-9xl"></i>
            </div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Beban Pokok (HPP)</p>
                <h3 class="mb-1 text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($totalHpp, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-red-500 font-bold bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded-md inline-block">Total Modal Barang (FEFO)</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden text-white bg-indigo-600 border border-indigo-700 shadow-md rounded-3xl group">
            <div class="absolute transition-transform -right-4 -bottom-4 text-indigo-500/50 group-hover:scale-110">
                <i class="ti ti-chart-arrows-vertical text-9xl"></i>
            </div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-indigo-200 uppercase">Laba Kotor (Profit)</p>
                <h3 class="mb-1 text-2xl font-black">Rp {{ number_format($labaKotor, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-white font-bold bg-indigo-500/50 px-2 py-1 rounded-md inline-block">Pendapatan - HPP</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800 rounded-3xl group">
            <div class="absolute transition-transform -right-4 -top-4 text-emerald-50 dark:text-emerald-900/20 group-hover:scale-110">
                <i class="ti ti-percentage text-9xl"></i>
            </div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Rata-rata Margin</p>
                <h3 class="text-3xl font-black {{ $marginPersen >= 40 ? 'text-emerald-500' : ($marginPersen >= 20 ? 'text-yellow-500' : 'text-red-500') }} mb-1">
                    {{ $marginPersen }}%
                </h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">
                    Ideal restoran f&b umumnya > 40%
                </p>
            </div>
        </div>
    </div>

    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Analisis Profitabilitas per Menu</h3>
            
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase">Show</span>
                <select wire:model.live="view" class="px-3 py-1.5 text-xs font-bold border-none bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center transition-all bg-white/60 dark:bg-gray-900/60 backdrop-blur-[2px]">
                <div class="flex flex-col items-center gap-2">
                    <i class="text-4xl text-indigo-600 ti ti-loader-2 animate-spin"></i>
                </div>
            </div>

            <table class="w-full font-mono text-sm text-left border-collapse">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                        <th class="px-6 py-4 font-bold">Nama Menu/Topping</th>
                        <th class="px-6 py-4 font-bold text-center">Qty Terjual</th>
                        <th class="px-6 py-4 font-bold text-right">Pendapatan</th>
                        <th class="px-6 py-4 font-bold text-right">Total HPP</th>
                        <th class="px-6 py-4 font-bold text-right">Laba Bersih</th>
                        <th class="px-6 py-4 font-bold text-center">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($laporanProduk as $item)
                        @php
                            $laba = $item->pendapatan_produk - $item->hpp_produk;
                            $margin = $item->pendapatan_produk > 0 ? round(($laba / $item->pendapatan_produk) * 100, 1) : 0;
                        @endphp
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $item->nama_produk }}</td>
                            <td class="px-6 py-4 font-bold text-center text-indigo-600 dark:text-indigo-400">{{ $item->qty_terjual }}</td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($item->pendapatan_produk, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-gray-500">Rp {{ number_format($item->hpp_produk, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-bold text-right text-emerald-600 dark:text-emerald-400">Rp {{ number_format($laba, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold {{ $margin >= 40 ? 'bg-emerald-100 text-emerald-700' : ($margin >= 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $margin }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 font-medium text-center text-gray-400">
                                Tidak ada data penjualan yang selesai pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100 dark:border-gray-800">
            {{ $laporanProduk->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cetak-laporan', (event) => {
            let url = `/admin/laporan-keuangan/cetak?start=${event.start}&end=${event.end}`;
            let printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        });
    });
</script>
@endpush