<div class="pb-20 space-y-8">
    
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $desc_page }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 p-2 transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800 rounded-2xl">
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

    <div wire:loading.flex class="fixed z-50 items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-indigo-600 shadow-lg top-4 right-4 rounded-xl animate-pulse">
        <i class="text-lg ti ti-loader-2 animate-spin"></i> Memuat Laporan...
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="relative flex flex-col justify-between p-6 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-indigo-50 -right-6 -top-6 dark:text-indigo-900/20 group-hover:scale-110"><i class="text-9xl ti ti-receipt"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Total Transaksi</p>
                <h3 class="mb-1 text-3xl font-black text-gray-900 dark:text-white">{{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Pesanan selesai</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-emerald-50 -right-6 -top-6 dark:text-emerald-900/20 group-hover:scale-110"><i class="text-9xl ti ti-box"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Item Terjual</p>
                <h3 class="mb-1 text-3xl font-black text-gray-900 dark:text-white">{{ number_format($totalItemTerjual, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Porsi / Topping keluar</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden text-white transition-all bg-indigo-600 border border-indigo-700 shadow-md rounded-3xl group">
            <div class="absolute transition-transform text-indigo-500/50 -right-4 -bottom-4 group-hover:scale-110"><i class="text-9xl ti ti-businessplan"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-indigo-200 uppercase">Total Pendapatan</p>
                <h3 class="mb-1 text-2xl font-black">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-white font-bold bg-indigo-500/50 px-2 py-1 rounded-md inline-block mt-1">Omset Penjualan Kotor</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between p-6 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-orange-50 -right-4 -top-4 dark:text-orange-900/20 group-hover:scale-110"><i class="text-9xl ti ti-shopping-cart"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Rata-rata Order (AOV)</p>
                <h3 class="mb-1 text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Nominal belanja per pelanggan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="font-bold text-gray-900 dark:text-white"><i class="mr-2 text-indigo-500 ti ti-bike"></i> Kinerja Tipe Pesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                            <th class="px-5 py-3 font-bold">Tipe Pesanan</th>
                            <th class="px-5 py-3 font-bold text-center">Transaksi</th>
                            <th class="px-5 py-3 font-bold text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($laporanTipePesanan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="px-5 py-3 font-bold uppercase">{{ $item->tipe_pesanan }}</td>
                                <td class="px-5 py-3 text-center">{{ $item->jumlah_transaksi }}</td>
                                <td class="px-5 py-3 font-bold text-right text-emerald-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="font-bold text-gray-900 dark:text-white"><i class="mr-2 ti ti-cash text-emerald-500"></i> Rekap Metode Pembayaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                            <th class="px-5 py-3 font-bold">Metode</th>
                            <th class="px-5 py-3 font-bold text-center">Transaksi</th>
                            <th class="px-5 py-3 font-bold text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($laporanMetodeBayar as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                <td class="px-5 py-3 font-bold">{{ $item->metode_pembayaran ?: 'Lainnya' }}</td>
                                <td class="px-5 py-3 text-center">{{ $item->jumlah_transaksi }}</td>
                                <td class="px-5 py-3 font-bold text-right text-emerald-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
            <h3 class="font-bold text-gray-900 dark:text-white"><i class="mr-2 text-blue-500 ti ti-calendar"></i> Tren Penjualan Harian</h3>
        </div>
        <div class="overflow-x-auto max-h-[400px]">
            <table class="relative w-full font-mono text-sm text-left border-collapse">
                <thead class="sticky top-0 z-10 text-xs text-gray-400 uppercase border-b border-gray-100 shadow-sm bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 font-bold">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-center">Jumlah Transaksi</th>
                        <th class="px-6 py-4 font-bold text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($trenHarian as $item)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-6 py-4 font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->jumlah_transaksi }} Order</td>
                            <td class="px-6 py-4 font-black text-right text-indigo-600 dark:text-indigo-400">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 font-medium text-center text-gray-400">Tidak ada data penjualan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cetak-laporan-penjualan', (event) => {
            let url = `/admin/laporan-penjualan/cetak?start=${event.start}&end=${event.end}`;
            let printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        });
    });
</script>
@endpush