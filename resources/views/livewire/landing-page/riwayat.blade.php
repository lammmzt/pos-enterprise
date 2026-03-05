<div x-data="{ 
        showDetailModal: false, 
        showReviewModal: false 
     }" @open-detail-modal.window="showDetailModal = true" @close-detail-modal.window="showDetailModal = false"
    @open-review-modal.window="showReviewModal = true" @close-review-modal.window="showReviewModal = false"
    class="relative w-full min-h-screen pb-24 font-sans bg-gray-50 dark:bg-gray-900 selection:bg-brand-red/30">

    {{-- Header --}}
    @include('livewire.landing-page.layout.header', ['active' => $active])

    <main class="container px-4 pt-8 mx-auto">

        <div class="flex flex-wrap justify-center gap-2 mb-8 lg:mb-12">
            @foreach(['all' => 'Semua', 'active' => 'Sedang Berjalan', 'completed' => 'Selesai'] as $key => $label)
            <button wire:click="setTab('{{ $key }}')"
                class="px-6 py-2.5 text-sm font-bold transition-all rounded-full whitespace-nowrap active:scale-95 {{ $activeTab === $key ? 'text-white shadow-lg bg-brand-red shadow-red-500/30' : 'text-gray-500 bg-white border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 hover:border-brand-red/50' }}">
                {{ $label }}    
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
            @forelse($pesanans as $order)
            @php
            // Logika Status Berdasarkan Pembayaran & Pesanan
            $isUnpaid = ($order->status_pembayaran === 'belum_bayar' || $order->status_pembayaran === 'peroses_bayar');
            $isPaid = ($order->status_pembayaran === 'lunas' || $order->status_pembayaran === 'berhasil');
            $delivered = ($order->status_pembayaran === 'lunas' || $order->status_pesanan === 'delivery');

            if ($isUnpaid) {
                $statusClass = 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400';
                $statusIcon = 'fa-wallet';
                $statusLabel = 'Belum Bayar';
            } elseif ($isPaid && $order->status_pesanan === 'proses') {
                $statusClass = 'bg-orange-100 text-brand-orange dark:bg-orange-900/30 dark:text-orange-400';
                $statusIcon = 'fa-fire-burner animate-pulse';
                $statusLabel = 'Sedang Disiapkan';
            } elseif ($order->status_pesanan === 'selesai') {
                $statusClass = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
                $statusIcon = 'fa-check';
                $statusLabel = 'Selesai';
            }elseif ($delivered) {
                $statusClass = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
                $statusIcon = 'fa-truck-fast animate-pulse';
                $statusLabel = 'Proses Pengiriman';
            } 
            else {
                $statusClass = 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400';
                $statusIcon = 'fa-xmark';
                $statusLabel = ucfirst($order->status_pesanan);
            }
            @endphp

            <div wire:click="openDetail({{ $order->id_pesanan }})"
                class="flex flex-col h-full p-5 transition-all bg-white border border-gray-100 shadow-sm cursor-pointer dark:bg-gray-800 rounded-3xl dark:border-gray-700 hover:shadow-xl hover:border-brand-red/20 group animate-fade-in-up">

                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-12 h-12 text-2xl text-gray-400 transition-colors rounded-2xl bg-gray-50 dark:bg-gray-700 group-hover:text-brand-red">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $order->nomor_invoice }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider flex items-center gap-2 {{ $statusClass }}">
                        <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                </div>

                <div class="mb-4 border-t border-gray-100 border-dashed dark:border-gray-700"></div>

                <div class="flex items-center justify-between mt-auto">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $order->mangkuk->count() }}</span>
                         Mangkuk
                    </div>
                    <div class="text-lg font-black text-brand-red">Rp
                        {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                </div>

                <div class="flex gap-2 mt-4">
                    @if($isUnpaid)
                    <a href="{{ route('Payment', ['id' => $order->id_pesanan]) }}" wire:navigate
                        class="flex items-center justify-center flex-1 gap-2 py-3.5 text-sm font-bold transition-all border-2 rounded-xl text-red-500 border-red-400 bg-red-50 hover:bg-red-500 hover:text-white dark:bg-red-900/20 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-600 dark:hover:border-amber-600 dark:hover:text-white active:scale-95 group">
                        <i class="transition-transform fa-solid fa-money-bill-wave group-hover:fa-solid group-hover:scale-110"></i> 
                        <span>Bayar</span>
                    </a>
                    {{-- @elseif($isPaid || $order->status_pesanan === 'selesai')
                    <button wire:click.stop="reOrder({{ $order->id_pesanan }})"
                        class="flex-1 py-2 text-xs font-bold text-white transition-all shadow-md bg-brand-red rounded-xl hover:bg-red-700">
                        Pesan Lagi
                    </button> --}}
                    @endif

                    @if($order->status_pesanan == 'selesai' && !$order->testimoni)
                   <button wire:click.stop="openReview({{ $order->id_pesanan }})"
                        class="flex items-center justify-center flex-1 gap-2 py-3.5 text-sm font-bold transition-all border-2 rounded-xl text-amber-500 border-amber-400 bg-amber-50 hover:bg-amber-500 hover:text-white dark:bg-amber-900/20 dark:border-amber-600 dark:text-amber-400 dark:hover:bg-amber-600 dark:hover:border-amber-600 dark:hover:text-white active:scale-95 group">
                        <i class="transition-transform fa-regular fa-star group-hover:fa-solid group-hover:scale-110"></i> 
                        <span>Beri Ulasan</span>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-20 text-center col-span-full animate-fade-in">
                <div class="flex items-center justify-center w-32 h-32 mb-6 bg-gray-100 rounded-full dark:bg-gray-800">
                    <i class="text-5xl text-gray-300 fa-solid fa-receipt dark:text-gray-600"></i>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Belum ada pesanan</h3>
                <p class="mb-8 text-gray-500 dark:text-gray-400">Dapur kami sudah siap, tinggal tunggu pesananmu!</p>
                <a wire:navigate href="{{ route('Order') }}"
                    class="px-8 py-3 font-bold text-white transition-all shadow-xl bg-brand-red rounded-2xl shadow-red-500/30 active:scale-95">
                    Mulai Belanja
                </a>
            </div>
            @endforelse
        </div>
    </main>

    <div x-show="showDetailModal" class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-0 sm:p-4"
        x-cloak>
        <div x-show="showDetailModal" x-transition.opacity @click="showDetailModal = false"
            class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

        <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-t-[2.5rem] sm:rounded-[2rem] shadow-2xl flex flex-col relative z-10 border-t sm:border border-gray-100 dark:border-gray-800 max-h-[90vh]">

            <div class="flex justify-center w-full pt-4 pb-2 cursor-pointer sm:hidden" @click="showDetailModal = false">
                <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
            </div>

            @if($selectedPesanan)
            <div class="flex items-center justify-between px-8 pt-4 pb-6 border-b border-gray-50 dark:border-gray-800">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-white">Detail Transaksi</h3>
                    <p class="text-sm text-gray-500">{{ $selectedPesanan->nomor_invoice }}</p>
                </div>
                <button @click="showDetailModal = false"
                    class="items-center justify-center hidden w-10 h-10 text-gray-400 transition-colors bg-gray-100 rounded-full sm:flex dark:bg-gray-800 hover:text-brand-red">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex-1 p-8 space-y-6 overflow-y-auto hide-scroll">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                    <span class="text-sm font-medium text-gray-500">Status Pembayaran</span>
                    <span
                        class="font-bold {{ $selectedPesanan->status_pembayaran === 'berhasil' ? 'text-green-500' : 'text-amber-500' }}">
                        {{ strtoupper(str_replace('_', ' ', $selectedPesanan->status_pembayaran)) }}
                    </span>
                </div>

                <div class="space-y-4">
                    <h4 class="text-sm font-bold tracking-widest text-gray-400 uppercase">Pesanan Kamu</h4>
                    @foreach($selectedPesanan->mangkuk as $mangkuk)
                    <div class="p-4 border border-gray-100 rounded-2xl dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="px-2 py-0.5 bg-brand-red text-[10px] text-white rounded-md font-bold italic">Bowl</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $mangkuk->nama_pemesan }}</span>
                        </div>
                        <div class="pl-2 space-y-2 border-l-2 border-gray-100 dark:border-gray-700">
                            @foreach($mangkuk->detailPesanan as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ $item->jumlah }}x
                                    {{ $item->produk->nama }}</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">Rp
                                    {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-6 space-y-3 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex justify-between text-gray-500">
                        <span>Metode Pembayaran</span>
                        <span
                            class="font-bold text-gray-800 uppercase dark:text-gray-200">{{ $selectedPesanan->metode_pembayaran }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-50 dark:border-gray-800">
                        <span class="text-lg font-bold text-gray-800 dark:text-white">Total Bayar</span>
                        <span class="text-2xl font-black text-brand-red">Rp
                            {{ number_format($selectedPesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div
                class="p-6 bg-white dark:bg-gray-900 border-t border-gray-50 dark:border-gray-800 rounded-b-[2rem] flex gap-3">
                @php
                $isUnpaidModal = ($selectedPesanan->status_pembayaran === 'belum_bayar' ||
                $selectedPesanan->status_pembayaran === 'proses_bayar' ||
                $selectedPesanan->status_pesanan === 'menunggu_pembayaran');
                @endphp

                @if($isUnpaidModal)
                <a href="{{ route('Payment', ['id' => $selectedPesanan->id_pesanan]) }}" wire:navigate
                    class="px-4 flex-1 py-4 font-bold text-white transition-all shadow-xl bg-amber-500 rounded-2xl hover:bg-amber-600 active:scale-95">
                    Bayar Sekarang <i class="ml-2 fa-solid fa-arrow-right"></i>
                </a>
                @else
                <button wire:click="reOrder({{ $selectedPesanan->id_pesanan }})"
                    class="flex-1 py-4 font-bold text-white transition-all shadow-xl bg-brand-red rounded-2xl hover:bg-red-700 active:scale-95">
                    Pesan Lagi
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>


    <div x-show="showReviewModal" class="fixed inset-0 z-[70] flex items-center justify-center px-4" x-cloak>
        <div x-show="showReviewModal" x-transition.opacity @click="showReviewModal = false"
            class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>

        <div x-show="showReviewModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative z-10 w-full max-w-sm p-6 text-center bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700">

            <div
                class="flex items-center justify-center mx-auto mb-4 text-2xl text-yellow-500 bg-yellow-100 rounded-full w-14 h-14">
                <i class="fa-solid fa-star"></i>
            </div>
            <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Beri Ulasan</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Bagaimana rasa pesananmu kali ini?</p>

            <div class="flex justify-center gap-2 mb-6">
                @for($i = 1; $i <= 5; $i++) <button wire:click="setRating({{ $i }})"
                    class="text-3xl transition-colors focus:outline-none hover:scale-110 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">
                    <i class="fa-solid fa-star"></i>
                    </button>
                    @endfor
            </div>
            @error('rating') <span class="block mb-2 text-xs text-red-500">{{ $message }}</span> @enderror

            <textarea wire:model="ulasan" placeholder="Tulis masukanmu disini (opsional)..."
                class="w-full p-3 mb-4 text-sm text-gray-800 border border-gray-200 outline-none resize-none bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-xl dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red"
                rows="3"></textarea>

            <button wire:click="submitReview" wire:loading.attr="disabled"
                class="relative flex items-center justify-center w-full py-3 mb-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl active:scale-95 disabled:opacity-50">
                <span wire:loading.remove wire:target="submitReview">Kirim Ulasan</span>
                <span wire:loading wire:target="submitReview"><i class="fa-solid fa-spinner fa-spin"></i>
                    Mengirim...</span>
            </button>
            <button @click="showReviewModal = false"
                class="text-sm text-gray-400 transition-colors hover:text-brand-red">
                Batal
            </button>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }

    </style>
</div>
