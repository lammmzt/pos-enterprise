<div>
    <div class="pb-20 space-y-10">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $desc_page }}</p>
        </div>

        <section class="space-y-4">
            <div class="overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
                <div class="row">
                    <div class="flex flex-col items-center justify-end p-4 border-b border-gray-100 col-12 dark:border-gray-800 md:flex-row">
                        <button wire:click="create" class="px-6 py-3 text-sm font-bold text-white transition-all bg-indigo-600 rounded-xl hover:bg-indigo-700">
                            <i class="mr-2 ti ti-plus"></i> Buat PO (Pembelian) Baru
                        </button>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-between gap-4 p-6 border-b border-gray-100 dark:border-gray-800 md:flex-row">
                    <div class="flex items-center w-full gap-4 md:w-auto">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 uppercase">Show</span>
                            <div class="relative">
                                <select wire:model.live="view" class="px-3 py-2 pr-8 text-xs font-bold transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                    <option value="5">5</option><option value="10">10</option><option value="25">25</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <i class="text-xs text-gray-400 dark:text-gray-500 ti ti-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full md:w-80">
                        <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl pl-11 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari No. Referensi...">
                    </div>
                </div>

                <div class="relative overflow-x-auto font-mono text-xs min-h-[300px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Tanggal</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">No Referensi</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Pemasok</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Total Harga</th>
                                <th class="px-6 py-4 font-bold text-center text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($pembelians as $item)
                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">{{ $item->nomor_referensi }}</td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $item->pemasok->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button wire:click="showDetail({{ $item->id_pembelian }})" class="p-2 text-indigo-600 transition-colors bg-indigo-100 rounded-lg hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50" title="Lihat Detail">
                                            <i class="ti ti-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 font-medium text-center text-gray-400">Belum ada data pembelian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-100 dark:border-gray-800">{{ $pembelians->links() }}</div>
            </div>
        </section>
    </div>

    {{-- MODAL FORM MASTER-DETAIL PEMBELIAN --}}
    <section x-data="{ open: @entangle('isModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10001] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-6xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Buat Purchase Order (Pembelian)</h4>
                        <button type="button" wire:click="closeModal" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>
                    
                    <form class="space-y-6 font-mono text-sm">
                        <div class="grid grid-cols-1 gap-4 p-5 border border-gray-100 md:grid-cols-3 bg-gray-50 dark:bg-gray-800/50 rounded-2xl dark:border-gray-800">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Pemasok</label>
                                <div wire:ignore class="mt-1" x-data="{
                                    init() {
                                        $($refs.select).select2({ placeholder: '-- Pilih Pemasok --', width: '100%', dropdownParent: $($el) })
                                        .on('change', (e) => { $wire.set('form.id_pemasok', e.target.value); });
                                        $watch('$wire.form.id_pemasok', (value) => { $($refs.select).val(value).trigger('change.select2'); });
                                    }
                                }">
                                    <select x-ref="select" class="w-full">
                                        <option value="">-- Pilih Pemasok --</option>
                                        @foreach($pemasoks as $pem)
                                            <option value="{{ $pem->id_pemasok }}">{{ $pem->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('form.id_pemasok') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Tanggal Pembelian</label>
                                <input type="date" wire:model="form.tanggal_pembelian" class="w-full px-4 py-2 mt-1 text-gray-400 bg-white border-none outline-none dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                @error('form.tanggal_pembelian') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">No. Referensi (Auto)</label>
                                <input type="text" wire:model="form.nomor_referensi" disabled class="w-full px-4 py-2 mt-1 font-bold text-gray-500 bg-gray-200 border-none outline-none cursor-not-allowed dark:bg-gray-700 rounded-xl">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-bold text-gray-900 dark:text-white">Daftar Produk Dibeli</h5>
                                <button type="button" wire:click="addItem" class="px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg hover:bg-indigo-100 transition-colors">
                                    + Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($form->items as $index => $item)
                                    <div class="flex flex-col gap-3 p-4 bg-white border border-gray-100 dark:bg-gray-900 rounded-xl dark:border-gray-800 md:flex-row md:items-start" wire:key="item-{{ $index }}">
                                        
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-xs font-bold text-gray-500">Produk</label>
                                            <div wire:ignore class="mt-1" x-data="{
                                                init() {
                                                    $($refs.select).select2({ placeholder: 'Pilih Produk', width: '100%', dropdownParent: $($el) })
                                                    .on('change', (e) => { 
                                                        $wire.productSelected({{ $index }}, e.target.value); 
                                                    });
                                                    $watch('$wire.form.items.{{ $index }}.id_produk', (value) => { $($refs.select).val(value).trigger('change.select2'); });
                                                }
                                            }">
                                                <select x-ref="select" class="w-full">
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach($produks as $prod)
                                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('form.items.'.$index.'.id_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="w-full md:w-20">
                                            <label class="block text-xs font-bold text-gray-500">Qty</label>
                                            <input type="number" wire:model="form.items.{{ $index }}.jumlah" min="1" class="w-full px-3 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                            @error('form.items.'.$index.'.jumlah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="w-full md:w-40">
                                            <label class="block text-xs font-bold text-gray-500">Harga Satuan Beli (Rp)</label>
                                            <div class="mt-1" x-data="{ 
                                                    raw: @entangle('form.items.'.$index.'.harga_satuan'), 
                                                    display: '',
                                                    init() {
                                                        this.display = this.formatRibuan(this.raw);
                                                        $watch('raw', value => this.display = this.formatRibuan(value));
                                                    },
                                                    formatRibuan(angka) {
                                                        if (angka === null || angka === undefined || angka === '') return '';
                                                        // Ubah desimal titik dari DB jadi koma
                                                        let str = angka.toString().replace('.', ',');
                                                        let parts = str.split(',');
                                                        // Berikan format ribuan
                                                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                        // Kembalikan dengan max 2 angka desimal
                                                        return parts.length > 1 ? parts[0] + ',' + parts[1].substring(0,2) : parts[0];
                                                    },
                                                    updateRaw() {
                                                        // Saat diketik, bersihkan titik ribuan dan ubah koma jadi titik desimal untuk database
                                                        let cleanStr = this.display.replace(/\./g, '').replace(',', '.');
                                                        this.raw = cleanStr;
                                                    }
                                                }">
                                                <input type="text" x-model="display" @input="updateRaw()" class="w-full px-3 py-2 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="0">
                                            </div>
                                            @error('form.items.'.$index.'.harga_satuan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="w-full md:w-40">
                                            <label class="block text-xs font-bold text-gray-500">Set Harga Jual (Rp)</label>
                                            <div class="mt-1" x-data="{ 
                                                    raw: @entangle('form.items.'.$index.'.harga_jual'), 
                                                    display: '',
                                                    init() {
                                                        this.display = this.formatRibuan(this.raw);
                                                        $watch('raw', value => this.display = this.formatRibuan(value));
                                                    },
                                                    formatRibuan(angka) {
                                                        if (angka === null || angka === undefined || angka === '') return '';
                                                        let str = angka.toString().replace('.', ',');
                                                        let parts = str.split(',');
                                                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                        return parts.length > 1 ? parts[0] + ',' + parts[1].substring(0,2) : parts[0];
                                                    },
                                                    updateRaw() {
                                                        let cleanStr = this.display.replace(/\./g, '').replace(',', '.');
                                                        this.raw = cleanStr;
                                                    }
                                                }">
                                                <input type="text" x-model="display" @input="updateRaw()" class="w-full px-3 py-2 border-none outline-none bg-indigo-50 dark:bg-indigo-900/20 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="0">
                                            </div>
                                            @error('form.items.'.$index.'.harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="w-full md:w-36">
                                            <label class="block text-xs font-bold text-gray-500">Tgl. Expired</label>
                                            <input type="date" wire:model="form.items.{{ $index }}.tanggal_kedaluwarsa" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 mt-1 text-gray-400 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                            @error('form.items.'.$index.'.tanggal_kedaluwarsa') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="pt-5">
                                            <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-500 transition-colors bg-red-50 rounded-xl hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20" @if(count($form->items) <= 1) disabled @endif>
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('form.items') <span class="block mt-2 text-xs font-bold text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-6 mt-8 border-t dark:border-gray-800">
                            <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                            <button type="button" wire:click="confirmSave" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all">
                                Simpan & Update Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </section>

    {{-- MODAL KONFIRMASI SIMPAN --}}
    <section x-data="{ open: @entangle('isConfirmModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10005] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full dark:bg-yellow-900/30">
                        <i class="text-2xl text-yellow-600 ti ti-alert-triangle dark:text-yellow-500"></i>
                    </div>
                    <h4 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Simpan</h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Pastikan data produk, harga dasar, harga jual, dan expired date sudah benar. Setelah disimpan, stok dan master harga akan otomatis terupdate. Lanjutkan?</p>
                    <div class="flex justify-center gap-3">
                        <button type="button" wire:click="$set('isConfirmModalOpen', false)" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="button" wire:click="executeSave" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700">Ya, Simpan Data</button>
                    </div>
                </div>
            </div>
        </template>
    </section>

    {{-- MODAL DETAIL PEMBELIAN --}}
    <section x-data="{ open: @entangle('isDetailModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10001] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    @if($detailData)
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Detail PO: <span class="text-indigo-600">{{ $detailData->nomor_referensi }}</span></h4>
                        <button type="button" wire:click="closeModal" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-4 mb-6 font-mono text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div><span class="font-bold">Tanggal:</span> {{ \Carbon\Carbon::parse($detailData->tanggal_pembelian)->format('d M Y') }}</div>
                        <div><span class="font-bold">Pemasok:</span> {{ $detailData->pemasok->nama ?? '-' }}</div>
                        <div><span class="font-bold">Status:</span> <span class="uppercase text-emerald-600">{{ $detailData->status }}</span></div>
                        <div><span class="font-bold">Dibuat Oleh:</span> {{ $detailData->user->nama ?? '-' }}</div>
                    </div>

                    <div class="overflow-x-auto font-mono text-xs border border-gray-100 rounded-xl dark:border-gray-800">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-4 py-3 font-bold text-gray-500">Produk</th>
                                    <th class="px-4 py-3 font-bold text-center text-gray-500">Qty</th>
                                    <th class="px-4 py-3 font-bold text-right text-gray-500">Harga Satuan</th>
                                    <th class="px-4 py-3 font-bold text-right text-gray-500">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($detailData->detailPembelian as $det)
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $det->produk->nama ?? 'Produk Dihapus' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $det->jumlah }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($det->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-bold text-right text-indigo-600 dark:text-indigo-400">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 font-bold text-right text-gray-900 dark:text-white">TOTAL KESELURUHAN</td>
                                    <td class="px-4 py-3 text-base font-bold text-right text-gray-900 dark:text-white">Rp {{ number_format($detailData->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </template>
    </section>
</div>