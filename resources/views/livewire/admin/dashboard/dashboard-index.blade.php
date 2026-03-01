<div class="w-full max-w-full pb-20 space-y-6 overflow-x-hidden sm:space-y-8" wire:poll.30s>
    
    <div class="flex flex-col w-full gap-4 md:flex-row md:items-end md:justify-between">
        <div class="w-full min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 truncate sm:text-3xl dark:text-white">{{ $title }}</h1>
            <p class="text-xs font-medium text-gray-500 truncate sm:text-sm dark:text-gray-400">{{ $desc_page }}</p>
        </div>
        
        <div class="flex flex-col items-center w-full gap-2 p-2 transition-all bg-white border border-gray-200 shadow-sm sm:flex-row sm:gap-3 dark:bg-gray-900 dark:border-gray-800 rounded-xl sm:rounded-2xl md:w-auto shrink-0">
            <div class="flex flex-col w-full px-2 sm:w-auto">
                <span class="text-[10px] font-bold text-gray-400 uppercase">Dari Tanggal</span>
                <input type="date" wire:model.live="tanggalMulai" class="w-full p-0 text-xs font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer sm:text-sm dark:text-gray-200 focus:ring-0">
            </div>
            <div class="hidden w-px h-8 bg-gray-200 sm:block dark:bg-gray-700"></div>
            <div class="w-full h-px my-1 bg-gray-100 sm:hidden dark:bg-gray-800"></div>
            <div class="flex flex-col w-full px-2 sm:w-auto">
                <span class="text-[10px] font-bold text-gray-400 uppercase">Sampai Tanggal</span>
                <input type="date" wire:model.live="tanggalAkhir" class="w-full p-0 text-xs font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer sm:text-sm dark:text-gray-200 focus:ring-0">
            </div>
        </div>
    </div>

    <div class="grid w-full grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden text-white transition-all bg-indigo-600 border border-indigo-700 shadow-md sm:p-6 rounded-2xl sm:rounded-3xl group">
            <div class="absolute transition-transform text-indigo-500/50 -right-4 -bottom-4 group-hover:scale-110"><i class="text-8xl sm:text-9xl ti ti-wallet"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-indigo-200 uppercase truncate">Pendapatan Terpilih</p>
                <h3 class="mb-1 text-xl font-black truncate sm:text-2xl">Rp {{ number_format($pendapatanPeriode, 0, ',', '.') }}</h3>
                <p class="text-[9px] sm:text-[10px] text-white font-bold bg-indigo-500/50 px-2 py-1 rounded-md inline-block mt-1">Berdasarkan Filter</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-emerald-50 -right-6 -top-6 dark:text-emerald-900/20 group-hover:scale-110"><i class="text-8xl sm:text-9xl ti ti-receipt"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-gray-500 uppercase truncate">Total Transaksi</p>
                <h3 class="mb-1 text-2xl font-black text-gray-900 truncate sm:text-3xl dark:text-white">{{ $transaksiPeriode }}</h3>
                <p class="text-[9px] sm:text-[10px] text-gray-400 font-medium mt-1">Berdasarkan Filter</p>
            </div>
        </div>

        <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-orange-50 -right-6 -top-6 dark:text-orange-900/20 group-hover:scale-110"><i class="text-8xl sm:text-9xl ti ti-soup"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-orange-500 uppercase truncate">Antrean Dapur</p>
                <h3 class="mb-1 text-2xl font-black text-orange-600 truncate sm:text-3xl">{{ $antreanDapur }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="flex w-2 h-2 bg-orange-500 rounded-full animate-ping shrink-0"></span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-orange-500">Real-time</span>
                </div>
            </div>
        </div>

        <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group">
            <div class="absolute transition-transform text-red-50 -right-6 -top-6 dark:text-red-900/20 group-hover:scale-110"><i class="text-8xl sm:text-9xl ti ti-cash-off"></i></div>
            <div class="relative z-10">
                <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-red-500 uppercase truncate">Menunggu Bayaran</p>
                <h3 class="mb-1 text-2xl font-black text-red-600 truncate sm:text-3xl">{{ $menungguPembayaran }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="flex w-2 h-2 bg-red-500 rounded-full animate-ping shrink-0"></span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-red-500">Real-time</span>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full min-w-0 p-4 bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800" wire:ignore>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900 truncate sm:text-lg dark:text-white">Tren Pendapatan</h3>
            <span class="text-[9px] sm:text-[10px] px-2 py-1 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 rounded-md font-bold uppercase tracking-widest shrink-0">Interaktif</span>
        </div>
        
        <div x-data="revenueChart()" x-init="initChart()" class="h-64 max-w-md sm:h-72 ma">
            <div id="chart-area" class="w-full"></div>
        </div>
    </div>

    <div class="grid w-full grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="w-full min-w-0 space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 truncate sm:text-lg dark:text-white">Transaksi Terkini</h3>
                <a href="{{ route('admin.riwayat-pesanan') }}" wire:navigate class="text-[10px] sm:text-xs font-bold text-indigo-600 hover:text-indigo-700 shrink-0">Lihat Semua</a>
            </div>
            
            <div class="w-full overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse sm:text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 text-gray-400 text-[10px] sm:text-xs uppercase">
                                <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Masa & Inv</th>
                                <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Pelanggan</th>
                                <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Status</th>
                                <th class="px-4 py-3 font-bold text-right sm:px-5 sm:py-4">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($transaksiTerkini as $trx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                    <td class="px-4 py-2 sm:px-5 sm:py-3">
                                        <div class="font-bold text-indigo-600 dark:text-indigo-400">{{ $trx->nomor_invoice }}</div>
                                        <div class="text-[9px] sm:text-[10px] text-gray-500">{{ $trx->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-2 font-medium text-gray-900 sm:px-5 sm:py-3 dark:text-white">
                                        {{ $trx->user->nama ?? 'Walk-in' }}
                                        <div class="text-[8px] sm:text-[9px] text-gray-400 uppercase mt-0.5">{{ $trx->tipe_pesanan }}</div>
                                    </td>
                                    <td class="px-4 py-2 sm:px-5 sm:py-3">
                                        @if($trx->status_pesanan === 'selesai')
                                            <span class="px-2 py-1 text-[9px] sm:text-[10px] font-bold text-emerald-600 bg-emerald-50 rounded-md dark:bg-emerald-500/10">Selesai</span>
                                        @elseif($trx->status_pesanan === 'proses')
                                            <span class="px-2 py-1 text-[9px] sm:text-[10px] font-bold text-orange-600 bg-orange-50 rounded-md dark:bg-orange-500/10 animate-pulse">Dapur</span>
                                        @else
                                            <span class="px-2 py-1 text-[9px] sm:text-[10px] font-bold text-red-600 bg-red-50 rounded-md dark:bg-red-500/10">Batal</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 font-bold text-right text-gray-900 sm:px-5 sm:py-3 dark:text-white">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Tiada transaksi direkodkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="w-full min-w-0 space-y-6">
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-red-500">
                    <i class="text-lg ti ti-alert-triangle sm:text-xl"></i>
                    <h3 class="text-sm font-bold text-gray-900 truncate dark:text-white">Amaran Kedaluwarsa <span class="text-[10px] text-gray-400 font-normal">(< 7 Hari)</span></h3>
                </div>
                
                <div class="overflow-hidden bg-white border border-red-100 divide-y divide-gray-100 shadow-sm dark:bg-gray-900 rounded-2xl dark:border-red-900/30 dark:divide-gray-800">
                    @forelse($hampirKedaluwarsa as $batch)
                        @php
                            $isExpired = \Carbon\Carbon::parse($batch->tanggal_kedaluwarsa)->isPast();
                        @endphp
                        <div class="flex items-center justify-between p-3 transition-colors sm:p-4 hover:bg-red-50/50 dark:hover:bg-red-900/10">
                            <div class="flex-1 min-w-0 pr-2">
                                <h4 class="text-[11px] sm:text-xs font-bold text-gray-900 dark:text-white truncate">{{ $batch->produk->nama ?? 'Produk Dibuang' }}</h4>
                                <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5 truncate">Batch #{{ $batch->id_batch }} | Stok: {{ $batch->jumlah }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] sm:text-xs font-bold {{ $isExpired ? 'text-red-600' : 'text-orange-500' }}">
                                    {{ \Carbon\Carbon::parse($batch->tanggal_kedaluwarsa)->format('d M y') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-xs font-medium text-center text-gray-400">Tiada produk hampir luput.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Memuatkan Library ApexCharts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('revenueChart', () => ({
            chart: null,
            initChart() {
                let options = {
                    series: [{
                        name: 'Pendapatan (Rp)',
                        data: @json($chartTotals)
                    }],
                    chart: {
                        type: 'area',
                        height: '100%',
                        width: '100%',
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        animations: { enabled: true, easing: 'easeinout', speed: 800 },
                        parentHeightOffset: 0
                    },
                    colors: ['#4f46e5'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    xaxis: {
                        categories: @json($chartDates),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 },
                            formatter: (value) => { 
                                if(value >= 1000000) return "Rp " + (value/1000000).toFixed(1) + "M";
                                if(value >= 1000) return "Rp " + (value/1000).toFixed(0) + "K";
                                return "Rp " + value; 
                            }
                        }
                    },
                    grid: { borderColor: '#f3f4f6', strokeDashArray: 4, padding: { left: 10, right: 10 } },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                        y: { formatter: function (val) { return "Rp " + new Intl.NumberFormat('id-ID').format(val) } }
                    }
                };

                this.chart = new ApexCharts(document.getElementById('chart-area'), options);
                this.chart.render();

                Livewire.on('update-chart', (event) => {
                    let data = event[0].chartData;
                    this.chart.updateOptions({ xaxis: { categories: data.dates } });
                    this.chart.updateSeries([{ data: data.totals }]);
                });
            }
        }));
    });
</script>
@endpush