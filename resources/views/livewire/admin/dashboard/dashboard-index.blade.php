<div class="w-full max-w-full pb-20 space-y-6 overflow-x-hidden sm:space-y-8" wire:poll.30s>
    
    {{-- Header & Filter Section --}}
    <div class="flex flex-col w-full gap-4 md:flex-row md:items-end md:justify-between">
        <div class="w-full min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 truncate sm:text-3xl dark:text-white">{{ $title }}</h1>
            <p class="text-xs font-medium text-gray-500 truncate sm:text-sm dark:text-gray-400">{{ $desc_page }}</p>
        </div>
        
        <div class="relative flex flex-col items-center w-full gap-2 p-2 transition-all bg-white border border-gray-200 shadow-sm sm:flex-row sm:gap-3 dark:bg-gray-900 dark:border-gray-800 rounded-xl sm:rounded-2xl md:w-auto shrink-0">
            {{-- Loading Indicator saat filter diubah --}}
            <div wire:loading wire:target="tanggalMulai, tanggalAkhir" class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm rounded-xl sm:rounded-2xl">
                <i class="text-xl text-indigo-600 animate-spin ti ti-loader-2"></i>
            </div>

            <div class="flex flex-col w-full px-2 transition-colors sm:w-auto group hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-xl">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Dari Tanggal</span>
                <input type="date" wire:model.live="tanggalMulai" class="w-full p-0 py-1 text-xs font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer sm:text-sm dark:text-gray-200 focus:ring-0">
            </div>
            <div class="hidden w-px h-8 bg-gray-200 sm:block dark:bg-gray-700"></div>
            <div class="w-full h-px my-1 bg-gray-100 sm:hidden dark:bg-gray-800"></div>
            <div class="flex flex-col w-full px-2 transition-colors sm:w-auto group hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-xl">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sampai Tanggal</span>
                <input type="date" wire:model.live="tanggalAkhir" class="w-full p-0 py-1 text-xs font-bold text-gray-700 bg-transparent border-none outline-none cursor-pointer sm:text-sm dark:text-gray-200 focus:ring-0">
            </div>
        </div>
    </div>

    {{-- Wrapper Loading State untuk Seluruh Konten Bawah --}}
    <div wire:loading.class="opacity-50 blur-[2px] pointer-events-none" class="space-y-6 transition-all duration-300 sm:space-y-8">
        
        {{-- Top Metrics Grid --}}
        <div class="grid w-full grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-4">
            
            {{-- Card Pendapatan --}}
            <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden text-white transition-all duration-500 bg-indigo-600 border border-indigo-700 shadow-lg sm:p-6 rounded-2xl sm:rounded-3xl group hover:-translate-y-1 hover:shadow-indigo-500/30">
                <div class="absolute transition-transform duration-700 text-indigo-500/50 -right-4 -bottom-4 group-hover:scale-125 group-hover:-rotate-12"><i class="text-8xl sm:text-9xl ti ti-wallet"></i></div>
                <div class="relative z-10">
                    <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-indigo-200 uppercase truncate">Pendapatan Terpilih</p>
                    <h3 class="mb-1 text-xl font-black truncate sm:text-2xl">Rp {{ number_format($pendapatanPeriode, 0, ',', '.') }}</h3>
                    <p class="text-[9px] sm:text-[10px] text-white font-bold bg-indigo-500/50 px-2 py-1 rounded-md inline-block mt-1 shadow-sm">Berdasarkan Filter</p>
                </div>
            </div>

            {{-- Card Transaksi --}}
            <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all duration-500 bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group hover:-translate-y-1 hover:shadow-md">
                <div class="absolute transition-transform duration-700 text-emerald-50 -right-6 -top-6 dark:text-emerald-900/20 group-hover:scale-125"><i class="text-8xl sm:text-9xl ti ti-receipt"></i></div>
                <div class="relative z-10">
                    <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-gray-500 uppercase truncate">Total Transaksi</p>
                    <h3 class="mb-1 text-2xl font-black text-gray-900 truncate sm:text-3xl dark:text-white">{{ $transaksiPeriode }} <span class="text-sm font-semibold text-gray-400">Struk</span></h3>
                </div>
            </div>

            {{-- Card Antrean Dapur --}}
            <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all duration-500 bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group hover:-translate-y-1 hover:border-orange-300 dark:hover:border-orange-800/50">
                <div class="absolute transition-transform duration-700 text-orange-50 -right-6 -top-6 dark:text-orange-900/20 group-hover:scale-125"><i class="text-8xl sm:text-9xl ti ti-soup"></i></div>
                <div class="relative z-10">
                    <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-orange-500 uppercase truncate">Antrean Dapur</p>
                    <h3 class="mb-1 text-2xl font-black text-orange-600 truncate sm:text-3xl">{{ $antreanDapur }}</h3>
                    <div class="flex inline-flex items-center gap-2 px-2 py-1 mt-1 rounded-md bg-orange-50 dark:bg-orange-900/20">
                        <span class="flex w-2 h-2 bg-orange-500 rounded-full animate-ping shrink-0"></span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-orange-600 dark:text-orange-400">Real-time</span>
                    </div>
                </div>
            </div>

            {{-- Card Menunggu Pembayaran --}}
            <div class="relative flex flex-col justify-between w-full min-w-0 p-5 overflow-hidden transition-all duration-500 bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 group hover:-translate-y-1 hover:border-red-300 dark:hover:border-red-800/50">
                <div class="absolute transition-transform duration-700 text-red-50 -right-6 -top-6 dark:text-red-900/20 group-hover:scale-125"><i class="text-8xl sm:text-9xl ti ti-cash-off"></i></div>
                <div class="relative z-10">
                    <p class="mb-2 text-[10px] sm:text-xs font-bold tracking-widest text-red-500 uppercase truncate">Menunggu Bayaran</p>
                    <h3 class="mb-1 text-2xl font-black text-red-600 truncate sm:text-3xl">{{ $menungguPembayaran }}</h3>
                    <div class="flex inline-flex items-center gap-2 px-2 py-1 mt-1 rounded-md bg-red-50 dark:bg-red-900/20">
                        <span class="flex w-2 h-2 bg-red-500 rounded-full animate-ping shrink-0"></span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-red-600 dark:text-red-400">Real-time</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Chart --}}
        <div class="relative w-full min-w-0 p-4 overflow-hidden transition-all bg-white border border-gray-200 shadow-sm sm:p-6 dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 hover:shadow-md" wire:ignore>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 text-indigo-600 rounded-lg bg-indigo-50 dark:bg-indigo-900/30">
                        <i class="text-lg ti ti-chart-area-line"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 truncate sm:text-lg dark:text-white">Tren Pendapatan</h3>
                </div>
                <span class="text-[9px] sm:text-[10px] px-2.5 py-1 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 rounded-md font-bold uppercase tracking-widest shrink-0 border border-indigo-100 dark:border-indigo-800">Interaktif</span>
            </div>
            
            {{-- FIX: Bungkus grafik dengan relative dan paksa div chart menjadi absolute inset-0 agar tidak bisa melebihi card --}}
            <div x-data="revenueChart()" x-init="initChart()" class="relative w-full h-64 overflow-hidden sm:h-72">
                <div id="chart-area" class="absolute inset-0 w-full h-full"></div>
            </div>
        </div>

        {{-- Grid Bawah: Transaksi & Alerts --}}
        <div class="grid w-full grid-cols-1 gap-6 lg:grid-cols-3">
            
            {{-- Kiri: Transaksi Terkini --}}
            <div class="w-full min-w-0 space-y-4 lg:col-span-2">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-base font-bold text-gray-900 truncate sm:text-lg dark:text-white">Transaksi Terkini</h3>
                    <a href="{{ route('admin.riwayat-pesanan') }}" wire:navigate class="text-[10px] sm:text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-full transition-colors shrink-0">Lihat Semua <i class="ml-1 ti ti-arrow-right"></i></a>
                </div>
                
                <div class="w-full overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-2xl sm:rounded-3xl dark:border-gray-800 hover:shadow-md">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse sm:text-sm whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50/80 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 text-gray-400 text-[10px] sm:text-xs uppercase tracking-wider">
                                    <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Masa & Inv</th>
                                    <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Pelanggan / Tipe</th>
                                    <th class="px-4 py-3 font-bold sm:px-5 sm:py-4">Status</th>
                                    <th class="px-4 py-3 font-bold text-right sm:px-5 sm:py-4">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono divide-y divide-gray-50 dark:divide-gray-800/60">
                                @forelse($transaksiTerkini as $trx)
                                    <tr class="transition-colors cursor-default hover:bg-gray-50 dark:hover:bg-gray-800/40 group">
                                        <td class="px-4 py-3 sm:px-5 sm:py-4">
                                            <div class="font-bold text-indigo-600 transition-colors dark:text-indigo-400 group-hover:text-indigo-700">{{ $trx->nomor_invoice }}</div>
                                            <div class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5 flex items-center gap-1">
                                                <i class="ti ti-clock"></i> {{ $trx->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 sm:px-5 sm:py-4">
                                            <div class="font-bold text-gray-900 dark:text-white">
                                                {{ $trx->pelanggan->nama ?? 'Walk-in Customer' }}
                                            </div>
                                            <div class="text-[9px] sm:text-[10px] px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-md inline-block text-gray-500 uppercase mt-1 font-sans font-bold">
                                                {{ $trx->tipe_pesanan }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 sm:px-5 sm:py-4">
                                            @if($trx->status_pesanan === 'selesai')
                                                <span class="px-2.5 py-1 text-[9px] sm:text-[10px] font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-lg dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400">Selesai</span>
                                            @elseif($trx->status_pesanan === 'proses')
                                                <span class="px-2.5 py-1 text-[9px] sm:text-[10px] font-bold text-orange-700 bg-orange-100 border border-orange-200 rounded-lg dark:bg-orange-500/10 dark:border-orange-500/20 dark:text-orange-400 flex inline-flex items-center gap-1">
                                                    <i class="ti ti-loader animate-spin"></i> Dapur
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 text-[9px] sm:text-[10px] font-bold text-red-700 bg-red-100 border border-red-200 rounded-lg dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">Batal</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-right text-gray-900 sm:px-5 sm:py-4 dark:text-white">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="mb-2 text-4xl opacity-50 ti ti-receipt-off"></i>
                                                <p class="font-sans text-sm font-medium">Tiada transaksi direkodkan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kanan: Stack Alerts (Kedaluwarsa & Stok Menipis) --}}
            <div class="w-full min-w-0 space-y-6">
                
                {{-- Panel 1: Stok Menipis (BARU) --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1 text-amber-600 dark:text-amber-500">
                        <div class="flex items-center gap-2">
                            <i class="text-lg ti ti-archive sm:text-xl animate-bounce"></i>
                            <h3 class="text-sm font-bold text-gray-900 truncate dark:text-white">Peringatan Stok Menipis</h3>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden transition-all bg-white border divide-y shadow-sm border-amber-200 divide-amber-100/50 dark:bg-gray-900 rounded-2xl dark:border-amber-900/30 dark:divide-gray-800 hover:shadow-md">
                        @forelse($stokTipis ?? [] as $stok) {{-- Ganti $stokMenipis sesuai variabel backend Anda --}}
                            <div class="flex items-center justify-between p-3 transition-colors cursor-pointer sm:p-4 hover:bg-amber-50/50 dark:hover:bg-amber-900/10 group">
                                <div class="flex items-center flex-1 min-w-0 gap-3 pr-2">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 shrink-0">
                                        <i class="text-lg ti ti-box"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-[11px] sm:text-xs font-bold text-gray-900 dark:text-white truncate group-hover:text-amber-600 transition-colors">{{ $stok->nama ?? 'Nama Produk' }}</h4>
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5 truncate">Sisa Stok: <strong class="text-xs text-amber-600">{{ $stok->stok ?? 0 }}</strong></p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.produk') }}" wire:navigate class="flex items-center justify-center text-gray-400 transition-all bg-white border border-gray-200 rounded-lg shadow-sm w-7 h-7 hover:bg-amber-500 hover:text-white hover:border-amber-500 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-amber-600 shrink-0">
                                    <i class="text-xs ti ti-plus"></i>
                                </a>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center p-6 text-xs font-medium text-center text-gray-400">
                                <i class="mb-2 text-3xl ti ti-check text-emerald-500"></i>
                                Semua stok produk aman.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel 2: Amaran Kedaluwarsa --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1 text-red-500">
                        <div class="flex items-center gap-2">
                            <i class="text-lg ti ti-alert-triangle sm:text-xl"></i>
                            <h3 class="text-sm font-bold text-gray-900 truncate dark:text-white">Amaran Kedaluwarsa</h3>
                        </div>
                        <span class="text-[9px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-md dark:bg-red-900/30">< 7 Hari</span>
                    </div>
                    
                    <div class="overflow-hidden transition-all bg-white border border-red-200 divide-y shadow-sm divide-red-100/50 dark:bg-gray-900 rounded-2xl dark:border-red-900/30 dark:divide-gray-800 hover:shadow-md">
                        @forelse($hampirKedaluwarsa as $batch)
                            @php
                                $isExpired = \Carbon\Carbon::parse($batch->tanggal_kedaluwarsa)->isPast();
                            @endphp
                            <div class="flex items-center justify-between p-3 transition-colors sm:p-4 hover:bg-red-50/50 dark:hover:bg-red-900/10">
                                <div class="flex-1 min-w-0 pr-2">
                                    <h4 class="text-[11px] sm:text-xs font-bold text-gray-900 dark:text-white truncate">{{ $batch->produk->nama ?? 'Produk Dibuang' }}</h4>
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5 truncate">Batch #{{ $batch->id_batch }} | Stok: <strong class="text-gray-700 dark:text-gray-300">{{ $batch->jumlah }}</strong></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[10px] sm:text-xs font-bold px-2 py-1 rounded-lg {{ $isExpired ? 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400' }}">
                                        {{ \Carbon\Carbon::parse($batch->tanggal_kedaluwarsa)->format('d M y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center p-6 text-xs font-medium text-center text-gray-400">
                                <i class="mb-2 text-3xl ti ti-shield-check text-emerald-500"></i>
                                Tiada bahan luput terdekat.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Memuatkan Library ApexCharts --}}{{-- Memuatkan Library ApexCharts --}}
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
                        parentHeightOffset: 0,
                        redrawOnParentResize: true // FIX: Pastikan chart menyesuaikan diri saat ukuran parent/layar berubah
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
                        labels: { 
                            style: { colors: '#9ca3af', fontSize: '10px', fontWeight: 600 },
                            hideOverlappingLabels: true // FIX: Sembunyikan label yang menumpuk di layar kecil
                        }
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
                    grid: { borderColor: '#f3f4f6', strokeDashArray: 4, padding: { left: 10, right: 10, bottom: 0 } },
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

    document.addEventListener('livewire:navigated', () => {
        Alpine.store('revenueChart').initChart();
    });
</script>
@endpush