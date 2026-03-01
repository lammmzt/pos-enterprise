<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '' }} | {{ config('app.name', 'Seblak Bucin') }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script data-navigate-once>
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Jalankan saat pertama kali muat
        applyTheme();

        // Pastikan tema tetap terkunci setiap kali Livewire selesai berpindah halaman
        document.addEventListener('livewire:navigated', applyTheme);
        
    </script>
    
    {{-- Theme Initializer --}}
    <script data-navigate-once>
        document.addEventListener('alpine:init', () => {

            /* ===============================
            THEME STORE
            =============================== */
            Alpine.store('theme', {
                theme: localStorage.getItem('theme') || 'light',

                init() {
                    applyTheme();
                },

                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    applyTheme();
                }
            });


        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>
</head>

<body class="h-full antialiased bg-white dark:bg-gray-950 selection:bg-indigo-100 dark:selection:bg-indigo-900/30">

    {{-- 
        Konten utama Auth akan merender file login/register.
        Gunakan fixed atau min-h-screen di child view untuk layout cover.
    --}}
    <main>
      {{ $slot }}
    </main>

    {{-- Toast Notification --}}
    <x-toast />
    
    @livewireScripts
    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#form_login").submit(function (e) {
                e.preventDefault();
                $("#btn_login").attr("disabled", "disabled");
                $("#btn_login").html(
                    '<i class="fa-solid fa-circle-notch fa-spin"></i> Memuat...'
                );

                // BUG FIX: Ubah #username menjadi #username sesuai dengan ID di form HTML Anda
                var username = $("#username").val(); 
                var password = $("#password").val();
                
                $.ajax({
                    url: "{{ route('login.process') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // Tambahkan CSRF token
                        username: username, // Kirim sebagai username
                        password: password
                    },
                    success: function (response) {
                        if (response.success == true) {
                            // Memicu event Alpine x-toast via Vanilla JS untuk Notifikasi Sukses
                            window.dispatchEvent(new CustomEvent('toast', { 
                                detail: { type: 'success', message: 'Login berhasil! Mengalihkan...' }
                            }));
                            
                            setTimeout(function() {
                                // add wire navigation
                                window.location.href = "{{ route('admin.dashboard') }}";
                            //    Livewire.navigate("{{ route('admin.dashboard') }}")
                            }, 3000); // Beri waktu 3 detik agar toast terlihat
                            
                        } else {
                            $("#btn_login").removeAttr("disabled");
                            $("#btn_login").html('Masuk');
                            
                            // Memicu event Alpine x-toast via Vanilla JS untuk Notifikasi Error
                            window.dispatchEvent(new CustomEvent('toast', { 
                                detail: { type: 'error', message: response.message || 'Kredensial tidak valid!' } 
                            }));
                        }
                    },
                    error: function(xhr) {
                        $("#btn_login").removeAttr("disabled");
                        $("#btn_login").html('Masuk');
                        
                        // Menangkap error 500/400 dari server
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type: 'error', message: xhr.responseJSON.message }
                        }));
                    }
                });
            });
        });

    </script>
</body>

</html>