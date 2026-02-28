<div>
    <div class="pb-20 space-y-10">

        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{$desc_page}}</p>
        </div>

        <section class="space-y-4">
            <div class="overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
                
                <div class="row">
                    <div class="flex flex-col items-center justify-end p-4 border-b border-gray-100 col-12 dark:border-gray-800 md:flex-row">
                         <button wire:click="create" class="px-6 py-3 text-sm font-bold text-white transition-all bg-gray-100 bg-indigo-600 hover:bg-indigo-700 rounded-xl">
                            <i class="mr-2 ti ti-plus"></i> Tambah Produk
                        </button>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-between gap-4 p-6 border-b border-gray-100 dark:border-gray-800 md:flex-row">
                    <div class="flex items-center w-full gap-4 md:w-auto">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 uppercase">Show</span>
                            <div class="relative">
                                <select wire:model.live="view" class="px-3 py-2 pr-8 text-xs font-bold transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <i class="text-xs text-gray-400 dark:text-gray-500 ti ti-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <button wire:click="$refresh" class="p-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-all active:scale-90 flex items-center gap-2 group">
                            <i class="text-lg transition-transform duration-500 ti ti-refresh" wire:loading.class="text-indigo-600 animate-spin"></i>
                        </button>
                    </div>

                    <div class="relative w-full md:w-80">
                        <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl pl-11 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari nama, SKU, deskripsi...">
                    </div>
                </div>

                <div class="overflow-x-auto font-mono text-sm relative min-h-[300px]">
                    
                    <div wire:loading.flex class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px] z-10 items-center justify-center transition-all">
                        <div class="flex flex-col items-center gap-2">
                            <i class="text-4xl text-indigo-600 ti ti-loader-2 animate-spin"></i>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Loading Data...</span>
                        </div>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Gambar</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase transition-colors cursor-pointer hover:text-indigo-600" wire:click="sort('nama')">
                                    <div class="flex items-center gap-2">Produk <i class="opacity-50 ti ti-arrows-sort"></i></div>
                                </th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Kategori</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Harga</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase transition-colors cursor-pointer hover:text-indigo-600" wire:click="sort('status')">
                                    <div class="flex items-center gap-2">Status <i class="opacity-50 ti ti-arrows-sort"></i></div>
                                </th>
                                <th class="px-6 py-4 font-bold text-center text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($produks as $item)
                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40 group">
                                    <td class="px-6 py-4">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama }}" class="object-cover w-12 h-12 border border-gray-200 rounded-xl dark:border-gray-700">
                                        @else
                                            <div class="flex items-center justify-center w-12 h-12 text-gray-400 bg-gray-100 border border-gray-200 dark:bg-gray-800 rounded-xl dark:border-gray-700">
                                                <i class="text-xl ti ti-photo"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $item->nama }}</span>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400">SKU: {{ $item->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $item->kategori->nama ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-indigo-600">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                                            <span class="text-[10px] text-gray-400">Dasar: Rp {{ number_format($item->harga_dasar, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->status === 'aktif')
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 dark:bg-red-500/10">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="edit({{ $item->id_produk }})" class="p-2 text-blue-500 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button wire:click="deleteConfirm({{ $item->id_produk }})" class="p-2 text-red-500 transition-colors bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 font-medium text-center text-gray-400">
                                        Tidak ada produk yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 dark:border-gray-800">
                    {{ $produks->links() }}
                </div>
            </div>
        </section>
    </div>

    {{-- MODAL FORM --}}
    <section x-data="{ open: @entangle('isModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10001] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-3xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl overflow-y-auto max-h-[90vh]">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $form->produk ? 'Edit Produk' : 'Tambah Produk Baru' }}</h4>
                        <button type="button" wire:click="closeModal" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>
                    
                    <form wire:submit="save" class="space-y-4 font-mono text-sm">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Nama Produk</label>
                                <input type="text" wire:model="form.nama" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Nama Produk">
                                @error('form.nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Kategori</label>
                                
                                <div wire:ignore class="mt-1" x-data="{
                                    init() {
                                        // Inisialisasi Select2 saat Alpine dimuat
                                        $($refs.select).select2({
                                            placeholder: '-- Cari & Pilih Kategori --',
                                            width: '100%',
                                            dropdownParent: $($el) // Sangat penting agar Select2 jalan normal di dalam modal
                                        }).on('change', (e) => {
                                            // Update nilai Livewire saat Select2 berubah (menggantikan wire:model.live)
                                            $wire.set('form.id_kategori', e.target.value);
                                        });

                                        // Pantau perubahan dari sisi Livewire (misal saat tombol Edit ditekan)
                                        $watch('$wire.form.id_kategori', (value) => {
                                            $($refs.select).val(value).trigger('change.select2');
                                        });
                                    }
                                }">
                                    <select x-ref="select" class="w-full px-4 py-2 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="">-- Cari & Pilih Kategori --</option>
                                        @foreach($kategoris as $kat)
                                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('form.id_kategori') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">SKU (Otomatis)</label>
                                <input type="text" wire:model="form.sku" readonly placeholder="Pilih kategori dulu..." class="w-full px-4 py-2 mt-1 text-gray-500 border-none outline-none cursor-not-allowed bg-gray-100/50 dark:bg-gray-800/50 rounded-xl dark:text-gray-400">
                                @error('form.sku') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                           <div x-data="{ 
                                    raw: @entangle('form.harga_dasar'), 
                                    display: '',
                                    init() {
                                        this.display = this.formatRibuan(this.raw);
                                        $watch('raw', value => this.display = this.formatRibuan(value));
                                    },
                                    formatRibuan(angka) {
                                        if (!angka) return '';
                                        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    },
                                    updateRaw() {
                                        // Hapus semua titik saat dikirim ke Livewire
                                        this.raw = this.display.replace(/\./g, '');
                                    }
                                }">
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Harga Dasar</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" x-model="display" @input="updateRaw()" class="w-full py-2 pl-12 pr-4 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="0">
                                </div>
                                @error('form.harga_dasar') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ 
                                    raw: @entangle('form.harga_jual'), 
                                    display: '',
                                    init() {
                                        this.display = this.formatRibuan(this.raw);
                                        $watch('raw', value => this.display = this.formatRibuan(value));
                                    },
                                    formatRibuan(angka) {
                                        if (!angka) return '';
                                        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    },
                                    updateRaw() {
                                        this.raw = this.display.replace(/\./g, '');
                                    }
                                }">
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Harga Jual</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-gray-500 dark:text-gray-400">Rp</span>
                                    <input type="text" x-model="display" @input="updateRaw()" class="w-full py-2 pl-12 pr-4 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="0">
                                </div>
                                @error('form.harga_jual') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Status Produk</label>
                                <div class="relative mt-1">
                                    <select wire:model="form.status" class="w-full py-2 pl-4 pr-10 transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak_aktif">Tidak Aktif</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="text-gray-400 dark:text-gray-500 ti ti-chevron-down"></i>
                                    </div>
                                </div>
                                @error('form.status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Upload Gambar Baru</label>
                                <input type="file" wire:model="gambar_baru" accept="image/*" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <div wire:loading wire:target="gambar_baru" class="mt-1 text-xs text-indigo-500">Mengunggah...</div>
                                @error('gambar_baru') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                
                                {{-- Preview Gambar --}}
                                <div class="mt-2">
                                    @if ($gambar_baru)
                                        <img src="{{ $gambar_baru->temporaryUrl() }}" class="object-cover w-16 h-16 border border-gray-200 rounded-xl">
                                    @elseif($form->produk && $form->produk->gambar)
                                        <img src="{{ asset('storage/'.$form->produk->gambar) }}" class="object-cover w-16 h-16 border border-gray-200 rounded-xl">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300">Deskripsi Singkat</label>
                            <textarea wire:model="form.deskripsi" rows="3" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Deskripsi Singkat Produk"></textarea>
                            @error('form.deskripsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 mt-8 border-t dark:border-gray-800">
                            <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all">
                                <span wire:loading.remove wire:target="save">Simpan Data</span>
                                <span wire:loading wire:target="save">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </section>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <section x-data="{ open: @entangle('isDeleteModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10002] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl text-center">
                    
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/30">
                        <i class="text-3xl text-red-600 ti ti-alert-triangle">!</i>
                    </div>
                    <h4 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Hapus Produk?</h4>
                    <p class="font-medium text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan. Gambar dan data akan terhapus.</p>
                    
                    <div class="flex justify-center gap-3 mt-8">
                        <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                        <button type="button" wire:click="destroy" class="px-6 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-all">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </section>
</div>