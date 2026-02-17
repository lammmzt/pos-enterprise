<div>
    {{-- include header --}}
    @include('livewire.landing-page.layout.header')
    <header class="sticky top-[61px] z-30 glass-header shadow-sm border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="mx-auto max-w-7xl">
            <div class="flex items-center justify-between px-4 py-3 lg:px-8">
                <div>
                    <h2 class="flex items-center gap-1 text-sm font-bold leading-tight text-gray-700 dark:text-gray-200">
                        <i class="text-xs fa-solid fa-location-dot text-brand-red"></i> Cabang Dago Atas
                    </h2>
                    <p class="text-[10px] text-gray-400">Buka: 10.00 - 22.00 WIB</p>
                </div>
                <div class="bg-brand-red/10 dark:bg-red-900/30 text-brand-red dark:text-red-400 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex items-center gap-1.5 cursor-pointer hover:bg-brand-red/20 transition-colors" onclick="confirmReset()">
                    <i class="fa-solid fa-rotate-right"></i> Reset
                </div>
            </div>

            <div id="tabs-container" class="flex gap-4 pb-0 pl-4 overflow-x-auto border-b border-gray-200 lg:pl-8 hide-scroll dark:border-gray-700"></div>
            
            <div class="flex flex-col gap-3 px-4 py-3 transition-colors duration-300 bg-white lg:px-8 dark:bg-gray-900 md:flex-row md:items-center">
                <div class="relative w-full md:w-80">
                    <i class="absolute text-sm text-gray-400 transform -translate-y-1/2 fa-solid fa-magnifying-glass left-3 top-1/2"></i>
                    <input type="text" id="search-input" oninput="handleSearch(this.value)" placeholder="Cari topping favoritmu..." class="w-full bg-gray-100 dark:bg-gray-800 dark:text-white text-sm rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-brand-red text-gray-700 transition-colors border border-transparent focus:bg-white dark:focus:bg-gray-800">
                </div>
                
                <div id="category-container" class="flex flex-1 gap-2 overflow-x-auto hide-scroll md:justify-end"></div>
            </div>
        </div>
    </header>

    <div class="relative flex flex-col gap-8 px-4 pt-4 pb-32 mx-auto max-w-7xl lg:pb-8 lg:px-8 lg:flex-row">
        
        <main class="flex-1 min-w-0">
            <div class="flex items-center justify-between pl-1 mb-4 border-l-4 border-brand-orange">
                <h2 class="text-base font-bold text-gray-700 dark:text-gray-200" id="menu-title">Semua Menu</h2>
                <span class="hidden text-xs text-gray-400 md:inline-block">Pilih topping sepuasnya!</span>
            </div>
            
            <div id="menu-grid" class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4 auto-rows-fr"></div>
        </main>

        <aside id="cart-sheet" class="
            fixed bottom-0 left-0 right-0 z-50 
            max-w-md mx-auto flex flex-col h-[85vh] 
            transition-transform duration-300 ease-in-out
            bg-white dark:bg-gray-800 
            rounded-t-3xl shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.3)] 
            border-t border-gray-200 dark:border-gray-700
            
            lg:transform-none lg:translate-y-0
            lg:sticky lg:top-40 lg:right-0 lg:bottom-auto
            lg:w-[380px] lg:h-[calc(100vh-180px)] lg:max-h-[800px]
            lg:rounded-2xl lg:shadow-xl lg:border
            lg:z-30 lg:mx-0
        " style="transform: translateY(calc(100% - 70px));">
            
            <div class="w-full pt-3 pb-2 transition-colors bg-white border-b border-gray-100 cursor-pointer dark:bg-gray-800 rounded-t-3xl lg:rounded-t-2xl lg:cursor-default dark:border-gray-700" onclick="toggleCart()">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto mb-2 lg:hidden"></div>
                <div class="flex items-center justify-between px-5">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                        <i class="fa-solid fa-basket-shopping text-brand-red"></i>
                        Pesananmu
                        <i id="chevron-icon" class="text-sm text-gray-400 transition-transform duration-300 fa-solid fa-chevron-up lg:hidden"></i>
                    </h2>
                    <span id="sheet-total-label" class="text-sm font-bold text-brand-red lg:hidden">Lihat Detail</span>
                </div>
            </div>

            <div class="flex-1 p-5 pb-24 overflow-y-auto transition-colors lg:pb-5 bg-gray-50 dark:bg-gray-900 lg:bg-white lg:dark:bg-gray-800 hide-scroll">
                <div id="cart-container" class="space-y-4"></div>
            </div>

            <div class="absolute lg:relative bottom-0 left-0 right-0 bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700 shadow-[0_-4px_15px_-3px_rgba(0,0,0,0.1)] lg:shadow-none lg:rounded-b-2xl transition-colors">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col flex-1">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400" id="total-info-text">Total</span>
                        <span class="text-lg font-bold text-brand-dark dark:text-white" id="grand-total-display">Rp 0</span>
                    </div>
                    <button onclick="checkout()" class="flex items-center justify-center flex-1 gap-2 px-4 py-3 font-bold text-white transition-all shadow-lg bg-brand-red hover:bg-red-700 rounded-xl shadow-red-200 active:scale-95">
                        <span>Pesan</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </aside>

    </div>

    <div id="overlay-backdrop" onclick="toggleCart(false)" class="fixed inset-0 z-40 hidden transition-opacity duration-300 opacity-0 bg-black/50 backdrop-blur-sm lg:hidden"></div>

</div>
    <script type="text/javascript">
        // --- DATA MASTER ---
        const MENU_DATA = window.MENU_DATA || [
            { id: 1, name: "Kerupuk Mawar", category: "kerupuk", price: 1000, image: "Kerupuk+Mawar", desc: "Kerupuk warna-warni klasik dengan tekstur renyah yang menyerap kuah sempurna." },
            { id: 2, name: "Kerupuk Oren", category: "kerupuk", price: 1000, image: "Kerupuk+Oren", desc: "Kerupuk kanji oren khas seblak, kenyal saat direbus." },
            { id: 3, name: "Makaroni Spiral", category: "kerupuk", price: 1000, image: "Makaroni", desc: "Makaroni spiral premium, tidak mudah hancur." },
            { id: 4, name: "Dumpling Keju", category: "seafood", price: 3500, image: "Dumpling+Keju", desc: "Olahan ikan dengan isian keju lumer di dalamnya." },
            { id: 5, name: "Dumpling Ayam", category: "seafood", price: 3000, image: "Dumpling+Ayam", desc: "Olahan ikan dengan isian daging ayam cincang gurih." },
            { id: 6, name: "Odeng Korea", category: "seafood", price: 4000, image: "Odeng", desc: "Fish cake lembaran ala Korea, lembut dan gurih." },
            { id: 7, name: "Sosis Sapi", category: "baso", price: 2500, image: "Sosis", desc: "Sosis sapi merah, potongan tebal." },
            { id: 8, name: "Baso Sapi", category: "baso", price: 2500, image: "Baso", desc: "Baso sapi halus asli, tekstur berdaging." },
            { id: 9, name: "Ceker Lunak", category: "baso", price: 2000, image: "Ceker", desc: "Ceker ayam yang sudah dipresto, tulang lunak bisa dimakan." },
            { id: 10, name: "Sawi Hijau", category: "sayur", price: 1000, image: "Sawi", desc: "Sayuran segar pelengkap serat." },
            { id: 11, name: "Jamur Enoki", category: "sayur", price: 3000, image: "Enoki", desc: "Jamur jepang dengan tekstur unik." },
            { id: 12, name: "Es Teh Manis", category: "minuman", price: 4000, image: "Es+Teh", desc: "Teh manis dingin menyegarkan, gelas jumbo." },
            { id: 13, name: "Es Jeruk", category: "minuman", price: 5000, image: "Es+Jeruk", desc: "Perasan jeruk asli, manis asam segar." },
            { id: 14, name: "Mie Kuning", category: "kerupuk", price: 1500, image: "Mie", desc: "Mie kuning basah kenyal." },
            { id: 15, name: "Batagor Kering", category: "kerupuk", price: 1500, image: "Batagor", desc: "Batagor mini kering yang gurih." },
            { id: 16, name: "Chikwa", category: "seafood", price: 2500, image: "Chikwa", desc: "Olahan ikan bentuk tabung khas Jepang." },
        ] ;

        const CATEGORIES = [
            { id: 'all', label: 'Semua' },
            { id: 'kerupuk', label: 'Aneka Kerupuk' },
            { id: 'seafood', label: 'Suki & Seafood' },
            { id: 'baso', label: 'Baso & Sosis' },
            { id: 'sayur', label: 'Sayur & Jamur' },
            { id: 'minuman', label: 'Minuman' },
        ];

        const LEVEL_CONFIG = [
            { id: 0, label: '0', color: 'bg-green-500', text: 'Tidak Pedas' },
            { id: 1, label: '1', color: 'bg-yellow-400', text: 'Pedas Sedang' },
            { id: 2, label: '2', color: 'bg-orange-400', text: 'Pedas Nikmat' },
            { id: 3, label: '3', color: 'bg-orange-600', text: 'Pedas Nampol' },
            { id: 4, label: '4', color: 'bg-red-600', text: 'Pedas Gila' },
            { id: 5, label: '5', color: 'bg-red-800', text: 'Pedas Mati' },
        ];

        // --- STATE ---
        let appState = { bowls: [], activeBowlId: null, activeCategory: 'all', searchQuery: '' };
        let isCartOpen = false;

        // --- INIT ---
        function homeInit() {
            const savedData = localStorage.getItem('pos-enterprise');
            if (savedData) appState = JSON.parse(savedData);
            else addBowl("Pemesan 1");

            renderAll();
            
            // Atur event untuk handle responsivitas Desktop vs Mobile
            window.addEventListener('resize', handleResize);
            
            handleResize(); // Eksekusi langsung
        }
        
        // --- SAVE STATE ---
        function saveData() {
            localStorage.setItem('pos-enterprise', JSON.stringify(appState));
            renderAll();
        }

        // --- BOWL LOGIC ---
        function addBowl(nameInput = null) {
            if (!nameInput) {
                showModal(`
                    <div class="p-6 text-center">
                        <h3 class="mb-4 text-lg font-bold dark:text-white">Tambah Pesanan Baru</h3>
                        <input type="text" id="new-bowl-name" placeholder="Nama Pemesan (ex: Budi)" class="w-full p-2 mb-4 border rounded dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-brand-red focus:outline-none">
                        <button onclick="finalizeAddBowl()" class="w-full py-2 font-bold text-white rounded-lg bg-brand-red">Buat Pesanan</button>
                    </div>
                `);
                setTimeout(() => document.getElementById('new-bowl-name').focus(), 100);
                return;
            }
            createBowlLogic(nameInput);
        }

        // --- BOWL LOGIC ---
        function finalizeAddBowl() {
            const name = document.getElementById('new-bowl-name').value;
            if (name) { createBowlLogic(name); closeModal(); }
        }

        // --- createBowl ---
        function createBowlLogic(name) {
            const newId = Date.now().toString();
            appState.bowls.push({ id: newId, name: name, items: {}, note: "", kuah: 'Kuah Kencur (Original)', level: 1 });
            switchBowl(newId);
        }

        // --- switchBowl ---
        function switchBowl(id) {
            appState.activeBowlId = id;
            appState.activeCategory = 'all';
            appState.searchQuery = '';
            document.getElementById('search-input').value = '';
            saveData();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // --- confirmDeleteBowl ---
        function confirmDeleteBowl(id) {
            const bowl = appState.bowls.find(b => b.id === id);
            showModal(`
                <div class="p-6 text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 text-xl text-red-500 bg-red-100 rounded-full dark:bg-red-900/30"><i class="fa-solid fa-trash-can"></i></div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800 dark:text-white">Hapus Pesanan?</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Mangkuk <b>${bowl.name}</b> akan dihapus.</p>
                    <div class="flex gap-3">
                        <button onclick="closeModal()" class="flex-1 py-2 text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200">Batal</button>
                        <button onclick="executeDeleteBowl('${id}')" class="flex-1 py-2 font-bold text-white bg-red-500 rounded-lg hover:bg-red-600">Hapus</button>
                    </div>
                </div>
            `);
        }

        // --- executeDeleteBowl ---
        function executeDeleteBowl(id) {
            appState.bowls = appState.bowls.filter(b => b.id !== id);
            if (appState.bowls.length === 0) addBowl("Pemesan 1");
            else appState.activeBowlId = appState.bowls[0].id;
            appState.activeCategory = 'all'; appState.searchQuery = '';
            saveData(); closeModal();
        }

        // --- confirmReset ---
        function confirmReset() {
             showModal(`
                <div class="p-6 text-center">
                     <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 text-xl text-orange-500 bg-orange-100 rounded-full dark:bg-orange-900/30"><i class="fa-solid fa-rotate-right"></i></div>
                    <h3 class="mb-2 text-lg font-bold dark:text-white">Reset Aplikasi?</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Semua data pesanan akan dihapus.</p>
                    <div class="flex gap-3">
                        <button onclick="closeModal()" class="flex-1 py-2 text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200">Batal</button>
                        <button onclick="executeReset()" class="flex-1 py-2 font-bold text-white rounded-lg bg-brand-red hover:bg-red-700">Ya, Reset</button>
                    </div>
                </div>
            `);
        }

        // --- executeReset ---
        function executeReset() { localStorage.removeItem('pos-enterprise'); Livewire.navigate('Order');; }

        // --- ITEMS & PREFS ---
        function updateItem(itemId, change) {
            if (!appState.activeBowlId) return;
            const bowl = appState.bowls.find(b => b.id === appState.activeBowlId);
            const currentQty = bowl.items[itemId] || 0;
            const newQty = currentQty + change;
            if (newQty <= 0) delete bowl.items[itemId]; else bowl.items[itemId] = newQty;
            saveData();
        }

        function updateBowlNote(id, text) {
            const bowl = appState.bowls.find(b => b.id === id);
            if (bowl) { bowl.note = text; saveData(); }
        }

        function updatePreference(bowlId, type, value) {
            const bowl = appState.bowls.find(b => b.id === bowlId);
            if (bowl) { bowl[type] = value; saveData(); }
        }

        function renameBowl(id) {
            const bowl = appState.bowls.find(b => b.id === id);
            showModal(`
                <div class="p-6 text-center">
                    <h3 class="mb-4 text-lg font-bold dark:text-white">Ganti Nama</h3>
                    <input type="text" id="edit-bowl-name" value="${bowl.name}" class="w-full p-2 mb-4 border rounded dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-brand-red focus:outline-none">
                    <button onclick="finalizeRename('${id}')" class="w-full py-2 font-bold text-white rounded-lg bg-brand-dark dark:bg-gray-600">Simpan</button>
                </div>
            `);
        }

        function finalizeRename(id) {
            const newName = document.getElementById('edit-bowl-name').value;
            if (newName) {
                appState.bowls.find(b => b.id === id).name = newName;
                saveData(); closeModal();
            }
        }

        function openMenuInfo(itemId) {
            const item = MENU_DATA.find(i => i.id == itemId);
            showModal(`
                <div class="relative">
                    <div class="h-48 bg-gray-200 dark:bg-gray-700">
                        <img src="https://placehold.co/400x300/orange/white?text=${item.image}" class="object-cover w-full h-full">
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">${item.name}</h3>
                            <span class="px-2 py-1 text-sm font-bold text-white rounded bg-brand-red">${formatRupiah(item.price)}</span>
                        </div>
                        <p class="mb-4 text-sm leading-relaxed text-gray-500 dark:text-gray-400">${item.desc}</p>
                        <button onclick="updateItem(${item.id}, 1); closeModal();" class="flex items-center justify-center w-full gap-2 py-3 font-bold text-white transition-colors bg-brand-red rounded-xl hover:bg-red-700">
                            <i class="fa-solid fa-plus"></i> Tambah ke Pesanan
                        </button>
                    </div>
                </div>
            `);
        }

    
        function setCategory(id) { appState.activeCategory = id; renderCategories(); renderMenu(); }
        function handleSearch(val) { appState.searchQuery = val.toLowerCase(); renderMenu(); }
        function getBowlTotal(bowl) { return Object.entries(bowl.items).reduce((sum, [id, qty]) => sum + (MENU_DATA.find(i => i.id == id)?.price || 0) * qty, 0); }
        function getGrandTotal() { return appState.bowls.reduce((sum, bowl) => sum + getBowlTotal(bowl), 0); }
        function formatRupiah(amount) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount); }

        // --- RENDERERS ---
        function renderAll() { renderTabs(); renderCategories(); renderMenu(); renderCart(); renderFloatingBar(); }

        function renderTabs() {
            const container = document.getElementById('tabs-container');
            let html = '';
            appState.bowls.forEach((bowl, index) => {
                const isActive = bowl.id === appState.activeBowlId;
                const activeClass = isActive ? 'border-brand-red text-brand-red' : 'border-transparent text-gray-400 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-200';
                const badgeClass = isActive ? 'bg-brand-red text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400';
                html += `
                    <button onclick="switchBowl('${bowl.id}')" class="pb-3 border-b-2 ${activeClass} font-semibold whitespace-nowrap flex items-center gap-2 text-sm transition-all">
                        <span class="${badgeClass} w-6 h-6 rounded-full flex items-center justify-center text-xs">${index + 1}</span>
                        ${bowl.name}
                    </button>
                `;
            });
            html += `<button onclick="addBowl()" class="flex items-center gap-1 pb-3 pr-4 text-sm font-medium border-b-2 border-transparent text-brand-orange whitespace-nowrap hover:text-orange-600"><i class="fa-solid fa-circle-plus"></i> Tambah</button>`;
            container.innerHTML = html;
        }

        function renderCategories() {
            document.getElementById('category-container').innerHTML = CATEGORIES.map(cat => {
                const isActive = appState.activeCategory === cat.id;
                const classes = isActive ? 'bg-brand-red text-white shadow-md shadow-red-200' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700';
                return `<button onclick="setCategory('${cat.id}')" class="px-4 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all ${classes}">${cat.label}</button>`;
            }).join('');
        }

        function renderMenu() {
            const container = document.getElementById('menu-grid');
            const activeBowl = appState.bowls.find(b => b.id === appState.activeBowlId);
            
            let filtered = MENU_DATA;
            if (appState.activeCategory !== 'all') filtered = filtered.filter(item => item.category === appState.activeCategory);
            if (appState.searchQuery) filtered = filtered.filter(item => item.name.toLowerCase().includes(appState.searchQuery));

            if (filtered.length === 0) {
                container.innerHTML = `<div class="py-10 text-sm text-center text-gray-400 col-span-full dark:text-gray-500">Menu tidak ditemukan :(</div>`;
                return;
            }

            container.innerHTML = filtered.map(item => {
                const qty = activeBowl?.items[item.id] || 0;
                let btnHtml = qty === 0 
                    ? `<button onclick="updateItem(${item.id}, 1)" class="flex items-center justify-center w-full gap-1 px-3 py-2 text-xs font-bold text-white transition-transform rounded-lg shadow-sm lg:w-auto bg-brand-red active:scale-95"><i class="fa-solid fa-plus"></i> Tambah</button>`
                    : `<div class="flex items-center justify-between w-full p-1 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <button onclick="updateItem(${item.id}, -1)" class="flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded dark:bg-gray-600 dark:border-gray-500 text-brand-red dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-500"><i class="text-xs fa-solid fa-minus"></i></button>
                            <span class="text-sm font-bold text-gray-700 dark:text-white animate-fade-in">${qty}</span>
                            <button onclick="updateItem(${item.id}, 1)" class="flex items-center justify-center w-8 h-8 text-white rounded shadow-sm bg-brand-red hover:bg-red-700"><i class="text-xs fa-solid fa-plus"></i></button>
                       </div>`;

                return `
                <div class="relative flex flex-col justify-between h-full p-3 transition-colors bg-white border border-gray-100 shadow-sm dark:bg-gray-800 rounded-2xl dark:border-gray-700 animate-fade-in group hover:shadow-md">
                    <button onclick="openMenuInfo(${item.id})" class="absolute z-10 flex items-center justify-center text-gray-400 transition-transform border border-gray-100 rounded-full shadow-sm top-2 right-2 w-7 h-7 bg-white/80 dark:bg-gray-900/80 backdrop-blur hover:text-brand-orange dark:border-gray-600 hover:scale-110">
                        <i class="text-xs fa-solid fa-info"></i>
                    </button>
                    <div class="relative mb-3">
                        <div class="w-full aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center overflow-hidden group-hover:scale-[1.02] transition-transform duration-300">
                             <img src="https://placehold.co/300x300/orange/white?text=${item.image}" class="object-cover w-full h-full">
                        </div>
                        <span class="absolute top-2 left-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur text-[10px] px-2 py-1 rounded-lg text-gray-600 dark:text-gray-300 font-bold border border-gray-200 dark:border-gray-600 shadow-sm">${formatRupiah(item.price)}</span>
                    </div>
                    <div>
                        <h3 class="mb-1 text-sm font-bold leading-tight text-gray-800 dark:text-gray-100 line-clamp-2">${item.name}</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-3 capitalize">${item.category}</p>
                        <div class="mt-auto">${btnHtml}</div>
                    </div>
                </div>`;
            }).join('');
        }

        function renderCart() {
            const container = document.getElementById('cart-container');
            let html = '';
            const sortedBowls = [...appState.bowls].sort((a, b) => (a.id === appState.activeBowlId ? -1 : 1));

            sortedBowls.forEach((bowl, index) => {
                const isActive = bowl.id === appState.activeBowlId;
                const bowlTotal = getBowlTotal(bowl);
                const hasItems = Object.keys(bowl.items).length > 0;

                if (isActive) {
                    let itemsHtml = !hasItems 
                        ? `<li class="py-4 text-sm italic text-center text-gray-400 border border-gray-200 border-dashed rounded-lg bg-white/50 dark:bg-gray-900/30 dark:border-gray-600">Belum ada menu dipilih</li>`
                        : Object.entries(bowl.items).map(([id, qty]) => {
                            const item = MENU_DATA.find(i => i.id == id);
                            return `
                                <li class="flex items-start justify-between py-2 border-b border-gray-100 border-dashed dark:border-gray-700 last:border-0 group">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-brand-red dark:text-red-400 font-bold text-xs bg-red-50 dark:bg-red-900/20 px-1.5 rounded">${qty}x</span>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 line-clamp-1">${item.name}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 pl-2">
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">${formatRupiah(item.price * qty)}</span>
                                        <button onclick="updateItem(${item.id}, -1)" class="flex items-center justify-center w-5 h-5 text-gray-400 bg-gray-100 rounded dark:bg-gray-700 hover:bg-red-50 hover:text-red-500"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                    </div>
                                </li>`;
                        }).join('');

                    const levelConfig = LEVEL_CONFIG.find(l => l.id == bowl.level);
                    let spicyHtml = `<div class="flex gap-1 p-1 mt-2 overflow-x-auto bg-gray-100 rounded-lg dark:bg-gray-700 hide-scroll">`;
                    LEVEL_CONFIG.forEach(lvl => {
                        const isSelected = lvl.id == bowl.level;
                        const activeStyle = isSelected ? `${lvl.color} text-white shadow-md transform scale-105` : `text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600`;
                        spicyHtml += `<button onclick="updatePreference('${bowl.id}', 'level', ${lvl.id})" class="flex-1 min-w-[24px] h-7 rounded-md text-[10px] font-bold transition-all ${activeStyle}">${lvl.label}</button>`;
                    });
                    spicyHtml += `</div><div class="text-[10px] text-right mt-1 font-bold ${levelConfig.color.replace('bg-', 'text-')}">${levelConfig.text}</div>`;

                    html += `
                    <div class="relative overflow-hidden transition-colors bg-white border-2 shadow-sm border-brand-red/10 dark:bg-gray-800 rounded-xl animate-fade-in">
                         <div class="flex items-center justify-between p-3 border-b border-red-100 bg-red-50/50 dark:bg-red-900/10 dark:border-red-900/30">
                             <div class="flex items-center gap-2 cursor-pointer" onclick="renameBowl('${bowl.id}')">
                                <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-brand-red"><i class="fa-solid fa-user"></i></div>
                                <h3 class="font-bold text-gray-800 dark:text-white line-clamp-1">${bowl.name} <i class="fa-solid fa-pencil text-[10px] text-gray-400 ml-1"></i></h3>
                             </div>
                             <button onclick="confirmDeleteBowl('${bowl.id}')" class="px-2 text-gray-400 hover:text-red-500"><i class="fa-regular fa-trash-can"></i></button>
                         </div>

                        <div class="p-4">
                            <ul class="pr-1 mb-4 space-y-1 overflow-y-auto max-h-48 hide-scroll scrollbar-thin scrollbar-thumb-gray-200">${itemsHtml}</ul>
                            <div class="grid grid-cols-1 gap-3 p-3 border border-gray-100 rounded-lg bg-gray-50 dark:bg-gray-900 dark:border-gray-700">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Kuah</label>
                                    <select onchange="updatePreference('${bowl.id}', 'kuah', this.value)" class="w-full p-2 mt-1 text-xs text-gray-800 bg-white border border-gray-200 rounded outline-none cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 focus:border-brand-red">
                                        <option ${bowl.kuah.includes('Kencur') ? 'selected' : ''}>Kuah Kencur (Original)</option>
                                        <option ${bowl.kuah.includes('Jeletot') ? 'selected' : ''}>Kuah Jeletot (Merah)</option>
                                        <option ${bowl.kuah.includes('Nyemek') ? 'selected' : ''}>Kuah Nyemek</option>
                                    </select>
                                </div>
                                <div><label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Level Pedas</label>${spicyHtml}</div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider flex items-center gap-1"><i class="fa-regular fa-note-sticky"></i> Catatan Khusus</label>
                                    <textarea oninput="updateBowlNote('${bowl.id}', this.value)" class="w-full p-2 mt-1 text-xs text-gray-800 bg-white border border-gray-200 rounded outline-none resize-none dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:border-brand-red" rows="2" placeholder="Contoh: Jangan pake bawang...">${bowl.note || ''}</textarea>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-2 mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="text-lg font-bold text-brand-red">${formatRupiah(bowlTotal)}</span>
                            </div>
                        </div>
                    </div>`;
                } else {
                    const levelConf = LEVEL_CONFIG.find(l => l.id == bowl.level);
                    html += `
                    <div onclick="switchBowl('${bowl.id}')" class="flex items-center justify-between p-3 transition-all bg-white border border-gray-200 shadow-sm cursor-pointer dark:border-gray-700 dark:bg-gray-800 rounded-xl opacity-70 hover:opacity-100 group">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-xs font-bold text-gray-500 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">${index + 1}</div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-gray-800 truncate transition-colors dark:text-white group-hover:text-brand-red">${bowl.name}</h3>
                                <div class="flex gap-2 text-[10px] mt-0.5">
                                    <span class="text-gray-500 dark:text-gray-400 whitespace-nowrap">${Object.keys(bowl.items).length} Items</span>
                                    <span class="text-xs ${levelConf.color.replace('bg-', 'text-')} font-bold whitespace-nowrap">Lvl ${bowl.level}</span>
                                </div>
                            </div>
                        </div>
                        <span class="pl-2 text-sm font-bold text-gray-700 dark:text-gray-300">${formatRupiah(bowlTotal)}</span>
                    </div>`;
                }
            });
            container.innerHTML = html;
        }

        function renderFloatingBar() {
            const grandTotal = getGrandTotal();
            const bowlCount = appState.bowls.length;
            document.getElementById('total-info-text').innerText = `Total (${bowlCount} Mangkuk)`;
            document.getElementById('grand-total-display').innerText = formatRupiah(grandTotal);
        }

        // --- CART SHEET TOGGLE (Fokus Perbaikan) ---
        function toggleCart(forceState = null) {
            if (window.innerWidth >= 1024) return;

            isCartOpen = forceState !== null ? forceState : !isCartOpen;
            const sheet = document.getElementById('cart-sheet');
            const overlay = document.getElementById('overlay-backdrop');
            const chevron = document.getElementById('chevron-icon');

            if (isCartOpen) {
                // Posisi Terbuka (Full)
                sheet.style.transform = 'translateY(0)';
                overlay.classList.remove('hidden');
                
                // Gunakan requestAnimationFrame agar browser me-render display block dulu sebelum transisi opacity
                requestAnimationFrame(() => {
                    overlay.classList.remove('opacity-0');
                });
                
                chevron.style.transform = 'rotate(180deg)';
                document.getElementById('sheet-total-label').innerText = 'Tutup';
            } else {
                // Posisi Tertutup (Sisakan 70px di bawah)
                sheet.style.transform = 'translateY(calc(100% - 70px))';
                overlay.classList.add('opacity-0');
                
                // Tunggu CSS transition opacity selesai sebelum menambahkan .hidden
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
                
                chevron.style.transform = 'rotate(0deg)';
                document.getElementById('sheet-total-label').innerText = 'Lihat Detail';
            }
        }

        function handleResize() {
            const sheet = document.getElementById('cart-sheet');
            const overlay = document.getElementById('overlay-backdrop');
            
            if (window.innerWidth >= 1024) {
                // Mode Desktop: Bersihkan inline style & sembunyikan overlay
                sheet.style.transform = ''; 
                overlay.classList.add('hidden');
                overlay.classList.add('opacity-0');
                isCartOpen = false;
            } else {
                // Mode Mobile: Pastikan posisi default saat dimuat
                if (!isCartOpen) {
                    sheet.style.transform = 'translateY(calc(100% - 70px))';
                }
            }
        }

        function checkout() {
            const total = getGrandTotal();
            if (total === 0) {
                showModal('<div class="p-6 text-center text-gray-600 dark:text-gray-400">Keranjang masih kosong!</div>');
                return;
            }

            const content = `
                <div class="p-6 text-center">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-2xl rounded-full bg-brand-red/10 text-brand-red">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold dark:text-white">Konfirmasi Pesanan</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Total pembayaran adalah <span class="font-bold text-gray-800 dark:text-gray-200">${formatRupiah(total)}</span>. Lanjut ke pembayaran?
                    </p>
                    <div class="flex gap-3">
                        <button onclick="closeModal()" class="flex-1 py-2.5 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <a href="{{ route('Payment') }}" wire:navigate class="flex-1 py-2.5 bg-brand-red text-white rounded-xl font-bold shadow-lg shadow-red-500/20 hover:bg-red-700 transition-colors">
                            Ya, Bayar
                        </a>
                    </div>
                </div>
            `;
            showModal(content);
        }

        homeInit();
        // Pemicu inisialisasi yang tahan terhadap wire:navigate
        document.addEventListener('livewire:navigated', () => {
            if (document.getElementById('cart-sheet')) {
                homeInit();
            }
        });
    </script>
