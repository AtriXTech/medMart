{{--
    Intended path: resources/views/components/layouts/super-admin.blade.php
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart Super Admin</title>
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
        .sidebar-scroll::-webkit-scrollbar{ width:5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb{ background:#DBEBFB; border-radius:10px; }
        .nav-link{ transition: background 0.15s ease, color 0.15s ease; }
        .nav-link.active{ background: linear-gradient(90deg, rgba(39,117,228,0.08), rgba(8,174,188,0.08)); color:#2775E4; }
        .nav-link.active .nav-icon{ color:#2775E4; }
        .nav-link.active .nav-bar{ opacity:1; }
        #sidebarDrawer, #sidebarOverlay{ transition: transform 0.28s ease, opacity 0.28s ease; }
        @media (prefers-reduced-motion: reduce){ #sidebarDrawer,#sidebarOverlay{ transition:none; } }

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
                <i class="ph-fill ph-shield-check text-white text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <p class="font-manrope font-extrabold text-[15px] text-[#171E26] truncate leading-tight">MedMart</p>
                <p class="font-inter text-[10px] font-semibold text-[#2775E4] tracking-wider uppercase">Super Admin</p>
            </div>
        </div>

        <nav id="sidebar-nav" class="flex-1 overflow-y-auto sidebar-scroll py-5 px-3 space-y-6">
            {{-- MAIN --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Main</p>
                <a href="{{route('dashboard')}}"
                   class="nav-link {{ $active === 'dashboard' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-squares-four nav-icon text-[18px] text-[#171E26]/45"></i> Dashboard
                </a>
            </div>

            {{-- PHARMACIES --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Pharmacies</p>
                <a href="{{ route('pharmacies') }}"
                   class="nav-link {{ in_array($active, ['pharmacies', 'pharmacy-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-storefront nav-icon text-[18px] text-[#171E26]/45"></i> All Pharmacies
                </a>
                <a href="/super-admin/pharmacies/staff"
                   class="nav-link {{ $active === 'pharmacy-staff' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-users-three nav-icon text-[18px] text-[#171E26]/45"></i> Pharmacy Staff
                </a>
            </div>

            {{-- CUSTOMERS --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Customers</p>
                <a href="{{ route('customs') }}"
                   class="nav-link {{ in_array($active, ['customers', 'customer-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-users nav-icon text-[18px] text-[#171E26]/45"></i> All Customers
                </a>
            </div>

            {{-- ORDERS --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Orders</p>
                <a href="{{route('orders') }}"
                   class="nav-link {{ in_array($active, ['orders', 'order-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-shopping-bag-open nav-icon text-[18px] text-[#171E26]/45"></i> All Orders
                </a>
            </div>

            {{-- PRODUCTS --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Products</p>
                <a href="/super-admin/products"
                   class="nav-link {{ in_array($active, ['products', 'product-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-package nav-icon text-[18px] text-[#171E26]/45"></i> Product Overview
                </a>
                <a href="/super-admin/categories"
                   class="nav-link {{ $active === 'categories' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-tag nav-icon text-[18px] text-[#171E26]/45"></i> Categories
                </a>
            </div>

            {{-- FINANCE --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Finance</p>
                <a href="{{ route('transactions') }}"
                   class="nav-link {{ $active === 'transactions' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-receipt nav-icon text-[18px] text-[#171E26]/45"></i> Transactions
                </a>
                <a href="{{ route('settlements') }}"
                   class="nav-link {{ in_array($active, ['settlements', 'settlement-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-bank nav-icon text-[18px] text-[#171E26]/45"></i> Settlements
                </a>
            </div>

            {{-- SUBSCRIPTIONS --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Subscriptions</p>
                <a href="{{ route('subscriptionPlans') }}"
                   class="nav-link {{ $active === 'subscriptionPlans' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-crown nav-icon text-[18px] text-[#171E26]/45"></i> Plans
                </a>
                <a href="{{ route('subscriptions') }}"
                   class="nav-link {{ in_array($active, ['active-subscriptions', 'subscription-details']) ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-arrows-clockwise nav-icon text-[18px] text-[#171E26]/45"></i> Active Subscriptions
                </a>
            </div>

            {{-- STAFF / ADMIN --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">Staff / Admin</p>
                <a href="{{ route('adminUsers') }}"
                   class="nav-link {{ $active === 'admin-users' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-user-gear nav-icon text-[18px] text-[#171E26]/45"></i> Admin Users
                </a>
                <a href="/super-admin/roles"
                   class="nav-link {{ $active === 'roles-permissions' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <i class="ph-light ph-key nav-icon text-[18px] text-[#171E26]/45"></i> Roles & Permissions
                </a>
            </div>

            {{-- SETTINGS --}}
            <div>
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3 mb-2">System</p>
                <a href="{{ route('settings') }}"
                   class="nav-link {{ $active === 'settings' ? 'active' : '' }} relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-inter text-[14px] font-medium text-[#171E26]/70">
                    <span class="nav-bar absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-gear nav-icon text-[18px] text-[#171E26]/45"></i> Settings
                </a>
            </div>
        </nav>

        <div class="p-3 border-t border-[#EAF1FB]">
            <a href="/super-admin/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#F7FAFD]">
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center text-white font-manrope font-bold text-sm flex-shrink-0">
                    <span id="admin-user-initial">SA</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate" id="admin-user-name">Super Admin</p>
                    <p class="font-inter text-[11px] text-[#171E26]/45">Account Settings</p>
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
    <aside id="sidebarDrawer" class="fixed lg:hidden top-0 left-0 h-full w-[300px] bg-white z-50 -translate-x-full flex flex-col">
        <div class="h-[68px] flex items-center justify-between px-5 border-b border-[#EAF1FB] flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center">
                    <i class="ph-fill ph-shield-check text-white text-lg"></i>
                </div>
                <div>
                    <p class="font-manrope font-extrabold text-[15px] text-[#171E26]">MedMart</p>
                    <p class="font-inter text-[10px] font-semibold text-[#2775E4] uppercase">Super Admin</p>
                </div>
            </div>
            <button onclick="closeDrawer()" aria-label="Close menu" class="h-9 w-9 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/60"><i class="ph ph-x text-xl"></i></button>
        </div>

        <nav id="sidebar-nav-mobile" class="flex-1 overflow-y-auto sidebar-scroll px-2 pb-4">
            {{-- MAIN --}}
            <div class="pt-4 pb-2">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5">Main</p>
                <a href="/super-admin/dashboard"
                   class="nav-link {{ $active === 'dashboard' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <span class="nav-bar absolute left-0 top-2 bottom-2 w-[3px] rounded-full bg-gradient-to-b from-[#2775E4] to-[#08AEBC] opacity-0"></span>
                    <i class="ph-light ph-squares-four nav-icon text-[20px] text-[#171E26]/45"></i> Dashboard
                </a>
            </div>

            {{-- PHARMACIES --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Pharmacies</p>
                <a href="/super-admin/pharmacies"
                   class="nav-link {{ in_array($active, ['pharmacies', 'pharmacy-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-storefront nav-icon text-[20px] text-[#171E26]/45"></i> All Pharmacies
                </a>
                <a href="/super-admin/pharmacies/staff"
                   class="nav-link {{ $active === 'pharmacy-staff' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-users-three nav-icon text-[20px] text-[#171E26]/45"></i> Pharmacy Staff
                </a>
            </div>

            {{-- CUSTOMERS --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Customers</p>
                <a href="/super-admin/customers"
                   class="nav-link {{ in_array($active, ['customers', 'customer-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-users nav-icon text-[20px] text-[#171E26]/45"></i> All Customers
                </a>
            </div>

            {{-- ORDERS --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Orders</p>
                <a href="/super-admin/orders"
                   class="nav-link {{ in_array($active, ['orders', 'order-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-shopping-bag-open nav-icon text-[20px] text-[#171E26]/45"></i> All Orders
                </a>
            </div>

            {{-- PRODUCTS --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Products</p>
                <a href="/super-admin/products"
                   class="nav-link {{ in_array($active, ['products', 'product-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-package nav-icon text-[20px] text-[#171E26]/45"></i> Product Overview
                </a>
                <a href="/super-admin/categories"
                   class="nav-link {{ $active === 'categories' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-tag nav-icon text-[20px] text-[#171E26]/45"></i> Categories
                </a>
            </div>

            {{-- FINANCE --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Finance</p>
                <a href="/super-admin/finance/transactions"
                   class="nav-link {{ $active === 'transactions' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-receipt nav-icon text-[20px] text-[#171E26]/45"></i> Transactions
                </a>
                <a href="/super-admin/finance/settlements"
                   class="nav-link {{ in_array($active, ['settlements', 'settlement-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-bank nav-icon text-[20px] text-[#171E26]/45"></i> Settlements
                </a>
            </div>

            {{-- SUBSCRIPTIONS --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Subscriptions</p>
                <a href="/super-admin/subscriptions/plans"
                   class="nav-link {{ $active === 'subscription-plans' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-crown nav-icon text-[20px] text-[#171E26]/45"></i> Plans
                </a>
                <a href="/super-admin/subscriptions/active"
                   class="nav-link {{ in_array($active, ['active-subscriptions', 'subscription-details']) ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-arrows-clockwise nav-icon text-[20px] text-[#171E26]/45"></i> Active Subscriptions
                </a>
            </div>

            {{-- STAFF / ADMIN --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">Staff / Admin</p>
                <a href="/super-admin/admins"
                   class="nav-link {{ $active === 'admin-users' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-user-gear nav-icon text-[20px] text-[#171E26]/45"></i> Admin Users
                </a>
                <a href="/super-admin/roles"
                   class="nav-link {{ $active === 'roles-permissions' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-key nav-icon text-[20px] text-[#171E26]/45"></i> Roles & Permissions
                </a>
            </div>

            {{-- SETTINGS --}}
            <div class="pt-3 pb-2 border-t border-[#F3F7FC]">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/35 px-3.5 mb-1.5 mt-2">System</p>
                <a href="/super-admin/settings"
                   class="nav-link {{ $active === 'settings' ? 'active' : '' }} relative flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-inter text-[15px] font-medium text-[#171E26]/75">
                    <i class="ph-light ph-gear nav-icon text-[20px] text-[#171E26]/45"></i> Settings
                </a>
            </div>
        </nav>

        <div class="p-3 border-t border-[#EAF1FB] flex-shrink-0">
            <a href="/super-admin/profile" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-[#F7FAFD]">
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#2775E4] to-[#08AEBC] flex items-center justify-center text-white font-manrope font-bold text-sm flex-shrink-0">
                    <span id="admin-user-initial-mobile">SA</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-inter text-[14px] font-semibold text-[#171E26] truncate" id="admin-user-name-mobile">Super Admin</p>
                    <p class="font-inter text-[12px] text-[#171E26]/45">Account Settings</p>
                </div>
            </a>
            <button id="logout-btn-mobile" type="button"
                class="w-full mt-1 flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-[#F7FAFD] font-inter text-[14px] font-medium text-[#171E26]/60">
                <i class="ph-light ph-sign-out text-[19px]"></i> Logout
            </button>
        </div>
    </aside>

    {{-- ================= MAIN COLUMN ================= --}}
    <div class="flex-1 min-w-0">
        <header class="sticky top-0 z-30 h-[68px] lg:h-[76px] bg-white/95 backdrop-blur border-b border-[#EAF1FB] flex items-center justify-between px-4 md:px-6 lg:px-8">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="openDrawer()" aria-label="Open menu" class="lg:hidden h-9 w-9 flex items-center justify-center rounded-lg hover:bg-[#F7FAFD] text-[#171E26]/70 flex-shrink-0"><i class="ph ph-list text-2xl"></i></button>
                <h1 class="font-manrope font-bold text-[17px] md:text-[19px] text-[#171E26] leading-tight truncate">{{ $title }}</h1>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <span id="admin-user-name-topbar" class="hidden sm:block font-inter text-[13px] font-medium text-[#171E26]"></span>
                <button id="logout-btn-topbar" type="button" class="px-3.5 py-2 rounded-lg border border-[#DBEBFB] font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD]">Logout</button>
            </div>
        </header>

        <div class="px-4 md:px-6 lg:px-8 py-6 md:py-8 max-w-[1400px]">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- MODAL CONTAINER --}}
<div id="ui-modal" style="display:none; position:fixed; inset:0; z-index:100; align-items:center; justify-center; background-color:rgba(23,30,38,0.45); padding:24px 16px;">
    <div class="bg-white rounded-2xl w-full max-w-[380px] p-6 shadow-xl">
        <div id="ui-modal-icon-wrap" class="h-11 w-11 rounded-xl bg-[#DBEBFB] flex items-center justify-center mb-4">
            <i id="ui-modal-icon" class="ph ph-info text-[#2775E4] text-xl"></i>
        </div>
        <p id="ui-modal-message" class="font-inter text-[14px] text-[#171E26] leading-relaxed"></p>
        <input id="ui-modal-input" type="text"
               class="w-full mt-4 rounded-xl border border-[#DBEBFB] px-3.5 py-2.5 font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition"
               style="display:none;">
        <div class="flex justify-end gap-2.5 mt-6">
            <button type="button" id="ui-modal-cancel-btn" class="px-4 py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[14px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">Cancel</button>
            <button type="button" id="ui-modal-confirm-btn" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">OK</button>
        </div>
    </div>
</div>

<script src="{{ asset('assets/minimal/js/super-admin/api.js') }}"></script>
<script src="{{ asset('assets/minimal/js/super-admin/auth.js') }}"></script>
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

                cancelBtn.style.display = (type === 'alert') ? 'none' : 'inline-block';

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

    if (typeof SuperAdminAuth !== 'undefined') {
        SuperAdminAuth.requireAuth();
        const user = SuperAdminAuth.getUser();
        if (user) {
            const name = user.name || user.email || 'Super Admin';
            document.getElementById('admin-user-name').textContent = name;
            document.getElementById('admin-user-name-topbar').textContent = name;
            document.getElementById('admin-user-name-mobile').textContent = name;
            const initial = name.charAt(0).toUpperCase();
            document.getElementById('admin-user-initial').textContent = initial;
            document.getElementById('admin-user-initial-mobile').textContent = initial;
        }
    }

    function doLogout() {
        if (typeof SuperAdminAuth !== 'undefined') SuperAdminAuth.logout();
    }
    document.getElementById('logout-btn')?.addEventListener('click', doLogout);
    document.getElementById('logout-btn-topbar')?.addEventListener('click', doLogout);
    document.getElementById('logout-btn-mobile')?.addEventListener('click', doLogout);

    function scrollActiveIntoView() {
        document.querySelectorAll('.nav-link.active').forEach(function (el) {
            el.scrollIntoView({ block: 'nearest', behavior: 'auto' });
        });
    }
    scrollActiveIntoView();

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