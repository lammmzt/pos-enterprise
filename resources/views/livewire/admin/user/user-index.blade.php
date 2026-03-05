<div>
    <div class="pb-20 space-y-10">
        {{-- Trigger Toast Event --}}
        @if (session()->has('success'))
            <div x-data x-init="window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: '{{ session('success') }}' } }))"></div>
        @endif

        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{$desc_page}}</p>
        </div>

        <section class="space-y-4">
            <div class="overflow-hidden transition-all bg-white border border-gray-200 shadow-sm dark:bg-gray-900 rounded-3xl dark:border-gray-800">
                
                <div class="row">
                    <div class="flex flex-col items-center justify-end p-4 border-b border-gray-100 col-12 dark:border-gray-800 md:flex-row">
                        <button wire:click="create" class="px-6 py-3 text-sm font-bold text-white transition-all bg-indigo-600 hover:bg-indigo-700 rounded-xl">
                            <i class="mr-2 ti ti-plus"></i> Tambah Pengguna
                        </button>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-between gap-4 p-6 border-b border-gray-100 dark:border-gray-800 md:flex-row">
                    <div class="flex items-center w-full gap-4 md:w-auto">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 uppercase">Show</span>
                            <select wire:model.live="view" class="px-3 py-2 text-xs font-bold transition-all border-none outline-none appearance-none cursor-pointer bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                        <button wire:click="$refresh" class="p-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-all active:scale-90 flex items-center gap-2 group">
                            <i class="text-lg transition-transform duration-500 ti ti-refresh" wire:loading.class="text-indigo-600 animate-spin"></i>
                        </button>
                    </div>

                    <div class="relative w-full md:w-80">
                        <i class="absolute text-gray-400 -translate-y-1/2 ti ti-search left-4 top-1/2"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl pl-11 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all" placeholder="Cari nama, username...">
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
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase transition-colors cursor-pointer hover:text-indigo-600" wire:click="sort('nama')">
                                    <div class="flex items-center gap-2">Pengguna <i class="opacity-50 ti ti-arrows-sort"></i></div>
                                </th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase transition-colors cursor-pointer hover:text-indigo-600" wire:click="sort('username')">
                                    <div class="flex items-center gap-2">Username <i class="opacity-50 ti ti-arrows-sort"></i></div>
                                </th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Role</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-4 font-bold text-center text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($users as $user)
                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center w-8 h-8 font-bold text-indigo-600 rounded-full bg-indigo-50 dark:bg-indigo-900/30">
                                                {{ strtoupper(substr($user->nama, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold dark:text-white">{{ $user->nama }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-600 dark:text-gray-400">{{ $user->username }}</td>
                                    <td class="px-6 py-4"><span class="font-bold text-indigo-600 uppercase">{{ $user->role }}</span></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase {{ $user->status === 'aktif' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600' }}">
                                            {{ str_replace('_', ' ', $user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(str_starts_with(strtolower($user->username), 'antrean_') && $user->role === 'pelanggan')
                                                <button wire:click="showQrCode({{ $user->id_user }})" title="Cetak QR Antrean" class="p-2 text-emerald-500 transition-colors bg-emerald-100 rounded-lg hover:bg-emerald-200 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50">
                                                    <i class="ti ti-qrcode"></i>
                                                </button>
                                            @endif
                                            <button wire:click="edit({{ $user->id_user }})" class="p-2 text-blue-500 transition-colors bg-blue-100 rounded-lg hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button wire:click="deleteConfirm({{ $user->id_user }})" class="p-2 text-red-500 transition-colors bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 font-medium text-center text-gray-400">Tidak ada data yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 dark:border-gray-800">
                    {{ $users->links() }}
                </div>
            </div>
        </section>
    </div>

    {{-- MODAL FORM (CREATE / EDIT) - Tetap Sama ... --}}
    <section x-data="{ open: @entangle('isModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10001] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $form->user ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h4>
                        <button wire:click="closeModal" class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"><i class="ti ti-x"></i></button>
                    </div>
                    
                    <form wire:submit="save" class="space-y-4 font-mono text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                <input type="text" wire:model="form.nama" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Masukan nama lengkap"> 
                                @error('form.nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Username</label>
                                <input type="text" wire:model="form.username" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Masukan username">
                                @error('form.username') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" wire:model="form.password" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="{{ $form->user ? 'Kosongkan jika tidak diganti' : 'Minimal 6 karakter' }}" placeholder="Masukan password"
                                @error('form.password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">No. WA</label>
                                <input type="text" wire:model="form.no_hp" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Masukan No. WA" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
                                @error('form.no_hp') <span class="text-xs text-red-500" >{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Hak Akses (Role)</label>
                                <select wire:model="form.role" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                    <option value="owner">Owner</option>
                                    <option value="pelanggan">Pelanggan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Status Akun</label>
                                <select wire:model="form.status" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak_aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 dark:text-gray-300">Catatan</label>
                                <input type="text" wire:model="form.catatan" class="w-full px-4 py-2 mt-1 border-none outline-none bg-gray-50 dark:bg-gray-800 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white" placeholder="Masukan catatan"> 
                                @error('form.catatan') <span class="text-xs text-red-500" >{{ $message }}</span> @enderror
                            </div>
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

    {{-- MODAL KONFIRMASI HAPUS - Tetap Sama --}}
    <section x-data="{ open: @entangle('isDeleteModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10002] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/30">
                        <i class="text-3xl text-red-600 ti ti-alert-triangle">!</i>
                    </div>
                    <h4 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Hapus Pengguna?</h4>
                    <p class="font-medium text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan. Data yang terhapus akan hilang selamanya.</p>
                    <div class="flex justify-center gap-3 mt-8">
                        <button wire:click="closeModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                        <button wire:click="destroy" class="px-6 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-all">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </template>
    </section>

    {{-- MODAL CETAK QR CODE ANTREAN BARU --}}
    <section x-data="{ open: @entangle('isQrModalOpen') }">
        <template x-teleport="body">
            <div x-show="open" class="fixed inset-0 z-[10003] flex items-center justify-center p-4" x-cloak>
                <div x-show="open" x-transition.opacity wire:click="closeQrModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative w-full max-w-sm bg-white dark:bg-gray-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-2xl text-center">
                    
                    <h4 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">QR Code Antrean</h4>
                    <p class="font-medium text-gray-500 dark:text-gray-400 mb-6">Scan QR ini untuk Auto-Login device kasir/pelanggan di outlet.</p>
                    
                    @if($qrUser)
                        <div id="printable-qr" class="p-6 inline-block bg-white border border-gray-200 rounded-2xl shadow-sm mb-4">
                            {{-- Generate URL Login. Pastikan Anda punya Route ini di web.php --}}
                            @php
                                $loginUrl = url('/auto-login/' . base64_encode($qrUser->username));
                            @endphp
                            
                            {!! DNS2D::getBarcodeSVG($loginUrl, 'QRCODE', 8, 8, 'black', true) !!}
                            
                            <p class="text-sm font-bold text-gray-900 mt-4">{{ $qrUser->nama }}</p>
                            <p class="text-[10px] text-gray-500">{{ $qrUser->role }}</p>
                        </div>
                    @endif
                    
                    <div class="flex justify-center gap-3 mt-6">
                        <button wire:click="closeQrModal" class="px-6 py-2.5 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">Tutup</button>
                        <button onclick="printQr()" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/30">
                            <i class="ti ti-printer"></i> Cetak QR
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </section>

</div>

@push('scripts')
<script>
    function printQr() {
        let qrContainer = document.getElementById('printable-qr');
        if (!qrContainer) return;
        
        let printContents = qrContainer.innerHTML;
        
        // Membuka jendela khusus print
        let printWindow = window.open('', '', 'height=600,width=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak QR Code Antrean</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            margin: 0;
                            text-align: center;
                        }
                        .qr-box {
                            padding: 20px;
                            border: 2px dashed #ccc;
                            border-radius: 15px;
                        }
                        svg {
                            max-width: 100%;
                            height: auto;
                        }
                        h2 { margin-bottom: 20px; font-size: 18px; }
                    </style>
                </head>
                <body>
                    <div class="qr-box">
                        <h2>Scan Untuk Login<br>Sistem Antrean</h2>
                        ${printContents}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        
        // Timeout sedikit untuk memastikan SVG ter-render sblm di-print
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endpush