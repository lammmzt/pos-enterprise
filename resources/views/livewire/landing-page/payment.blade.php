@php
    // PERBAIKAN LOGIKA WAKTU (ANTI-BUG REFRESH)
    // 1. Ambil waktu pesanan dibuat
    $waktuDibuat = \Carbon\Carbon::parse($pesanan->created_at);
    
    // 2. Tambahkan 10 menit sebagai batas kedaluwarsa
    $waktuKedaluwarsa = $waktuDibuat->copy()->addMinutes(10);
    
    // 3. Hitung selisih dari waktu kedaluwarsa dengan detik saat ini
    // (false digunakan agar jika waktu sudah lewat, hasilnya menjadi minus)
    $sisaDetik = now()->diffInSeconds($waktuKedaluwarsa, false);
    
    // 4. Pastikan sisa waktu tidak kurang dari 0 dan tidak lebih dari 600 detik
    $sisaWaktu = $sisaDetik > 0 ? (int) $sisaDetik : 0;
    if ($sisaWaktu > 600) {
        $sisaWaktu = 600;
    }
@endphp

<div x-data="paymentTimer({{ $sisaWaktu }})" 
     @open-payment-modal.window="showPaymentModal = true" 
     @close-payment-modal.window="closeModal()"
     class="relative min-h-screen pb-24 font-sans bg-gray-50 dark:bg-gray-900 selection:bg-brand-red/30">
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}" data-navigate-track></script>

    <nav class="sticky top-0 z-50 transition-colors duration-300 border-b border-gray-200 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md dark:border-gray-800">
        <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl lg:px-8">
            <div class="flex items-center gap-3">
                <a wire:navigate href="{{ route('Order') }}" class="flex items-center justify-center w-8 h-8 text-gray-600 transition-all bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300 hover:bg-brand-red hover:text-white active:scale-95">
                    <i class="text-sm fa-solid fa-arrow-left"></i>
                </a>
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 text-white shadow-lg rounded-xl bg-gradient-to-br from-brand-red to-orange-500 shadow-orange-500/20 rotate-3">
                        <i class="text-sm fa-solid fa-fire-flame-curved"></i>
                    </div>
                    <h1 class="text-lg font-extrabold tracking-tight text-gray-800 dark:text-white">Pembayaran</h1>
                </div>
            </div>
            <div class="px-3 py-1 text-xs font-bold text-gray-600 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300">
                #{{ $pesanan->id_pesanan }}
            </div>
        </div>
    </nav>

    <div class="px-4 pt-6 mx-auto max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            
            <div class="space-y-6 lg:col-span-7">
                <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-4 text-base font-bold text-gray-800 dark:text-white">Tipe Pesanan</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex flex-col items-center p-3 text-center transition-all border-2 cursor-pointer rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 {{ $tipe_pesanan === 'dinein' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="dinein" class="hidden">
                            <i class="mb-2 text-2xl fa-solid fa-utensils {{ $tipe_pesanan === 'dinein' ? 'text-brand-red' : 'text-gray-400' }}"></i>
                            <span class="text-xs font-bold {{ $tipe_pesanan === 'dinein' ? 'text-brand-red' : 'text-gray-500' }}">Makan Sini</span>
                        </label>
                        <label class="relative flex flex-col items-center p-3 text-center transition-all border-2 cursor-pointer rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 {{ $tipe_pesanan === 'takeaway' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="takeaway" class="hidden">
                            <i class="mb-2 text-2xl fa-solid fa-bag-shopping {{ $tipe_pesanan === 'takeaway' ? 'text-brand-red' : 'text-gray-400' }}"></i>
                            <span class="text-xs font-bold {{ $tipe_pesanan === 'takeaway' ? 'text-brand-red' : 'text-gray-500' }}">Bawa Pulang</span>
                        </label>
                        <label class="relative flex flex-col items-center p-3 text-center transition-all border-2 cursor-pointer rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 {{ $tipe_pesanan === 'delivery' ? 'border-brand-red bg-red-50/50 dark:bg-red-900/20' : 'border-gray-100 dark:border-gray-700' }}">
                            <input type="radio" wire:model.live="tipe_pesanan" value="delivery" class="hidden">
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
                                <input type="number" wire:model="nomor_hp" class="w-full p-3 text-sm bg-gray-50 dark:bg-gray-900 border @error('nomor_hp') border-red-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-brand-orange dark:text-white">
                                @error('nomor_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Alamat Lengkap</label>
                                <textarea wire:model="alamat" rows="2" class="w-full p-3 text-sm bg-gray-50 dark:bg-gray-900 border @error('alamat') border-red-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-brand-orange dark:text-white" placeholder="Nama Jalan, RT/RW, Patokan..."></textarea>
                                @error('alamat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Catatan Tambahan (Opsional)</label>
                                <input type="text" wire:model="catatan" class="w-full p-3 text-sm border border-gray-200 outline-none bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-brand-orange dark:text-white" placeholder="Cth: Pagar warna hitam">
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
                            <label class="flex items-center justify-between p-4 transition-colors border-2 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50 {{ $metode_pembayaran === 'qris' ? 'border-brand-red bg-red-50/30 dark:bg-red-900/10' : 'border-gray-100 dark:border-gray-700' }}">
                                <div class="flex items-center gap-3">
                                    <div class="text-2xl text-blue-600"><i class="fa-solid fa-qrcode"></i></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800 dark:text-white">QRIS / E-Wallet</h4>
                                        <p class="text-[10px] text-gray-500">Gopay, ShopeePay, Dana, M-Banking</p>
                                    </div>
                                </div>
                                <input type="radio" wire:model="metode_pembayaran" value="qris" class="w-5 h-5 text-brand-red focus:ring-brand-red">
                            </label>

                            @if($isKasir)
                                <label class="flex items-center justify-between p-4 transition-colors border-2 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50 {{ $metode_pembayaran === 'tunai' ? 'border-brand-red bg-red-50/30 dark:bg-red-900/10' : 'border-gray-100 dark:border-gray-700' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="text-2xl text-emerald-500"><i class="fa-solid fa-money-bill-wave"></i></div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 dark:text-white">Tunai (Kasir)</h4>
                                            <p class="text-[10px] text-gray-500">Bayar langsung di tempat</p>
                                        </div>
                                    </div>
                                    <input type="radio" wire:model="metode_pembayaran" value="tunai" class="w-5 h-5 text-brand-red focus:ring-brand-red">
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Total Pembayaran</span>
                            <span class="text-2xl font-black text-brand-red">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <button wire:click="processPayment" wire:loading.attr="disabled" :disabled="timeLeft <= 0" class="relative flex items-center justify-center w-full py-4 font-bold text-white transition-all shadow-lg bg-brand-red rounded-xl hover:bg-red-700 active:scale-95 disabled:opacity-50">
                            <span wire:loading.remove wire:target="processPayment" x-text="timeLeft > 0 ? 'Bayar Sekarang' : 'Waktu Habis'"></span>
                            <span wire:loading wire:target="processPayment"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showPaymentModal" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
        <div x-show="showPaymentModal" x-transition.opacity @click="closeModal()" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
        
        <div x-show="showPaymentModal" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95" 
             class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl flex flex-col overflow-hidden max-h-[85vh] sm:max-h-[90vh]">
            
            <div class="flex justify-center w-full pt-3 pb-1 bg-white sm:hidden dark:bg-gray-900">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
            </div>

            <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <i class="text-xl fa-solid fa-shield-halved text-brand-red"></i>
                    <h3 class="font-bold text-gray-900 dark:text-white">Pembayaran Aman</h3>
                </div>
                <button @click="closeModal()" class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors bg-gray-100 rounded-full dark:bg-gray-800 hover:text-red-500 hover:bg-red-50">
                    <i class="text-lg fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="snap-wrapper" class="flex-1 w-full overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="flex flex-col items-center justify-center h-full min-h-[450px] gap-3 text-gray-400">
                    <i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i>
                    <span class="text-xs font-medium tracking-widest uppercase animate-pulse">Menyiapkan Pembayaran...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inisialisasi Timer Alpine JS Global
    document.addEventListener('alpine:init', () => {
        Alpine.data('paymentTimer', (initialSeconds) => ({
            showPaymentModal: false,
            // Pastikan nilai detik merupakan angka bulat
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
                // Menjalankan fungsi return stock & ubah status di backend Livewire
                if (this.$wire) {
                    this.$wire.cancelExpiredOrder();
                }
            },

            closeModal() {
                this.showPaymentModal = false;
                
                // Mencegah Bug state Midtrans "PopupInView"
                if (window.snap && typeof window.snap.hide === 'function') {
                    window.snap.hide();
                }

                // Mereset Wrapper setelah animasi tutup modal selesai
                setTimeout(() => {
                    let wrapper = document.getElementById('snap-wrapper');
                    if(wrapper){
                        wrapper.innerHTML = `<div id="snap-container" class="flex flex-col items-center justify-center w-full h-full min-h-[450px] gap-3 text-gray-400"><i class="text-3xl fa-solid fa-circle-notch fa-spin text-brand-red"></i><span class="text-xs font-medium tracking-widest uppercase animate-pulse">Menyiapkan...</span></div>`;
                    }
                }, 300);
            }
        }));
    });

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('pay-with-midtrans', (event) => {
            
            let snapToken = event.token || (event[0] && event[0].token) || event;
            if (typeof snapToken !== 'string' || snapToken.trim() === '') {
                alert("Gagal memuat Token Pembayaran."); return;
            }
            
            if (window.snap && typeof window.snap.hide === 'function') {
                window.snap.hide();
            }
            
            window.dispatchEvent(new CustomEvent('open-payment-modal'));

            let wrapper = document.getElementById('snap-wrapper');
            wrapper.innerHTML = '<div id="snap-container" class="w-full h-full min-h-[450px]"></div>';

            setTimeout(() => {
                window.snap.embed(snapToken, {
                    embedId: 'snap-container',
                    onSuccess: function(result){
                        alert("Pembayaran Berhasil! Pesanan Anda sedang diproses.");
                        window.location.href = "{{ route('Order') }}"; 
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran diselesaikan.");
                    },
                    onError: function(result){
                        alert("Pembayaran Gagal. Silakan coba metode lain.");
                        window.dispatchEvent(new CustomEvent('close-payment-modal'));
                    },
                    onClose: function(){
                        console.log('Menutup iframe midtrans.');
                    }
                });
            }, 100);
        });
    });
</script>
@endpush