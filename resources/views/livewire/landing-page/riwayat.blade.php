<div>
    {{-- include header --}}
    @include('livewire.landing-page.layout.header', ['active' => $active])
    <!-- Main Content -->
    <main class="flex-1 w-full max-w-md p-4 pb-24 mx-auto">
        
        <!-- Filter Tabs -->
        <div class="flex gap-2 pb-2 mb-6 overflow-x-auto hide-scroll">
            <button onclick="filterOrders('all')" id="tab-all" class="px-5 py-2 text-sm font-bold text-white transition-all rounded-full shadow-md bg-brand-red shadow-red-500/30 whitespace-nowrap active:scale-95">
                Semua
            </button>
            <button onclick="filterOrders('active')" id="tab-active" class="px-5 py-2 text-sm font-bold text-gray-500 transition-all bg-white border border-gray-200 rounded-full dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 whitespace-nowrap active:scale-95">
                Sedang Berjalan
            </button>
            <button onclick="filterOrders('completed')" id="tab-completed" class="px-5 py-2 text-sm font-bold text-gray-500 transition-all bg-white border border-gray-200 rounded-full dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 whitespace-nowrap active:scale-95">
                Selesai
            </button>
        </div>

        <!-- Order List Container -->
        <div id="order-list" class="space-y-4">
            <!-- Orders will be injected here via JS -->
        </div>

        <!-- Empty State (Hidden by default) -->
        <div id="empty-state" class="flex-col items-center justify-center hidden py-20 text-center animate-fade-in">
            <div class="flex items-center justify-center w-24 h-24 mb-4 bg-gray-100 rounded-full dark:bg-gray-800">
                <i class="text-4xl text-gray-300 fa-solid fa-receipt dark:text-gray-600"></i>
            </div>
            <h3 class="mb-1 text-lg font-bold text-gray-800 dark:text-white">Belum ada pesanan</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yuk, mulai pesan seblak favoritmu sekarang!</p>
            <a href="index.html" class="px-6 py-2.5 bg-brand-red text-white font-bold rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-transform">
                Pesan Sekarang
            </a>
        </div>

    </main>

    <!-- Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-[60] hidden flex items-end sm:items-center justify-center sm:px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm" onclick="closedModal()"></div>
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-sm rounded-t-3xl sm:rounded-3xl shadow-2xl transform transition-transform duration-300 translate-y-full sm:translate-y-0 sm:scale-95 h-[85vh] sm:h-auto sm:max-h-[80vh] flex flex-col relative z-10 border-t sm:border border-gray-100 dark:border-gray-800" id="modal-content">
            
            <!-- Handle for mobile -->
            <div class="flex justify-center w-full pt-3 pb-1 cursor-pointer sm:hidden" onclick="closedModal()">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
            </div>

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 pt-4 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Rincian Pesanan</h3>
                    <p id="modal-order-id" class="text-xs text-gray-500 dark:text-gray-400">ORD-xxxxxx</p>
                </div>
                <button onclick="closedModal()" class="flex items-center justify-center w-8 h-8 text-gray-500 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 p-6 space-y-6 overflow-y-auto hide-scroll">
                
                <!-- Status Timeline -->
                <div class="p-4 border border-gray-100 bg-gray-50 dark:bg-gray-800 rounded-xl dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div id="modal-status-icon" class="flex items-center justify-center w-10 h-10 text-lg bg-orange-100 rounded-full text-brand-orange">
                            <i class="fa-solid fa-fire-burner"></i>
                        </div>
                        <div>
                            <div id="modal-status-text" class="text-sm font-bold text-gray-800 dark:text-white">Sedang Disiapkan</div>
                            <div id="modal-status-desc" class="text-xs text-gray-500 dark:text-gray-400">Pesananmu sedang dimasak oleh chef.</div>
                        </div>
                    </div>
                </div>

                <!-- Item List -->
                <div>
                    <h4 class="mb-3 text-sm font-bold text-gray-700 dark:text-gray-300">Menu Pesanan</h4>
                    <ul id="modal-items" class="space-y-3">
                        <!-- Items injected here -->
                    </ul>
                </div>

                <!-- Payment Info -->
                <div class="pt-4 border-t border-gray-200 border-dashed dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Metode Pembayaran</span>
                        <span class="text-sm font-bold text-gray-800 dark:text-white">Tunai</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Harga</span>
                        <span id="modal-total" class="text-lg font-extrabold text-brand-red">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-4 bg-white border-t border-gray-100 dark:border-gray-800 dark:bg-gray-900 rounded-b-3xl">
                <button onclick="reOrder()" class="w-full bg-brand-red hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-500/20 active:scale-95 transition-all">
                    Pesan Lagi
                </button>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-[70] hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 transition-opacity bg-black/60 backdrop-blur-sm" onclick="closeReviewModal()"></div>
        <div class="relative z-10 w-full max-w-sm p-6 text-center transition-all transform scale-95 bg-white border border-gray-100 shadow-2xl dark:bg-gray-800 rounded-3xl dark:border-gray-700 animate-slide-up">
            
            <div class="flex items-center justify-center mx-auto mb-4 text-2xl text-yellow-500 bg-yellow-100 rounded-full w-14 h-14">
                <i class="fa-solid fa-star"></i>
            </div>
            <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">Beri Ulasan</h3>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Bagaimana rasa seblak pesananmu?</p>

            <!-- Star Rating -->
            <div class="flex justify-center gap-2 mb-6" id="star-container">
                <button onclick="setRating(1)" class="text-3xl text-gray-300 transition-colors hover:text-yellow-400 focus:outline-none star-btn" data-value="1"><i class="fa-solid fa-star"></i></button>
                <button onclick="setRating(2)" class="text-3xl text-gray-300 transition-colors hover:text-yellow-400 focus:outline-none star-btn" data-value="2"><i class="fa-solid fa-star"></i></button>
                <button onclick="setRating(3)" class="text-3xl text-gray-300 transition-colors hover:text-yellow-400 focus:outline-none star-btn" data-value="3"><i class="fa-solid fa-star"></i></button>
                <button onclick="setRating(4)" class="text-3xl text-gray-300 transition-colors hover:text-yellow-400 focus:outline-none star-btn" data-value="4"><i class="fa-solid fa-star"></i></button>
                <button onclick="setRating(5)" class="text-3xl text-gray-300 transition-colors hover:text-yellow-400 focus:outline-none star-btn" data-value="5"><i class="fa-solid fa-star"></i></button>
            </div>

            <textarea placeholder="Tulis masukanmu disini (opsional)..." class="w-full p-3 mb-4 text-sm text-gray-800 border border-gray-200 outline-none resize-none bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-xl dark:text-white focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red" rows="3"></textarea>

            <button onclick="submitReview()" class="w-full py-3 mb-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl active:scale-95">
                Kirim Ulasan
            </button>
            <button onclick="closeReviewModal()" class="text-sm text-gray-400 transition-colors hover:text-brand-red">
                Batal
            </button>
        </div>
    </div>
</div>
<!-- JAVASCRIPT LOGIC -->
<script>
    // --- Mock Data ---
    window.mockOrders = window.mockOrders || [
        {
            id: 'ORD-882190',
            date: 'Hari ini, 14:30',
            status: 'active',
            statusLabel: 'Sedang Disiapkan',
            total: 18500,
            bowls: [{ name: 'Mangkuk Budi', detail: 'Kuah Kencur, Lvl 3', items: '2x Kerupuk Mawar, 1x Dumpling Keju, 1x Es Teh' }]
        },
        {
            id: 'ORD-772100',
            date: 'Kemarin, 19:00',
            status: 'completed',
            statusLabel: 'Selesai',
            total: 25000,
            bowls: [
                { name: 'Mangkuk Siti', detail: 'Kuah Jeletot, Lvl 1', items: '1x Mie, 1x Sosis, 1x Bakso, 1x Es Jeruk' },
                { name: 'Mangkuk Budi', detail: 'Kuah Kencur, Lvl 2', items: '2x Kerupuk Oren' }
            ]
        },
        {
            id: 'ORD-661022',
            date: '12 Feb 2024',
            status: 'completed',
            statusLabel: 'Selesai',
            total: 12000,
            bowls: [{ name: 'Pesanan Saya', detail: 'Kuah Kacang, Lvl 0', items: '1x Batagor Kering, 1x Siomay, 1x Air Mineral' }]
        }
    ];
    
    window.currentFilter = window.currentFilter || 'all';

    function renderOrders() {
        const container = document.getElementById('order-list');
        const emptyState = document.getElementById('empty-state');
        if (!container || !emptyState) return;
        container.innerHTML = '';
        const filteredOrders = mockOrders.filter(order => {
            if (currentFilter === 'all') return true;
            if (currentFilter === 'active') return order.status === 'active';
            if (currentFilter === 'completed') return order.status === 'completed' || order.status === 'cancelled';
        });
        if (filteredOrders.length === 0) {
            emptyState.classList.remove('hidden');
            emptyState.classList.add('flex');
        } else {
            emptyState.classList.add('hidden');
            emptyState.classList.remove('flex');
            filteredOrders.forEach(order => {
                let statusClass = '', statusIcon = '', reviewBtn = '';
                
                if (order.status === 'active') {
                    statusClass = 'bg-orange-100 text-brand-orange dark:bg-orange-900/30 dark:text-orange-400';
                    statusIcon = '<i class="fa-solid fa-fire-burner animate-pulse"></i>';
                } else if (order.status === 'completed') {
                    statusClass = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
                    statusIcon = '<i class="fa-solid fa-check"></i>';
                    // Add Review Button if Completed
                    reviewBtn = `
                    <button onclick="openReviewModal('${order.id}'); event.stopPropagation();" class="mt-3 w-full py-2.5 rounded-xl border border-brand-red text-brand-red text-xs font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center justify-center gap-1.5 active:scale-[0.98]">
                        <i class="fa-regular fa-star"></i> Beri Ulasan
                    </button>`;
                } else {
                    statusClass = 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400';
                    statusIcon = '<i class="fa-solid fa-xmark"></i>';
                }
                const html = `
                <div onclick="openOrderModal('${order.id}')" class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 active:scale-[0.99] transition-all cursor-pointer group animate-slide-up">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 text-xl text-gray-400 transition-colors rounded-xl bg-gray-50 dark:bg-gray-700 group-hover:text-brand-red">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">${order.id}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${order.date}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 ${statusClass}">
                            ${statusIcon} ${order.statusLabel}
                        </span>
                    </div>
                    <div class="my-3 border-t border-gray-100 border-dashed dark:border-gray-700"></div>
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-500 dark:text-gray-400">${order.bowls.length} Mangkuk Pesanan</div>
                        <div class="text-base font-extrabold text-brand-dark dark:text-white">${formatRupiah(order.total)}</div>
                    </div>
                    ${reviewBtn}
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });
        }
    }
    
    function filterOrders(filter) {
        currentFilter = filter;
        const tabs = ['all', 'active', 'completed'];
        tabs.forEach(t => {
            const btn = document.getElementById(`tab-${t}`);
            if (t === filter) btn.className = "px-5 py-2 rounded-full text-sm font-bold bg-brand-red text-white shadow-md shadow-red-500/30 transition-all whitespace-nowrap active:scale-95";
            else btn.className = "px-5 py-2 rounded-full text-sm font-bold bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 transition-all whitespace-nowrap active:scale-95 hover:bg-gray-50 dark:hover:bg-gray-700";
        });
        renderOrders();
    }
    // --- Detail Modal Logic ---
    function openOrderModal(orderId) {
        const order = mockOrders.find(o => o.id === orderId);
        if (!order) return;
        document.getElementById('modal-order-id').innerText = order.id;
        document.getElementById('modal-total').innerText = formatRupiah(order.total);
        
        const iconBox = document.getElementById('modal-status-icon');
        const statusText = document.getElementById('modal-status-text');
        const statusDesc = document.getElementById('modal-status-desc');
        if (order.status === 'active') {
            iconBox.className = "w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 text-brand-orange flex items-center justify-center text-lg";
            iconBox.innerHTML = '<i class="fa-solid fa-fire-burner"></i>';
            statusText.innerText = "Sedang Disiapkan";
            statusDesc.innerText = "Pesanan sedang dimasak oleh chef.";
        } else {
            iconBox.className = "w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center text-lg";
            iconBox.innerHTML = '<i class="fa-solid fa-check"></i>';
            statusText.innerText = "Pesanan Selesai";
            statusDesc.innerText = "Terima kasih sudah menikmati Seblak Bucin.";
        }
        const itemsContainer = document.getElementById('modal-items');
        itemsContainer.innerHTML = '';
        order.bowls.forEach(bowl => {
            const html = `
            <li class="p-3 border border-gray-100 rounded-lg bg-gray-50 dark:bg-gray-800/50 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-brand-red"></div>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">${bowl.name}</span>
                    <span class="text-[10px] bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-1.5 rounded text-gray-500 dark:text-gray-300">${bowl.detail}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 pl-3.5 leading-relaxed">${bowl.items}</p>
            </li>`;
            itemsContainer.insertAdjacentHTML('beforeend', html);
        });
        const modal = document.getElementById('detail-modal');
        const content = document.getElementById('modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('translate-y-full'), 10);
    }

    function closedModal() {
        const modal = document.getElementById('detail-modal');
        const content = document.getElementById('modal-content');
        content.classList.add('translate-y-full');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    
    // --- Review Modal Logic ---
    window.currentRating = window.currentRating || 0;
    function openReviewModal(orderId) {
        document.getElementById('review-modal').classList.remove('hidden');
        setRating(0); // Reset
    }
    function closeReviewModal() {
        document.getElementById('review-modal').classList.add('hidden');
    }
    function setRating(rating) {
        currentRating = rating;
        const stars = document.querySelectorAll('.star-btn i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.add('text-gray-300');
                star.classList.remove('text-yellow-400');
            }
        });
    }
    function submitReview() {
        if(currentRating === 0) {
            alert("Silakan pilih bintang terlebih dahulu!");
            return;
        }
        // Simulate API Call
        const btn = document.querySelector('#review-modal button[onclick="submitReview()"]');
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim...';
        setTimeout(() => {
            alert("Terima kasih atas ulasan Anda!");
            closeReviewModal();
            btn.innerHTML = 'Kirim Ulasan';
        }, 1000);
    }
    function reOrder() {
        alert("Mengarahkan ke menu pemesanan...");
        window.location.href = 'index.html';
    }
    function formatRupiah(amount) { 
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount); 
    }
    
    // --- INIT SAAT PINDAH HALAMAN (wire:navigate) ---
    document.addEventListener('livewire:navigated', () => {
        renderOrders();
    });
</script>
