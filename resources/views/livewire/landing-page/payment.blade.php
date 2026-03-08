@php
    // PERBAIKAN LOGIKA WAKTU (ANTI-BUG REFRESH)
    $waktuDibuat = \Carbon\Carbon::parse($pesanan->created_at);
    $waktuKedaluwarsa = $waktuDibuat->copy()->addMinutes(20);
    $sisaDetik = now()->diffInSeconds($waktuKedaluwarsa, false);
    
    $sisaWaktu = $sisaDetik > 0 ? (int) $sisaDetik : 0;
    if ($sisaWaktu > 1200) {
        $sisaWaktu = 1200;
    }

    // CEK STATUS LOCK DARI BACKEND
    // Jika status bukan belum_bayar (misal: proses_bayar, pending), kunci form sejak awal
    $isLockedBackend = $pesanan->status_pembayaran !== 'belum_bayar';
@endphp

<div x-data="paymentTimer({{ $sisaWaktu }}, {{ $isLockedBackend ? 'true' : 'false' }})" 
     @open-payment-modal.window="showPaymentModal = true; isLocked = true" 
     @close-payment-modal.window="closeModal()"
     @open-qrcode-modal.window="showQrcodePayment = true; isLocked = true" 
     @close-qrcode-modal.window="closeModal()"
     class="relative min-h-screen pb-24 font-sans bg-gray-50 dark:bg-gray-900 selection:bg-brand-red/30">
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}" data-navigate-track></script>

    @include('livewire.landing-page.layout.header', ['active' => $active])

    <div class="px-4 pt-6 mx-auto max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            
            <div class="space-y-6 lg:col-span-7">
                <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-4 text-base font-bold text-gray-800 dark:text-white">Tipe Pesanan</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <label :class="isLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700'" class="relative flex flex-col items-center p-3 text-center transition-all border-2 rounded-xl {{ $tipe_pesanan === 'dinein' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="dinein" class="hidden" :disabled="isLocked">
                            <i class="mb-2 text-2xl fa-solid fa-utensils {{ $tipe_pesanan === 'dinein' ? 'text-brand-red' : 'text-gray-400' }}"></i>
                            <span class="text-xs font-bold {{ $tipe_pesanan === 'dinein' ? 'text-brand-red' : 'text-gray-500' }}">Makan Sini</span>
                        </label>
                        <label :class="isLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700'" class="relative flex flex-col items-center p-3 text-center transition-all border-2 rounded-xl {{ $tipe_pesanan === 'takeaway' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="takeaway" class="hidden" :disabled="isLocked">
                            <i class="mb-2 text-2xl fa-solid fa-bag-shopping {{ $tipe_pesanan === 'takeaway' ? 'text-brand-red' : 'text-gray-400' }}"></i>
                            <span class="text-xs font-bold {{ $tipe_pesanan === 'takeaway' ? 'text-brand-red' : 'text-gray-500' }}">Bawa Pulang</span>
                        </label>
                        <label :class="isLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700'" class="relative flex flex-col items-center p-3 text-center transition-all border-2 rounded-xl {{ $tipe_pesanan === 'delivery' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="delivery" class="hidden" :disabled="isLocked">
                            <i class="mb-2 text-2xl fa-solid fa-motorcycle {{ $tipe_pesanan === 'delivery' ? 'text-brand-red' : 'text-gray-400' }}"></i>
                            <span class="text-xs font-bold {{ $tipe_pesanan === 'delivery' ? 'text-brand-red' : 'text-gray-500' }}">Delivery</span>
                        </label>
                    </div>
                </div>

                @if($tipe_pesanan === 'delivery')
                    <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700 animate-fade-in-up">
                        <div class="flex items-center gap-2 mb-4 text-brand-orange">
                            <i class="text-xl fa-solid fa-motorcycle"></i>
                            <h2 class="text-base font-bold text-gray-800 dark:text-white">Informasi Pengiriman</h2>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">No WhatsApp (Penerima)</label>
                                <input type="number" wire:model="nomor_hp" :disabled="isLocked" :class="isLocked ? 'bg-gray-200 dark:bg-gray-800 opacity-60 cursor-not-allowed' : 'bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-brand-orange'" class="w-full p-3 text-sm border @error('nomor_hp') border-red-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl outline-none dark:text-white transition-all">
                                @error('nomor_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Alamat Lengkap</label>
                                <textarea wire:model="alamat" rows="2" :disabled="isLocked" :class="isLocked ? 'bg-gray-200 dark:bg-gray-800 opacity-60 cursor-not-allowed' : 'bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-brand-orange'" class="w-full p-3 text-sm border @error('alamat') border-red-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl outline-none dark:text-white transition-all" placeholder="Nama Jalan, RT/RW, Patokan..."></textarea>
                                @error('alamat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Catatan Tambahan (Opsional)</label>
                                <input type="text" wire:model="catatan" :disabled="isLocked" :class="isLocked ? 'bg-gray-200 dark:bg-gray-800 opacity-60 cursor-not-allowed' : 'bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-brand-orange'" class="w-full p-3 text-sm border border-gray-200 outline-none dark:border-gray-700 rounded-xl dark:text-white transition-all" placeholder="Cth: Pagar warna hitam">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-4 text-base font-bold text-gray-800 dark:text-white">Ringkasan Pesanan</h2>
                    <div class="space-y-4">
                        @foreach($pesanan->mangkuk as $idx => $mangkuk)
                            <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                                <div class="flex items-center gap-2 pb-2 mb-2 border-b border-gray-200 border-dashed dark:border-gray-700">
                                    <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-brand-red">{{ $idx + 1 }}</div>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">{{ $mangkuk->nama_pemesan }}</h3>
                                        <p class="text-[10px] text-gray-500">{{ $mangkuk->tipe_kuah }} | Lvl {{ $mangkuk->level_pedas }}</p>
                                    </div>
                                </div>
                                
                                <ul class="space-y-1">
                                    @foreach($mangkuk->detailPesanan as $item)
                                        <li class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                            <span>{{ $item->jumlah }}x {{ $item->produk->nama }}</span>
                                            <span class="font-medium text-gray-800 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                @if(!empty($mangkuk->catatan))
                                    <div class="mt-3 p-2.5 text-xs text-brand-orange bg-orange-50/50 border border-brand-orange/20 rounded-lg dark:bg-orange-900/10 dark:text-orange-300 dark:border-orange-800/30">
                                        <i class="mr-1 fa-solid fa-note-sticky"></i>
                                        <span class="font-bold">Catatan:</span> {{ $mangkuk->catatan }}
                                    </div>
                                @endif
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-[80px] p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700 space-y-6">
                    
                    <div class="flex items-center justify-between p-4 border border-red-100 bg-red-50 dark:bg-red-900/20 rounded-xl dark:border-red-800/50">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-clock text-brand-red animate-pulse"></i>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Sisa Waktu Pembayaran</span>
                        </div>
                        <span class="text-xl font-black tracking-widest text-brand-red tabular-nums" x-text="formatTime(timeLeft)"></span>
                    </div>

                    <div>
                        <h2 class="mb-3 text-base font-bold text-gray-800 dark:text-white">Metode Pembayaran</h2>
                        <div class="space-y-3">
                            <label :class="isLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50'" class="flex items-center justify-between p-4 transition-colors border-2 rounded-xl {{ $metode_pembayaran === 'qris' ? 'border-brand-red bg-red-50/30 dark:bg-red-900/10' : 'border-gray-100 dark:border-gray-700' }}">
                                <div class="flex items-center gap-3">
                                    <div class="text-2xl text-blue-600"><i class="fa-solid fa-qrcode"></i></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800 dark:text-white">QRIS / E-Wallet</h4>
                                        <p class="text-[10px] text-gray-500">Gopay, ShopeePay, Dana, M-Banking</p>
                                    </div>
                                </div>
                                <input type="radio" wire:model.live="metode_pembayaran" value="qris" class="hidden" :disabled="isLocked">
                                @if($metode_pembayaran === 'qris') <i class="fa-solid fa-circle-check text-brand-red"></i> @endif
                            </label>

                            @if($isKasir)
                                <label :class="isLocked ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50'" class="flex items-center justify-between p-4 transition-colors border-2 rounded-xl {{ $metode_pembayaran === 'tunai' ? 'border-brand-red bg-red-50/30 dark:bg-red-900/10' : 'border-gray-100 dark:border-gray-700' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="text-2xl text-emerald-500"><i class="fa-solid fa-money-bill-wave"></i></div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 dark:text-white">Tunai (Kasir)</h4>
                                            <p class="text-[10px] text-gray-500">Bayar langsung di tempat</p>
                                        </div>
                                    </div>
                                    <input type="radio" wire:model.live="metode_pembayaran" value="tunai" class="hidden" :disabled="isLocked">
                                    @if($metode_pembayaran === 'tunai') <i class="fa-solid fa-circle-check text-brand-red"></i> @endif
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Total Pembayaran</span>
                            <span class="text-2xl font-black text-brand-red">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <button wire:click="processPayment" wire:loading.attr="disabled" :disabled="timeLeft <= 0" class="relative flex items-center justify-center w-full py-4 font-bold text-white transition-all shadow-lg bg-brand-red rounded-xl hover:bg-red-700 active:scale-95 disabled:opacity-50 disabled:bg-gray-400">
                            <span wire:loading.remove wire:target="processPayment" x-text="timeLeft > 0 ? (isLocked ? 'Lihat Pembayaran' : 'Bayar Sekarang') : 'Waktu Habis'"></span>
                            <span wire:loading wire:target="processPayment"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showPaymentModal" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
        <div x-show="showPaymentModal" x-transition.opacity @click="closeModal()" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
        <div x-show="showPaymentModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl flex flex-col overflow-hidden max-h-[85vh] sm:max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <i class="text-xl fa-solid fa-shield-halved text-brand-red"></i>
                    <h3 class="font-bold text-gray-900 dark:text-white">Pembayaran Aman</h3>
                </div>
                <button @click="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors bg-gray-100 rounded-full dark:bg-gray-800 hover:text-red-500">
                    <i class="text-lg fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="snap-wrapper" class="flex-1 w-full overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="flex flex-col items-center justify-center h-full min-h-[450px] gap-3 text-gray-400">
                    <i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showQrcodePayment" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
        <div x-show="showQrcodePayment" x-transition.opacity @click="closeModal()" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
        <div x-show="showQrcodePayment" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl flex flex-col overflow-hidden max-h-[85vh] sm:max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <i class="text-xl fa-solid fa-money-bill-wave text-brand-red"></i>
                    <h3 class="font-bold text-gray-900 dark:text-white">Detail Pembayaran Tunai</h3>
                </div>
                <button @click="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors bg-gray-100 rounded-full dark:bg-gray-800 hover:text-red-500">
                    <i class="text-lg fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="qrcode-wrapper" class="flex-1 w-full overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="flex flex-col items-center justify-center h-full min-h-[450px] gap-3 text-gray-400">
                    <i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const initPaymentTimer = () => {
        Alpine.data('paymentTimer', (initialSeconds, initialLockState) => ({
            showPaymentModal: false,
            showQrcodePayment: false,
            isLocked: initialLockState,
            timeLeft: parseInt(initialSeconds, 10), 
            timerInterval: null,
            
            init() {
                if(this.timeLeft > 0) {
                    this.startTimer();
                } else {
                    this.cancelOrder();
                }
            },
            
            startTimer() {
                this.timerInterval = setInterval(() => {
                    if(this.timeLeft > 0) {
                        this.timeLeft--;
                    } else {
                        clearInterval(this.timerInterval);
                        this.cancelOrder();
                    }
                }, 1000);
            },
            
            formatTime(seconds) {
                if (seconds <= 0) return "00 : 00";
                let m = Math.floor(seconds / 60).toString().padStart(2, '0');
                let s = Math.floor(seconds % 60).toString().padStart(2, '0');
                return m + ' : ' + s;
            },
            
            cancelOrder() {
                if (this.$wire) this.$wire.cancelExpiredOrder();
            },

            closeModal() {
                this.showPaymentModal = false;
                this.showQrcodePayment = false;
                
                // Tutup Midtrans jika masih terbuka
                if (window.snap && typeof window.snap.hide === 'function') {
                    window.snap.hide();
                }

                // PERBAIKAN: PAKSA CEK STATUS KETIKA MODAL DITUTUP DARI UI
                if (this.$wire) {
                    this.$wire.checkPaymentStatus();
                } else {
                    Livewire.dispatch('check-payment-status');
                }

                // Kembalikan tampilan kontainer loading
                setTimeout(() => {
                    let wrapper = document.getElementById('snap-wrapper');
                    if(wrapper) wrapper.innerHTML = `<div id="snap-container" class="flex flex-col items-center justify-center w-full h-full min-h-[450px] gap-3 text-gray-400"><i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i></div>`;
                    
                    let wrappe2 = document.getElementById('qrcode-wrapper');
                    if(wrappe2) wrappe2.innerHTML = `<div id="qrcode-container" class="flex flex-col items-center justify-center w-full h-full min-h-[450px] gap-3 text-gray-400"><i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i></div>`;
                }, 300);
            }
        }));
    };

    if (window.Alpine) {
        initPaymentTimer();
    } else {
        document.addEventListener('alpine:init', initPaymentTimer);
    }

    document.addEventListener('livewire:navigated', () => {
        if (window.midtransCleanup) window.midtransCleanup();
        if (window.qrcodeCleanup) window.qrcodeCleanup();

        window.midtransCleanup = Livewire.on('pay-with-midtrans', (event) => {
            let snapToken = event.token || (event[0] && event[0].token) || event;
            if (typeof snapToken !== 'string' || snapToken.trim() === '') {
                alert("Gagal memuat Token Pembayaran."); return;
            }
            
            if (window.snap && typeof window.snap.hide === 'function') window.snap.hide();
            
            window.dispatchEvent(new CustomEvent('open-payment-modal'));

            let wrapper = document.getElementById('snap-wrapper');
            wrapper.innerHTML = '<div id="snap-container" class="w-full h-full min-h-[450px]"></div>';

            setTimeout(() => {
                window.snap.embed(snapToken, {
                    embedId: 'snap-container',
                    onSuccess: function(result){
                        // Eksekusi fungsi Lunas di Livewire
                        if(window.Alpine) {
                            let alpineEl = document.querySelector('[x-data^="paymentTimer"]');
                            if(alpineEl && Alpine.$data(alpineEl).$wire) {
                                Alpine.$data(alpineEl).$wire.paymentSuccessCallback();
                            }
                        }
                    },
                    onPending: function(result){ 
                        alert("Menunggu pembayaran diselesaikan."); 
                        window.dispatchEvent(new CustomEvent('close-payment-modal'));
                    },
                    onError: function(result){
                        alert("Pembayaran Gagal. Silakan coba metode lain.");
                        window.dispatchEvent(new CustomEvent('close-payment-modal'));
                    },
                    onClose: function(){ 
                        // PERBAIKAN: Saat tombol close (Batal) Midtrans diklik
                        console.log('Iframe midtrans ditutup, mengecek status aktual...');
                        window.dispatchEvent(new CustomEvent('close-payment-modal'));
                    }
                });
            }, 100);
        });

        window.qrcodeCleanup = Livewire.on('open-qrcode-modal', (event) => {
            window.dispatchEvent(new CustomEvent('open-qrcode-modal'));

            let wrapper2 = document.getElementById('qrcode-wrapper');
            wrapper2.innerHTML = `
                <div class="flex flex-col items-center justify-center gap-4 p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Tunjukkan QR Code ini ke Kasir</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Invoice: <span class="font-mono font-bold text-brand-red">#{{ $pesanan->nomor_invoice }}</span></p>
                    </div>
                    <div class="p-4 bg-white border border-gray-200 shadow-sm rounded-xl">
                        {!! DNS2D::getBarcodeHTML($pesanan->nomor_invoice, 'QRCODE', 4, 4) !!}
                    </div>
                    <div class="w-full p-4 text-sm text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                        <p><span class="font-bold">Total Pembayaran:</span> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            `;
        });
    });
</script>
@endpush