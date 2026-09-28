@props(['title' => 'MedMart', 'active' => ''])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css">
    <style>
        .font-manrope{font-family:'Manrope',sans-serif}
        .font-inter{font-family:'Inter',sans-serif}
        body{ font-family:'Inter',sans-serif; color:#171E26; background:#F7FAFD; }

        .field-input{
            width:100%;
            background:#fff;
            border:1px solid #DBEBFB;
            border-radius:0.65rem;
            padding:0.6rem 0.85rem;
            font-family:'Inter',sans-serif;
            font-size:13.5px;
            color:#171E26;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        .field-input:focus{
            outline:none;
            border-color:#2775E4;
            box-shadow:0 0 0 3px rgba(39,117,228,0.15);
        }
        .field-label{
            display:block;
            font-family:'Inter',sans-serif;
            font-size:12.5px;
            font-weight:600;
            color:#171E26;
            margin-bottom:0.35rem;
        }

        .skel{ position:relative; overflow:hidden; background:#EAF1FB; border-radius:10px; }
        .skel::after{
            content:''; position:absolute; inset:0;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent);
            transform:translateX(-100%); animation:shimmer 1.4s infinite;
        }
        @keyframes shimmer{ 100%{ transform:translateX(100%); } }
        @media (prefers-reduced-motion: reduce){ .skel::after{ animation:none; } }

        #switcher-modal{ transition: opacity 0.2s ease; }
        #ui-modal{ transition: opacity 0.2s ease; }
    </style>
</head>
<body>
    <div class="max-w-[480px] sm:max-w-2xl md:max-w-4xl lg:max-w-6xl mx-auto min-h-screen bg-[#F7FAFD] relative pb-[84px]">

        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-[#EAF1FB] px-4 sm:px-6 lg:px-10 py-3 flex items-center justify-between">
            <button type="button" id="pharmacy-switcher"
                    class="flex items-center gap-2 min-w-0 pl-1.5 pr-3 py-1.5 rounded-full hover:bg-[#F7FAFD] transition">
                <span class="h-7 w-7 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-storefront text-white text-[13px]"></i>
                </span>
                <span id="active-pharmacy-name" class="font-manrope font-bold text-[14px] text-[#171E26] truncate max-w-[160px]">Select Pharmacy</span>
                <i class="ph-light ph-caret-down text-[#171E26]/40 text-xs flex-shrink-0"></i>
            </button>
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <a href="{{route('extra')}}"
                   class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-full font-inter text-[13px] font-medium text-[#171E26]/60 hover:text-[#2775E4] hover:bg-[#F7FAFD] transition">
                    <i class="ph-bold ph ph-list text-xl"></i>
                    Menu
                </a>
                <a href="{{route('extra')}}"
                   class="flex sm:hidden items-center gap-1.5 px-3 py-1.5 rounded-full font-inter text-[13px] font-medium text-[#171E26]/60 hover:text-[#2775E4] hover:bg-[#F7FAFD] transition">
                    <i class="ph-bold ph ph-list text-2xl"></i>
                </a>
            </div>
        </header>

        <div class="p-4 sm:p-6 lg:p-10">
            {{ $slot }}
        </div>

        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] sm:max-w-2xl md:max-w-4xl lg:max-w-6xl bg-white flex justify-around sm:justify-center sm:gap-14 pt-2.5 pb-3 z-50"
             style="box-shadow: 0 -6px 20px rgba(23,30,38,0.06);">
            <a href="/customer/products" class="flex flex-col items-center gap-1 px-3 py-1 font-inter text-[10px] transition-colors {{ $active === 'products' ? 'text-[#2775E4] font-semibold' : 'text-[#171E26]/40' }}">
                <span class="h-8 w-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'products' ? 'bg-[#DBEBFB]' : '' }}">
                    <i class="ph-{{ $active === 'products' ? 'fill' : 'light' }} ph-storefront text-[19px]"></i>
                </span>
                Products
            </a>
            <a href="/customer/cart" class="relative flex flex-col items-center gap-1 px-3 py-1 font-inter text-[10px] transition-colors {{ $active === 'cart' ? 'text-[#2775E4] font-semibold' : 'text-[#171E26]/40' }}">
                <span class="relative h-8 w-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'cart' ? 'bg-[#DBEBFB]' : '' }}">
                    <i class="ph-{{ $active === 'cart' ? 'fill' : 'light' }} ph-shopping-cart-simple text-[19px]"></i>
                    <span id="cart-badge" style="display:none;"
                          class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 rounded-full bg-red-500 text-white text-[9px] font-bold items-center justify-center leading-none transition-transform duration-200 ease-out transform"></span>
                </span>
                Cart
            </a>
            <a href="/customer/orders" class="flex flex-col items-center gap-1 px-3 py-1 font-inter text-[10px] transition-colors {{ $active === 'orders' ? 'text-[#2775E4] font-semibold' : 'text-[#171E26]/40' }}">
                <span class="h-8 w-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'orders' ? 'bg-[#DBEBFB]' : '' }}">
                    <i class="ph-{{ $active === 'orders' ? 'fill' : 'light' }} ph-package text-[19px]"></i>
                </span>
                Orders
            </a>
            <a href="/customer/notifications" class="relative flex flex-col items-center gap-1 px-3 py-1 font-inter text-[10px] transition-colors {{ $active === 'notifications' ? 'text-[#2775E4] font-semibold' : 'text-[#171E26]/40' }}">
                <span class="relative h-8 w-8 rounded-xl flex items-center justify-center transition-colors {{ $active === 'notifications' ? 'bg-[#DBEBFB]' : '' }}">
                    <i class="ph-{{ $active === 'notifications' ? 'fill' : 'light' }} ph-bell text-[19px]"></i>
                    <span id="alerts-badge" style="display:none;"
                          class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 rounded-full bg-red-500 text-white text-[9px] font-bold items-center justify-center leading-none"></span>
                </span>
                Notification
            </a>
        </nav>
    </div>

    {{-- PHARMACY SWITCHER MODAL --}}
    <div id="switcher-modal" class="hidden fixed inset-0 z-[200] items-center justify-center bg-[#171E26]/50 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[400px] max-h-[80vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2.5">
                    <span class="h-9 w-9 rounded-xl bg-[#DBEBFB] flex items-center justify-center">
                        <i class="ph-light ph-storefront text-[#2775E4] text-lg"></i>
                    </span>
                    <h3 class="font-manrope font-bold text-[16px] text-[#171E26]">Switch Pharmacy</h3>
                </div>
                <button type="button" id="close-switcher-btn" aria-label="Close"
                        class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/50">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <div id="pharmacy-list"></div>
            <a href="/customer/pharmacies/join"
                class="flex items-center justify-center gap-2 mt-4 py-3 rounded-xl border-2 border-dashed border-[#DBEBFB] font-inter font-semibold text-[13px] text-[#2775E4] hover:bg-[#F7FAFD] hover:border-[#2775E4] transition">
                <i class="ph-light ph-plus-circle text-base"></i>
                Join Another Pharmacy
            </a>
        </div>
    </div>

    {{-- UI MODAL --}}
    {{-- FIXED: was `justify-center;` (missing "content:", invalid CSS —
         browsers silently ignore it) instead of `justify-content:center;`.
         Since this modal gets display:flex set via JS when shown,
         align-items:center still worked, but without a valid
         justify-content it wasn't actually horizontally centered. --}}
    <div id="ui-modal"
         style="display:none; position:fixed; inset:0; z-index:100; align-items:center; justify-content:center; background-color:rgba(23,30,38,0.45); padding:24px 16px;">
        <div class="bg-white rounded-2xl w-full max-w-[380px] p-6 shadow-xl">
            <div id="ui-modal-icon-wrap" class="h-11 w-11 rounded-xl bg-[#DBEBFB] flex items-center justify-center mb-4">
                <i id="ui-modal-icon" class="ph ph-info text-[#2775E4] text-xl"></i>
            </div>
            <p id="ui-modal-message" class="font-inter text-[14px] text-[#171E26] leading-relaxed"></p>
            <input id="ui-modal-input" type="text"
                   class="w-full mt-4 rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition"
                   style="display:none;">
            <div class="flex justify-end gap-2.5 mt-6">
                <button type="button" id="ui-modal-cancel-btn"
                        class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
                    Cancel
                </button>
                <button type="button" id="ui-modal-confirm-btn"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/minimal/js/customer/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/customer/auth.js') }}"></script>
    <script>
        (function () {
            const modal = document.getElementById('ui-modal');
            const iconWrap = document.getElementById('ui-modal-icon-wrap');
            const icon = document.getElementById('ui-modal-icon');
            const messageEl = document.getElementById('ui-modal-message');
            const input = document.getElementById('ui-modal-input');
            const cancelBtn = document.getElementById('ui-modal-cancel-btn');
            const confirmBtn = document.getElementById('ui-modal-confirm-btn');

            let resolvePending = null;
            let currentType = null;

            function setConfirmDanger(isDanger) {
                if (isDanger) {
                    confirmBtn.className = 'px-4 py-2.5 rounded-xl bg-red-500 text-white font-inter text-[14px] font-semibold shadow-sm hover:bg-red-600 transition';
                    iconWrap.className = 'h-11 w-11 rounded-xl bg-red-50 flex items-center justify-center mb-4';
                    icon.className = 'ph ph-warning text-red-500 text-xl';
                } else {
                    confirmBtn.className = 'px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition';
                    iconWrap.className = 'h-11 w-11 rounded-xl bg-[#DBEBFB] flex items-center justify-center mb-4';
                    icon.className = 'ph ph-info text-[#2775E4] text-xl';
                }
            }

            function open(type, message, options) {
                return new Promise(function (resolve) {
                    currentType = type;
                    resolvePending = resolve;
                    messageEl.textContent = message;

                    const opts = options || {};
                    confirmBtn.textContent = opts.confirmText || 'OK';
                    cancelBtn.textContent = opts.cancelText || 'Cancel';
                    setConfirmDanger(!!opts.danger);

                    if (type === 'alert') {
                        cancelBtn.style.display = 'none';
                    } else {
                        cancelBtn.style.display = 'inline-block';
                    }

                    if (type === 'prompt') {
                        input.style.display = 'block';
                        input.value = opts.defaultValue != null ? opts.defaultValue : '';
                        setTimeout(function () { input.focus(); input.select(); }, 0);
                    } else {
                        input.style.display = 'none';
                    }

                    modal.style.display = 'flex';
                });
            }

            function close(result) {
                modal.style.display = 'none';
                if (resolvePending) {
                    resolvePending(result);
                    resolvePending = null;
                }
                currentType = null;
            }

            function handleConfirmClick() {
                if (currentType === 'prompt') close(input.value);
                else if (currentType === 'confirm') close(true);
                else close(undefined);
            }

            function handleCancelClick() {
                if (currentType === 'prompt') close(null);
                else close(false);
            }

            confirmBtn.addEventListener('click', handleConfirmClick);
            cancelBtn.addEventListener('click', handleCancelClick);

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    if (currentType === 'alert') close(undefined);
                    else handleCancelClick();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (modal.style.display !== 'flex') return;
                if (event.key === 'Escape') {
                    if (currentType === 'alert') close(undefined);
                    else handleCancelClick();
                } else if (event.key === 'Enter' && currentType === 'prompt') {
                    handleConfirmClick();
                }
            });

            window.UIModal = {
                alert: function (message, options) { return open('alert', message, options); },
                confirm: function (message, options) { return open('confirm', message, options); },
                prompt: function (message, defaultValue, options) { return open('prompt', message, Object.assign({ defaultValue: defaultValue }, options || {})); }
            };
        })();

        async function loadPharmacies() {
            try {
                const data = await CustomerApi.get('/customer/pharmacies');
                const pharmacies = data.data || data;
                const activePharmacy = pharmacies.find(function(p) { return p.is_active; });
                if (activePharmacy) {
                    document.getElementById('active-pharmacy-name').textContent = activePharmacy.name;
                }
                renderPharmacyList(pharmacies);
            } catch (error) {
                console.error('Unable to load pharmacies:', error);
            }
        }

        function renderPharmacyList(pharmacies) {
            const container = document.getElementById('pharmacy-list');
            if (!pharmacies || pharmacies.length === 0) {
                container.innerHTML = '<p class="text-center py-6 font-inter text-[13px] text-[#171E26]/40">No pharmacies linked yet.</p>';
                return;
            }

            container.innerHTML = pharmacies.map(function (pharmacy) {
                const isActive = pharmacy.is_active;
                return `<div data-pharmacy-id="${pharmacy.id}"
                    class="pharmacy-option flex items-center gap-3 p-3 rounded-xl border mb-2 cursor-pointer transition ${isActive ? 'border-[#2775E4] bg-[#F0F6FE]' : 'border-[#EAF1FB] hover:bg-[#F7FAFD]'}">
                    <span class="h-9 w-9 rounded-lg flex items-center justify-center flex-shrink-0 ${isActive ? 'bg-[#2775E4]' : 'bg-[#F7FAFD]'}">
                        <i class="ph-fill ph-storefront text-[15px] ${isActive ? 'text-white' : 'text-[#171E26]/30'}"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate">${pharmacy.name}</p>
                        <p class="font-inter text-[11px] text-[#171E26]/45 mt-0.5">Linked: ${new Date(pharmacy.linked_at).toLocaleDateString()}</p>
                    </div>
                    ${isActive ? `<span class="flex-shrink-0 flex items-center gap-1 font-inter text-[10px] font-semibold px-2.5 py-1 rounded-full bg-[#DBEBFB] text-[#2775E4]"><i class="ph-fill ph-check-circle text-xs"></i>Active</span>` : ''}
                </div>`;
            }).join('');

            container.querySelectorAll('.pharmacy-option').forEach(function (el) {
                el.addEventListener('click', function () {
                    switchPharmacy(Number(el.getAttribute('data-pharmacy-id')));
                });
            });
        }

        async function switchPharmacy(pharmacyId) {
            try {
                await CustomerApi.patch('/customer/pharmacies/switch', { pharmacy_id: pharmacyId });
                window.location.reload();
            } catch (error) {
                await UIModal.alert(error.message || 'Unable to switch pharmacy.');
            }
        }

        document.getElementById('pharmacy-switcher').addEventListener('click', function() {
            document.getElementById('switcher-modal').classList.remove('hidden');
            document.getElementById('switcher-modal').classList.add('flex');
        });

        document.getElementById('close-switcher-btn').addEventListener('click', function() {
            document.getElementById('switcher-modal').classList.add('hidden');
            document.getElementById('switcher-modal').classList.remove('flex');
        });

        function updateCartBadgeUI(itemCount) {
            const badge = document.getElementById('cart-badge');
            if (!badge) return;

            if (itemCount > 0) {
                badge.textContent = itemCount > 9 ? '9+' : String(itemCount);
                badge.style.display = 'flex';

                badge.classList.add('scale-125');
                setTimeout(() => {
                    badge.classList.remove('scale-125');
                }, 200);
            } else {
                badge.style.display = 'none';
            }
        }

        async function loadCartBadge() {
            try {
                const cart = await CustomerApi.get('/customer/cart');
                const items = cart.items || [];
                updateCartBadgeUI(items.length);
            } catch (error) {
                console.error('Unable to load cart badge:', error);
            }
        }

        window.updateCartBadge = loadCartBadge;
        window.addEventListener('cart-updated', function (event) {
            if (event.detail && typeof event.detail.count === 'number') {
                updateCartBadgeUI(event.detail.count);
            } else {
                loadCartBadge();
            }
        });

        // NEW: extracted so it can be called either from a fresh fetch
        // (loadUnreadBadge) or directly from an already-known count
        // (the notifications-updated event) — same split as
        // updateCartBadgeUI/loadCartBadge above.
        function updateUnreadBadgeUI(count) {
            const badge = document.getElementById('alerts-badge');
            if (!badge) return;
            const unreadCount = Number(count || 0);
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        async function loadUnreadBadge() {
            try {
                const data = await CustomerApi.get('/customer/notifications?per_page=100');
                const notifications = data.data || data;
                const unreadCount = notifications.filter(function(n) { return !n.read_at; }).length;
                updateUnreadBadgeUI(unreadCount);
            } catch (error) {
                console.error('Unable to load unread notifications badge:', error);
            }
        }

        // NEW: mirrors window.updateCartBadge / the 'cart-updated' listener
        // above — notifications.js dispatches 'notifications-updated' with
        // the current unread count after every load/mark-read/mark-all-read,
        // so this badge updates immediately without a page refresh.
        window.updateNotificationsBadge = loadUnreadBadge;
        window.addEventListener('notifications-updated', function (event) {
            if (event.detail && typeof event.detail.count === 'number') {
                updateUnreadBadgeUI(event.detail.count);
            } else {
                loadUnreadBadge();
            }
        });

        loadPharmacies();
        loadCartBadge();
        loadUnreadBadge();

    </script>
    {{ $scripts ?? '' }}
</body>
</html>