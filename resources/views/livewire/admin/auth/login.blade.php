<div >
    <div   div class="fixed inset-0 flex overflow-hidden bg-gray-50 dark:bg-gray-950" x-data>

      {{-- Decorative Background Elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px] dark:bg-indigo-500/5">
            </div>
            <div
                class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/10 rounded-full blur-[120px] dark:bg-purple-500/5">
            </div>
            {{-- Dot Pattern --}}
            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]"
                style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 24px 24px;">
            </div>
        </div>

        {{-- Theme Switcher Floating --}}
        <div class="absolute top-6 right-8 z-[60]">
            <button @click="$store.theme.toggle()"
                class="flex items-center justify-center w-12 h-12 text-gray-500 transition-all bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 hover:text-indigo-600 active:scale-95">
                <i class="text-2xl ti" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            </button>
        </div>

        <div class="relative flex items-center justify-center w-full p-6 mx-auto max-w-7xl md:p-12">

            {{-- Card Container --}}
            <div
                class="w-full max-w-5xl grid md:grid-cols-2 bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl shadow-indigo-500/10 border border-gray-100 dark:border-gray-800 overflow-hidden">

                {{-- Branding Section (Left) --}}
                <div
                    class="relative flex-col justify-between hidden p-12 overflow-hidden bg-indigo-600 md:flex lg:p-16">
                    {{-- Abstract Shapes --}}
                    <div class="absolute top-0 right-0 w-64 h-64 -mt-32 -mr-32 rounded-full bg-white/10 blur-3xl">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-64 h-64 -mb-32 -ml-32 rounded-full bg-indigo-400/20 blur-3xl">
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-indigo-600 bg-white shadow-lg rounded-xl">
                            <i class="text-xs fa-solid fa-fire-flame-curved"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">Seblak-Bucin</span>
                    </div>

                    <div class="relative z-10 space-y-6">
                        <h2 class="text-4xl lg:text-5xl font-bold text-white leading-[1.1] tracking-tight">
                            Powering the <br>
                            <span class="text-indigo-200">Next Generation</span> <br>
                            of Dashboards.
                        </h2>
                        <p class="max-w-sm font-medium leading-relaxed text-indigo-100/80">
                            Sistem manajemen modern dengan performa tinggi dan desain yang memanjakan mata.
                        </p>
                    </div>

                    <div class="relative z-10 flex items-center gap-4">
                        <div class="flex -space-x-3">
                            @foreach([1,2,3] as $i)
                            <img src="https://i.pravatar.cc/150?u={{$i+10}}"
                                class="w-8 h-8 border-2 border-indigo-600 rounded-full shadow-sm">
                            @endforeach
                        </div>
                        <span class="text-xs font-bold tracking-widest text-indigo-100 uppercase">Trusted by 2k+
                            Teams</span>
                    </div>
                </div>

                {{-- Form Section (Right) --}}
                <div class="flex flex-col justify-center p-10 bg-white lg:p-16 dark:bg-gray-900">

                    <div class="flex items-center gap-3 mb-10 md:hidden">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-white bg-indigo-600 shadow-lg rounded-xl shadow-indigo-500/20">
                            <i class="text-2xl ti ti-brand-laravel"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight dark:text-white">LaravaelUI</span>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h3 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Login</h3>
                            <p class="mt-2 font-medium text-gray-500 dark:text-gray-400">Selamat datang kembali!
                                Silakan masukkan detail Anda.</p>
                        </div>

                        {{-- TOMBOL TOAST DITAMBAHKAN DI SINI UNTUK TESTING --}}
                        {{-- <div class="flex pb-2">
                            <button type="button" @click="$dispatch('toast', { type: 'success', message: 'Data berhasil disimpan ke server!' })" 
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all active:scale-95 flex items-center gap-2">
                                <i class="text-lg ti ti-circle-check"></i> Test Toast
                            </button>
                        </div> --}}

                        <form action="#" method="POST" class="space-y-5" id="form_login">
                            @csrf
                            <div class="space-y-4">
                                <x-input label="Username" name="username" type="text" icon="user"
                                    placeholder="Masukan username" id="username" required />

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Kata
                                            Sandi</label>
                                    </div>
                                    <x-input name="password" type="password" icon="lock" id="password" placeholder="••••••••"
                                        required />
                                </div>
                            </div>

                            <div class="flex items-center">
                                <x-checkbox name="remember" label="Biarkan saya tetap masuk" />
                            </div>

                            <x-button type="submit" variant="primary" id="btn_login"
                                class="w-full py-4 shadow-lg rounded-2xl shadow-indigo-600/20">
                                Masuk
                            </x-button>

                            <div class="grid grid-cols-2 gap-4">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>