<div>
    <!-- NAVBAR (Consistent with Index) -->
    <nav class="sticky top-0 z-50 transition-colors duration-300 border-b border-gray-200 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md dark:border-gray-800">
        <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl lg:px-8">
            <!-- Brand & Back Button -->
            <div class="flex items-center gap-3">
                <a wire:navigate href="{{ route('Order') }}" class="flex items-center justify-center w-8 h-8 text-gray-600 transition-all bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300 hover:bg-brand-red hover:text-white active:scale-95">
                    <i class="text-sm fa-solid fa-arrow-left"></i>
                </a>
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 text-white shadow-lg rounded-xl bg-gradient-to-br from-brand-red to-orange-500 shadow-orange-500/20 rotate-3">
                        <i class="text-sm fa-solid fa-fire-flame-curved"></i>
                    </div>
                    <h1 class="hidden text-lg font-extrabold leading-none tracking-tight text-gray-800 dark:text-white sm:block">
                        Seblak<span class="text-brand-red">Bucin</span>
                    </h1>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <button onclick="toggleDarkMode()" class="flex items-center justify-center text-gray-500 transition-all bg-gray-100 border border-transparent rounded-full w-9 h-9 dark:bg-gray-800 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-gray-700 active:scale-95 dark:border-gray-700">
                    <i id="dark-mode-icon" class="text-sm fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 w-full max-w-md p-5 mx-auto space-y-6 animate-fade-in">
        
        <!-- Header Page Title -->
        <div class="pb-2 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Checkout & Pembayaran</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Selesaikan pembayaran untuk memproses pesanan.</p>
        </div>

        <!-- Total Card -->
        <div class="bg-gradient-to-br from-brand-red to-orange-600 rounded-2xl p-6 text-white shadow-xl shadow-orange-500/20 text-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
            <div class="absolute w-32 h-32 transition-colors rounded-full -top-10 -right-10 bg-white/10 blur-2xl group-hover:bg-white/20"></div>
            <div class="absolute w-32 h-32 transition-colors rounded-full -bottom-10 -left-10 bg-white/10 blur-2xl group-hover:bg-white/20"></div>
            
            <p class="mb-1 text-sm font-medium text-white/90">Total Pembayaran</p>
            <h2 id="total-display" class="my-2 text-4xl font-extrabold tracking-tight">Rp 0</h2>
            <div class="inline-flex items-center gap-2 px-3 py-1 mt-2 text-xs border rounded-full bg-white/20 backdrop-blur-sm border-white/10">
                <i class="fa-solid fa-receipt"></i>
                <span id="order-id-display">Order #---</span>
            </div>
        </div>

        <!-- Order Summary Brief -->
        <div class="p-4 transition-colors bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700">
            <h3 class="flex items-center gap-2 pb-2 mb-3 text-sm font-bold text-gray-700 border-b border-gray-100 dark:text-gray-200 dark:border-gray-700">
                <i class="fa-solid fa-list-ul text-brand-red"></i> Ringkasan Pesanan
            </h3>
            <div id="summary-items" class="pr-2 space-y-3 overflow-y-auto text-sm text-gray-600 max-h-48 dark:text-gray-400 hide-scroll">
                <!-- Items injected here -->
            </div>
        </div>

        <!-- Payment Methods -->
        <div>
            <h3 class="flex items-center gap-2 mb-3 font-bold text-gray-700 dark:text-gray-200">
                <i class="fa-regular fa-credit-card text-brand-red"></i> Pilih Metode Pembayaran
            </h3>
            
            <div class="space-y-3">
                <!-- Tunai -->
                <label class="relative flex items-center justify-between p-4 bg-white dark:bg-gray-800 border-2 border-transparent rounded-2xl cursor-pointer shadow-sm hover:border-gray-200 dark:hover:border-gray-700 transition-all group has-[:checked]:border-brand-red has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/10 has-[:checked]:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 text-xl text-green-600 bg-green-100 shadow-inner rounded-xl">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 dark:text-white">Tunai / Cash</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Bayar langsung di kasir</div>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 group-has-[:checked]:border-brand-red group-has-[:checked]:bg-brand-red flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-check text-white text-xs opacity-0 group-has-[:checked]:opacity-100"></i>
                    </div>
                    <input type="radio" name="payment" value="tunai" class="hidden" checked>
                </label>

                <!-- QRIS -->
                <label class="relative flex items-center justify-between p-4 bg-white dark:bg-gray-800 border-2 border-transparent rounded-2xl cursor-pointer shadow-sm hover:border-gray-200 dark:hover:border-gray-700 transition-all group has-[:checked]:border-brand-red has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/10 has-[:checked]:shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center p-1.5 shadow-inner">
                             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png" class="object-contain w-full h-full" alt="QRIS">
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 dark:text-white">QRIS</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Scan QR & Verifikasi Otomatis</div>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 group-has-[:checked]:border-brand-red group-has-[:checked]:bg-brand-red flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-check text-white text-xs opacity-0 group-has-[:checked]:opacity-100"></i>
                    </div>
                    <input type="radio" name="payment" value="qris" class="hidden">
                </label>
            </div>
        </div>

    </main>

    <!-- Bottom Button -->
    <div class="sticky bottom-0 z-40 p-4 transition-colors duration-300 bg-white border-t border-gray-100 dark:bg-gray-900 dark:border-gray-800">
        <div class="max-w-md mx-auto">
            <button onclick="processPayment()" class="flex items-center justify-center w-full gap-2 px-4 py-4 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-2xl shadow-red-500/20 active:scale-95 group">
                <i class="text-xs transition-transform fa-solid fa-lock group-hover:scale-110"></i>
                <span>Bayar Sekarang</span>
            </button>
        </div>
    </div>

    <!-- Modal Success -->
    <div id="success-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Content -->
        <div class="relative z-10 w-full max-w-sm p-8 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700">
            <div class="flex items-center justify-center w-24 h-24 mx-auto mb-6 text-4xl text-green-500 bg-green-100 rounded-full shadow-lg dark:bg-green-900/30 animate-bounce shadow-green-500/20">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">Pembayaran Berhasil!</h2>
            <p class="mb-8 leading-relaxed text-gray-500 dark:text-gray-400">Terima kasih, pesananmu akan segera disiapkan oleh dapur.</p>
            <button onclick="finishOrder()" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-3.5 rounded-xl transition-colors">
                Kembali ke Menu Utama
            </button>
        </div>
    </div>
</div>
<script type="text/javascript">
    
     // --- Payment Logic ---
    window.paymentState = window.paymentState || { bowls: [] };

     function initPayment() {

         // Load Order Data
        const savedData = localStorage.getItem('pos-enterprise');
        
        if (!savedData) {
            // FALLBACK FOR PREVIEW: Generate dummy data if visited directly without ordering
            console.log("No data found, generating dummy data for preview.");
            paymentState = {
                bowls: [{
                    id: 'dummy-1',
                    name: 'Contoh Pemesan (Budi)',
                    items: { 1: 2, 4: 1, 12: 1 }, // 2x Kerupuk, 1x Dumpling, 1x Es Teh
                    level: 2,
                    kuah: 'Kuah Kencur (Original)'
                }]
            };
        } else {
            paymentState = JSON.parse(savedData);
        }
        
        renderPage();
    }

     function formatRupiah(amount) { 
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount); 
    }

     // Menu Data (Harus sinkron dengan index.html untuk kalkulasi)
    window.MENU_DATA = window.MENU_DATA || [
        { id: 1, name: "Kerupuk Mawar", price: 1000 },
        { id: 2, name: "Kerupuk Oren", price: 1000 },
        { id: 3, name: "Makaroni Spiral", price: 1000 },
        { id: 4, name: "Dumpling Keju", price: 3500 },
        { id: 5, name: "Dumpling Ayam", price: 3000 },
        { id: 6, name: "Odeng Korea", price: 4000 },
        { id: 7, name: "Sosis Sapi", price: 2500 },
        { id: 8, name: "Baso Sapi", price: 2500 },
        { id: 9, name: "Ceker Lunak", price: 2000 },
        { id: 10, name: "Sawi Hijau", price: 1000 },
        { id: 11, name: "Jamur Enoki", price: 3000 },
        { id: 12, name: "Es Teh Manis", price: 4000 },
        { id: 13, name: "Es Jeruk", price: 5000 },
        { id: 14, name: "Mie Kuning", price: 1500 },
        { id: 15, name: "Batagor Kering", price: 1500 },
        { id: 16, name: "Chikwa", price: 2500 },
    ];

     function getGrandTotal() {
        return paymentState.bowls.reduce((sum, bowl) => {
            return sum + Object.entries(bowl.items).reduce((s, [id, qty]) => {
                return s + (MENU_DATA.find(i => i.id == id)?.price || 0) * qty;
            }, 0);
        }, 0);
    }

     function renderPage() {
        const total = getGrandTotal();
        document.getElementById('total-display').innerText = formatRupiah(total);
        document.getElementById('order-id-display').innerText = `ORD-${Date.now().toString().slice(-6)}`;

         const container = document.getElementById('summary-items');
        let html = '';
        
        paymentState.bowls.forEach(bowl => {
            if(Object.keys(bowl.items).length > 0) {
                html += `
                <div class="pb-3 mb-3 border-b border-gray-100 border-dashed dark:border-gray-700 last:border-0 last:pb-0 last:mb-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-brand-orange"></div>
                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200">${bowl.name}</div>
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-500 dark:text-gray-400">Lvl ${bowl.level}</span>
                    </div>
                `;
                for(const [id, qty] of Object.entries(bowl.items)) {
                    const item = MENU_DATA.find(i => i.id == id);
                    html += `
                    <div class="flex justify-between pl-4 mb-1 text-xs text-gray-500 dark:text-gray-400 last:mb-0">
                        <span>${qty}x ${item.name}</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">${formatRupiah(item.price * qty)}</span>
                    </div>`;
                }
                html += `</div>`;
            }
        });
        container.innerHTML = html;
    }

     function processPayment() {
        const method = document.querySelector('input[name="payment"]:checked').value;
        const btn = document.querySelector('button[onclick="processPayment()"]');
        
        // Loading State
        const originalContent = btn.innerHTML;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...`;
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

         setTimeout(() => {
            if (method === 'qris') {
                // Simulating Midtrans Redirect/Popup
                // In real integration: snap.pay(token) would go here
                alert("Simulasi: Redirect ke Midtrans Snap Gateway...");
            }
            
            // Show Success Modal
            document.getElementById('success-modal').classList.remove('hidden');
            
            // Reset Button state
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }, 2000);
    }

     function finishOrder() {
        localStorage.removeItem('pos-enterprise'); // Clear cart
        goToHome(); // Use safe navigation
    }

     // Run
    initPayment();

    document.addEventListener('livewire:navigated', () => {
        initPayment();
        renderPage();
        window.scrollTo(0, 0);
    });
</script>
