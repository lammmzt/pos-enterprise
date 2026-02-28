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
                        <button wire:click="create" class="px-6 py-3 text-sm font-bold text-white transition-all bg-indigo-600 shadow-sm rounded-xl hover:bg-indigo-700">
                            <i class="mr-2 ti ti-adjustments-alt"></i> Catat Mutasi / Penyesuaian
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
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl pl-11 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari nama produk, catatan...">
                    </div>
                </div>

                <div class="relative overflow-x-auto font-mono text-xs min-h-[300px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Tanggal</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Produk & Batch</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Jenis Mutasi</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Qty (+/-)</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Catatan</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($mutasis as $item)
                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $item->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $item->produk->nama ?? 'Dihapus' }}</span>
                                            <span class="text-[10px] text-gray-500">Batch #{{ $item->id_batch }} | Exp: {{ $item->batchProduk->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($item->batchProduk->tanggal_kedaluwarsa)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->tipe === 'kedaluwarsa')
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 dark:bg-red-500/10">Kedaluwarsa</span>
                                        @elseif($item->tipe === 'keluar')
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-600 dark:bg-orange-500/10">Rusak / Keluar</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">Penambahan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base {{ in_array($item->tipe, ['keluar', 'kedaluwarsa']) ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ in_array($item->tipe, ['keluar', 'kedaluwarsa']) ? '-' : '+' }}{{ $item->jumlah }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 truncate max-w-[200px]" title="{{ $item->catatan }}">{{ $item->catatan }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $item->user->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 font-medium text-center text-gray-400">Belum ada riwayat mutasi/penyesuaian stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-100 dark:border-gray-800">{{ $mutasis->links() }}</div>
            </div>
        </section>
    </div>

    {{-- MODAL FORM PENYESUAIAN --}}
    <section x-data="{ open: @entangle('isModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10001] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Form Penyesuaian / Mutasi Stok</h4>
                        <button type="button" wire:click="closeModal" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>
                    
                    <form class="space-y-5 font-mono text-sm">
                        
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300">Pilih Produk</label>
                            <div wire:ignore class="mt-1" x-data="{
                                init() {
                                    $($refs.select).select2({ placeholder: '-- Cari & Pilih Produk --', width: '100%', dropdownParent: $($el) })
                                    .on('change', (e) => { 
                                        $wire.productSelected(e.target.value); 
                                    });
                                    $watch('$wire.form.id_produk', (value) => { $($refs.select).val(value).trigger('change.select2'); });
                                }
                            }">
                                <select x-ref="select" class="w-full">
                                    <option value="">-- Cari & Pilih Produk --</option>
                                    @foreach($produks as $prod)
                                        <option value="{{ $prod->id_produk }}">{{ $prod->nama }} (Total Stok: {{ $prod->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('form.id_produk') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Pilih Batch (Target)</label>
                                <div class="relative mt-1">
                                    <select wire:model="form.id_batch" class="w-full py-2 pl-4 pr-10 transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" {{ empty($availableBatches) ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Batch --</option>
                                        @foreach($availableBatches as $batch)
                                            <option value="{{ $batch->id_batch }}">
                                                Batch #{{ $batch->id_batch }} | Stok: {{ $batch->jumlah }} | Exp: {{ $batch->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($batch->tanggal_kedaluwarsa)->format('d/m/Y') : '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="text-gray-400 ti ti-chevron-down"></i>
                                    </div>
                                </div>
                                @error('form.id_batch') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Jenis Mutasi</label>
                                <div class="relative mt-1">
                                    <select wire:model="form.tipe" class="w-full py-2 pl-4 pr-10 transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="kedaluwarsa">Buang Kedaluwarsa (Kurangi Stok)</option>
                                        <option value="keluar">Barang Rusak / Hilang (Kurangi Stok)</option>
                                        <option value="masuk">Penyesuaian Tambah (Tambah Stok)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="text-gray-400 ti ti-chevron-down"></i>
                                    </div>
                                </div>
                                @error('form.tipe') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300">Jumlah (Qty)</label>
                            <input type="number" wire:model="form.jumlah" min="1" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Contoh: 5">
                            @error('form.jumlah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300">Catatan / Alasan</label>
                            <textarea wire:model="form.catatan" rows="3" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Cth: Dibuang karena kemasan rusak, stok di gudang berlebih, dll..."></textarea>
                            @error('form.catatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-6 mt-8 border-t dark:border-gray-800">
                            <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                            <button type="button" wire:click="confirmSave" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all">
                                Terapkan Penyesuaian
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
                    <h4 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Mutasi</h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Tindakan ini akan secara otomatis mengubah stok produk dan stok pada batch terkait. Apakah Anda yakin data yang dimasukkan sudah benar?</p>
                    <div class="flex justify-center gap-3">
                        <button type="button" wire:click="$set('isConfirmModalOpen', false)" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200">Batal</button>
                        <button type="button" wire:click="executeSave" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700">Ya, Proses Mutasi</button>
                    </div>
                </div>
            </div>
        </template>
    </section>
</div>