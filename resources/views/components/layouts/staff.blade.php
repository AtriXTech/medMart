<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart Staff</title>
    <link rel="stylesheet" href="{{ asset('assets/minimal/css/app.css') }}">
    <style>
        #sidebar-nav a[data-permission] {
            display: none;
        }

        #sidebar-nav.permissions-loaded a[data-permission] {
            display: block;
        }

        #sidebar-nav.permissions-loaded a[data-permission].hidden-by-permission {
            display: none;
        }
    </style>
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">MedMart</div>
            <nav id="sidebar-nav">
                <div class="nav-section">Main</div>
                <a href="/staff/dashboard" class="{{ $active === 'dashboard' ? 'active' : '' }}"
                    data-permission="view_dashboard">Dashboard</a>
                <a href="/staff/pos" class="{{ $active === 'pos' ? 'active' : '' }}"
                    data-permission="process_sales">POS</a>
                <a href="/staff/sales" class="{{ $active === 'sales' ? 'active' : '' }}"
                    data-permission="view_sales">Sales</a>

                <div class="nav-section">Inventory</div>
                <a href="/staff/products" class="{{ $active === 'products' ? 'active' : '' }}"
                    data-permission="manage_products">Products</a>
                <a href="/staff/product-categories" class="{{ $active === 'product-categories' ? 'active' : '' }}"
                    data-permission="manage_categories">Categories</a>
                <a href="/staff/suppliers" class="{{ $active === 'suppliers' ? 'active' : '' }}"
                    data-permission="manage_suppliers">Suppliers</a>
                <a href="/staff/purchase-orders" class="{{ $active === 'purchase-orders' ? 'active' : '' }}"
                    data-permission="manage_purchase_orders">Purchase Orders</a>

                <a href="/staff/expiring-batches" class="{{ $active === 'expiring-batches' ? 'active' : '' }}"
                    data-permission="manage_products">Expiring Batches</a>

                <div class="nav-section">Customers</div>
                <a href="/staff/customers" class="{{ $active === 'customers' ? 'active' : '' }}"
                    data-permission="manage_customers">Customers</a>
                <a href="/staff/orders" class="{{ $active === 'orders' ? 'active' : '' }}"
                    data-permission="manage_orders">Orders</a>
                <a href="/staff/prescriptions" class="{{ $active === 'prescriptions' ? 'active' : '' }}"
                    data-permission="manage_prescriptions">Prescriptions</a>

                <div class="nav-section">Management</div>
                <a href="/staff/staff-management" class="{{ $active === 'staff-management' ? 'active' : '' }}"
                    data-permission="manage_staff">Staff & Roles</a>
                <a href="/staff/pharmacy-codes" class="{{ $active === 'pharmacy-codes' ? 'active' : '' }}"
                    data-permission="generate_pharmacy_codes">Pharmacy Codes</a>
                <a href="/staff/settlement" class="{{ $active === 'settlement' ? 'active' : '' }}"
                    data-permission="manage_settlement">Settlement Account</a>

                <div class="nav-section">Account</div>
                <a href="/staff/subscription" class="{{ $active === 'subscription' ? 'active' : '' }}"
                    data-permission="manage_subscription">Subscription</a>
                <a href="/staff/pharmacy-settings" class="{{ $active === 'pharmacy-settings' ? 'active' : '' }}"
                    data-permission="manage_pharmacy_settings">Pharmacy Settings</a>
            </nav>
        </aside>
        <div class="main">
            <header class="topbar">
                <div class="page-title">{{ $title }}</div>
                <div class="user-menu">
                    <a href="/staff/profile" style="color: var(--text-muted); text-decoration: none;">Profile</a>
                    <span id="staff-user-name"></span>
                    <button class="btn btn-secondary" id="logout-btn" type="button">Logout</button>
                </div>
            </header>
            <div class="content">
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
            document.getElementById('staff-user-name').textContent = staffUser.name || staffUser.email || '';
        }
        document.getElementById('logout-btn').addEventListener('click', function() {
            Auth.logout();
        });

        async function applyPermissions() {
            try {
                const profile = await Api.get('/staff/profile');
                const userRole = profile.role;

                const navLinks = document.querySelectorAll('#sidebar-nav a[data-permission]');

                if (userRole === 'owner') {
                    navLinks.forEach(function(link) {
                        link.classList.remove('hidden-by-permission');
                    });
                } else {
                    const staffRole = profile.staffRole;
                    const permissions = staffRole ? (staffRole.permissions || []) : [];

                    navLinks.forEach(function(link) {
                        const requiredPermission = link.getAttribute('data-permission');
                        if (permissions.includes(requiredPermission)) {
                            link.classList.remove('hidden-by-permission');
                        } else {
                            link.classList.add('hidden-by-permission');
                        }
                    });
                }

                document.getElementById('sidebar-nav').classList.add('permissions-loaded');
            } catch (error) {
                console.error('Unable to load permissions:', error);
                document.getElementById('sidebar-nav').classList.add('permissions-loaded');
            }
        }

        applyPermissions();
    </script>
    {{ $scripts ?? '' }}
</body>

</html>
