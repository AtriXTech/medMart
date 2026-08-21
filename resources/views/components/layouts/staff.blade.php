{{--
    Intended path: resources/views/components/layouts/staff.blade.php
    (adjust if your project keeps layout components elsewhere)

    CHANGE SUMMARY:
    - Fully rebuilt in Tailwind. app.css is no longer loaded from this layout.
      Any other staff page still using app.css classes (.card, .btn, .stat-grid,
      .badge, .alert, etc.) will render unstyled until migrated — confirmed
      acceptable, migrating page-by-page.
    - Permission-gating logic is UNCHANGED: same data-permission attributes,
      same hidden-by-permission / permissions-loaded classes (now driven by
      Tailwind's `hidden` utility instead of the old <style> block), same
      applyPermissions() fetch against /staff/profile.
    - Auth.requireAuth(), Api.getUser(), logout button: unchanged.
    - All nav hrefs and active-state logic: unchanged.
    - Added: mobile drawer nav (the old layout had no mobile handling).
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart Staff</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
        .sidebar-scroll::-webkit-scrollbar{ width:5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb{ background:#DBEBFB; border-radius:10px; }
        .nav-link{ transition: background 0.15s ease, color 0.15s ease; }
        .nav-link.active{ background: linear-gradient(90deg, rgba(39,117,228,0.08), rgba(8,174,188,0.08)); color:#2775E4; }
        .nav-link.active .nav-icon{ color:#2775E4; }
        .nav-link.active .nav-bar{ opacity:1; }
        #sidebarDrawer, #sidebarOverlay{ transition: transform 0.28s ease, opacity 0.28s ease; }
        @media (prefers-reduced-motion: reduce){ #sidebarDrawer,#sidebarOverlay{ transition:none; } }

        /*
          Permission gating — same behavior as before, just Tailwind-driven.
          Default: hidden. JS adds/removes `hidden` on #sidebar-nav links
          based on data-permission, same as the old hidden-by-permission class did.
        */
        #sidebar-nav a[data-permission]{ display:none; }
        #sidebar-nav.permissions-loaded a[data-permission]{ display:flex; }
        #sidebar-nav.permissions-loaded a[data-permission].hidden-by-permission{ display:none; }

        /* Shared form field styling — used across staff pages (POS, Settings, Staff, etc.) */
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

        /* Shared skeleton loading utility */
        .skel{
            position:relative;
            overflow:hidden;
            background:#EAF1FB;
            border-radius:10px;
        }
        .skel::after{
            content:'';
            position:absolute; inset:0;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent);
            transform:translateX(-100%);
            animation:shimmer 1.4s infinite;
        }
        @keyframes shimmer{ 100%{ transform:translateX(100%); } }
        @media (prefers-reduced-motion: reduce){ .skel::after{ animation:none; } }
    </style>
</head>
<body class="antialiased">

<div class="flex min-h-screen">

    {{-- ================= DESKTOP SIDEBAR ================= --}}
    <aside class="hidden lg:flex flex-col w-[248px] shrink-0 bg-white border-r border-[#EAF1FB] h-screen sticky top-0">
        <div class="h-[76px] flex items-center px-6 border-b border-[#EAF1FB]">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center flex-shrink-0">
                <i class="ph-fill ph-cross text-white text-lg"></i>
            </div>
            <p class="ml-3 font-manrope font-extrabold text-[15px] text-[#171E26] truncate">MedMart</p>
        </div>

        <nav id="sidebar-nav" class="flex-1 overflow-y-auto sidebar-scroll py-5 px-3 space-y-6">
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Main</p>
                <a href="/staff/dashboard" data-permission="view_dashboard"
                   class="nav-link {{ $active === 'dashboard' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-squares-four nav-icon text-[18px] text-[#171E26]/45"></i> Dashboard
                </a>
                <a href="/staff/pos" data-permission="process_sales"
                   class="nav-link {{ $active === 'pos' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-cash-register nav-icon text-[18px] text-[#171E26]/45"></i> POS
                </a>
                <a href="/staff/sales" data-permission="view_sales"
                   class="nav-link {{ $active === 'sales' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-chart-line-up nav-icon text-[18px] text-[#171E26]/45"></i> Sales
                </a>
            </div>

            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Inventory</p>
                <a href="/staff/products" data-permission="manage_products"
                   class="nav-link {{ $active === 'products' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-package nav-icon text-[18px] text-[#171E26]/45"></i> Products
                </a>
                <a href="/staff/product-categories" data-permission="manage_categories"
                   class="nav-link {{ $active === 'product-categories' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-tag nav-icon text-[18px] text-[#171E26]/45"></i> Categories
                </a>
                <a href="/staff/suppliers" data-permission="manage_suppliers"
                   class="nav-link {{ $active === 'suppliers' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-truck nav-icon text-[18px] text-[#171E26]/45"></i> Suppliers
                </a>
                <a href="/staff/purchase-orders" data-permission="manage_purchase_orders"
                   class="nav-link {{ $active === 'purchase-orders' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-clipboard-text nav-icon text-[18px] text-[#171E26]/45"></i> Purchase Orders
                </a>
                <a href="/staff/expiring-batches" data-permission="manage_products"
                   class="nav-link {{ $active === 'expiring-batches' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-hourglass-medium nav-icon text-[18px] text-[#171E26]/45"></i> Expiring Batches
                </a>
            </div>

            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Customers</p>
                <a href="/staff/customers" data-permission="manage_customers"
                   class="nav-link {{ $active === 'customers' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-users nav-icon text-[18px] text-[#171E26]/45"></i> Customers
                </a>
                <a href="/staff/orders" data-permission="manage_orders"
                   class="nav-link {{ $active === 'orders' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-shopping-bag-open nav-icon text-[18px] text-[#171E26]/45"></i> Orders
                </a>
                <a href="/staff/prescriptions" data-permission="manage_prescriptions"
                   class="nav-link {{ $active === 'prescriptions' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-file-rx nav-icon text-[18px] text-[#171E26]/45"></i> Prescriptions
                </a>
            </div>

            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Management</p>
                <a href="/staff/staff-management" data-permission="manage_staff"
                   class="nav-link {{ $active === 'staff-management' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-identification-badge nav-icon text-[18px] text-[#171E26]/45"></i> Staff & Roles
                </a>
                <a href="/staff/pharmacy-codes" data-permission="generate_pharmacy_codes"
                   class="nav-link {{ $active === 'pharmacy-codes' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-qr-code nav-icon text-[18px] text-[#171E26]/45"></i> Pharmacy Codes
                </a>
                <a href="/staff/settlement" data-permission="manage_settlement"
                   class="nav-link {{ $active === 'settlement' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-bank nav-icon text-[18px] text-[#171E26]/45"></i> Settlement Account
                </a>
            </div>

            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Account</p>
                <a href="/staff/subscription" data-permission="manage_subscription"
                   class="nav-link {{ $active === 'subscription' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-crown nav-icon text-[18px] text-[#171E26]/45"></i> Subscription
                </a>
                <a href="/staff/pharmacy-settings" data-permission="manage_pharmacy_settings"
                   class="nav-link {{ $active === 'pharmacy-settings' ? 'active' : '' }} relative items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-gear nav-icon text-[18px] text-[#171E26]/45"></i> Pharmacy Settings
                </a>
            </div>
        </nav>

        <div class="p-3 border-t border-[#EAF1FB]">
            <a href="/staff/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#F7FAFD]">
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center text-white font-manrope font-bold text-sm flex-shrink-0">
                    <span id="staff-user-initial">M</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate" id="staff-user-name"></p>
                    <p class="font-inter text-[11px] text-[#171E26]/45">Profile</p>
                </div>
            </a>
            <button id="logout-btn" type="button"
                class="w-full mt-1 flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#F7FAFD] font-inter text-[13px] font-medium text-[#171E26]/60">
                <i class="ph-light ph-sign-out text-[18px]"></i> Logout
            </button>
        </div>
    </aside>

    {{-- ================= MOBILE DRAWER ================= --}}
    <div id="sidebarOverlay" onclick="closeDrawer()" class="hidden fixed inset-0 bg-[#171E26]/40 z-40 opacity-0"></div>
    <aside id="sidebarDrawer" class="fixed lg:hidden top-0 left-0 h-full w-[280px] bg-white z-50 -translate-x-full flex flex-col">
        <div class="h-[68px] flex items-center justify-between px-5 border-b border-[#EAF1FB]">
            <div class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center"><i class="ph-fill ph-cross text-white text-lg"></i></div>
                <p class="font-manrope font-extrabold text-[15px] text-[#171E26]">MedMart</p>
            </div>
            <button onclick="closeDrawer()" aria-label="Close menu" class="h-9 w-9 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/60"><i class="ph ph-x text-xl"></i></button>
        </div>
        <nav id="sidebar-nav-mobile" class="flex-1 overflow-y-auto sidebar-scroll py-5 px-3">
            {{-- Populated by JS: mirrors #sidebar-nav so permission logic only needs to run once. --}}
        </nav>
    </aside>

    {{-- ================= MAIN COLUMN ================= --}}
    <div class="flex-1 min-w-0">
        <header class="sticky top-0 z-30 h-[68px] lg:h-[76px] bg-white/95 backdrop-blur border-b border-[#EAF1FB] flex items-center justify-between px-4 md:px-6 lg:px-8">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="openDrawer()" aria-label="Open menu" class="lg:hidden h-9 w-9 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/70 flex-shrink-0"><i class="ph ph-list text-2xl"></i></button>
                <h1 class="font-manrope font-bold text-[17px] md:text-[19px] text-[#171E26] leading-tight truncate">{{ $title }}</h1>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="/staff/profile" class="hidden sm:block font-inter text-[13px] text-[#171E26]/60 hover:text-[#2775E4]">Profile</a>
                <span id="staff-user-name-topbar" class="hidden sm:block font-inter text-[13px] font-medium text-[#171E26]"></span>
                <button id="logout-btn-topbar" type="button" class="px-3.5 py-2 rounded-lg border border-[#DBEBFB] font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD]">Logout</button>
            </div>
        </header>

        <div class="px-4 md:px-6 lg:px-8 py-6 md:py-8 max-w-[1400px]">
            {{ $slot }}
        </div>
    </div>
</div>

<script src="{{ asset('assets/minimal/js/api.js') }}"></script>
<script src="{{ asset('assets/minimal/js/auth.js') }}"></script>
<script>
    Auth.requireAuth();
    const staffUser = Api.getUser();
    if (staffUser) {
        const name = staffUser.name || staffUser.email || '';
        document.getElementById('staff-user-name').textContent = name;
        document.getElementById('staff-user-name-topbar').textContent = name;
        document.getElementById('staff-user-initial').textContent = (name || 'M').charAt(0).toUpperCase();
    }
    function doLogout() { Auth.logout(); }
    document.getElementById('logout-btn').addEventListener('click', doLogout);
    document.getElementById('logout-btn-topbar').addEventListener('click', doLogout);

    // Mirror the desktop nav into the mobile drawer so we only fetch/apply
    // permissions once against the desktop list, then clone it.
    document.getElementById('sidebar-nav-mobile').innerHTML = document.getElementById('sidebar-nav').innerHTML;

    async function applyPermissions() {
        const navs = [document.getElementById('sidebar-nav'), document.getElementById('sidebar-nav-mobile')];
        try {
            const profile = await Api.get('/staff/profile');
            const userRole = profile.role;

            navs.forEach(function (nav) {
                const navLinks = nav.querySelectorAll('a[data-permission]');
                if (userRole === 'owner') {
                    navLinks.forEach(function (link) { link.classList.remove('hidden-by-permission'); });
                } else {
                    const staffRole = profile.staffRole;
                    const permissions = staffRole ? (staffRole.permissions || []) : [];
                    navLinks.forEach(function (link) {
                        const requiredPermission = link.getAttribute('data-permission');
                        if (permissions.includes(requiredPermission)) {
                            link.classList.remove('hidden-by-permission');
                        } else {
                            link.classList.add('hidden-by-permission');
                        }
                    });
                }
                nav.classList.add('permissions-loaded');
            });
        } catch (error) {
            console.error('Unable to load permissions:', error);
            navs.forEach(function (nav) { nav.classList.add('permissions-loaded'); });
        }
    }
    applyPermissions();

    function openDrawer(){
        document.getElementById('sidebarDrawer').classList.remove('-translate-x-full');
        const overlay = document.getElementById('sidebarOverlay');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer(){
        document.getElementById('sidebarDrawer').classList.add('-translate-x-full');
        const overlay = document.getElementById('sidebarOverlay');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 280);
        document.body.style.overflow = '';
    }
</script>
{{ $scripts ?? '' }}
</body>
</html>