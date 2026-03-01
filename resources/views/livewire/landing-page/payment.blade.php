<div class="relative min-h-screen pb-24 font-sans bg-gray-50 dark:bg-gray-900 selection:bg-brand-red/30">
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <nav class="sticky top-0 z-50 transition-colors duration-300 border-b border-gray-200 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md dark:border-gray-800">
        <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl lg:px-8">
            <div class="flex items-center gap-3">
                <a wire:navigate href="{{ route('Order') }}" class="flex items-center justify-center w-8 h-8 text-gray-600 transition-all bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300 hover:bg-brand-red hover:text-white active:scale-95">
                    <i class="text-sm fa-solid fa-arrow-left"></i>
                </a>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-extrabold tracking-tight text-gray-800 dark:text-white">Pembayaran</h1>
                </div>
            </div>
            <div class="px-3 py-1 text-xs font-bold text-gray-600 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300">
                #{{ $pesanan->nomor_invoice }}
            </div>
        </div>
    </nav>

    <div class="px-4 pt-6 mx-auto max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            
            <div class="space-y-6 lg:col-span-7">
                
                @if($pesanan->tipe_pesanan === 'delivery')
                    <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700">
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
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-[80px] p-5 bg-white border border-gray-100 shadow-sm rounded-2xl dark:bg-gray-800 dark:border-gray-700 space-y-6">
                    
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

                            @if($this->isKasir)
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

                        <button wire:click="processPayment" wire:loading.attr="disabled" class="relative flex items-center justify-center w-full py-4 font-bold text-white transition-all shadow-lg bg-brand-red rounded-xl hover:bg-red-700 active:scale-95 disabled:opacity-50">
                            <span wire:loading.remove wire:target="processPayment">Bayar Sekarang</span>
                            <span wire:loading wire:target="processPayment"><i class="mr-2 fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        Livewire.on('pay-with-midtrans', (event) => {
            let snapToken = event[0].token;
            
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    // Pembayaran Sukses (Webhook juga akan update DB)
                    alert("Pembayaran Berhasil! Pesanan Anda sedang diproses.");
                    window.location.href = "{{ route('Order') }}"; // Redirect ke halaman sukses/riwayat
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda.");
                },
                onError: function(result){
                    alert("Pembayaran Gagal. Silakan coba lagi.");
                },
                onClose: function(){
                    console.log('Anda menutup popup sebelum menyelesaikan pembayaran');
                }
            });
        });
    });
</script>
@endpush