@props(['title' => 'MedMart', 'active' => ''])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · MedMart</title>
    <link rel="stylesheet" href="{{ asset('assets/minimal/css/app.css') }}">
    <style>
        .customer-app {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg);
            position: relative;
            padding-bottom: 70px;
        }
        .customer-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .customer-header .pharmacy-name {
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .customer-content {
            padding: 16px;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: var(--surface);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 100;
        }
        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            font-size: 11px;
            color: var(--text-muted);
            text-decoration: none;
            padding: 4px 12px;
        }
        .bottom-nav a.active {
            color: var(--primary);
            font-weight: 600;
        }
        .bottom-nav a .icon {
            font-size: 20px;
        }
        .switcher-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .switcher-modal.open {
            display: flex;
        }
        .switcher-content {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            width: 100%;
            max-width: 400px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .pharmacy-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 8px;
            cursor: pointer;
        }
        .pharmacy-option.active {
            border-color: var(--primary);
            background: #eff4ff;
        }
        .pharmacy-option .badge {
            display: none;
        }
        .pharmacy-option.active .badge {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="customer-app">
        <header class="customer-header">
            <div class="pharmacy-name" id="pharmacy-switcher">
                <span id="active-pharmacy-name">Select Pharmacy</span>
                <span>⌄</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="/customer/profile" style="color: var(--text); text-decoration: none; font-size: 14px;">Profile</a>
                <button class="btn btn-secondary" id="logout-btn" type="button" style="padding: 6px 12px; font-size: 12px;">Logout</button>
            </div>
        </header>
        
        <div class="customer-content">
            {{ $slot }}
        </div>
        
        <nav class="bottom-nav">
            <a href="/customer/products" class="{{ $active === 'products' ? 'active' : '' }}">
                <span class="icon">🛍️</span>
                <span>Products</span>
            </a>
            <a href="/customer/cart" class="{{ $active === 'cart' ? 'active' : '' }}">
                <span class="icon">🛒</span>
                <span>Cart</span>
            </a>
            <a href="/customer/orders" class="{{ $active === 'orders' ? 'active' : '' }}">
                <span class="icon">📦</span>
                <span>Orders</span>
            </a>
            <a href="/customer/prescriptions" class="{{ $active === 'prescriptions' ? 'active' : '' }}">
                <span class="icon">📋</span>
                <span>Prescriptions</span>
            </a>
            <a href="/customer/notifications" class="{{ $active === 'notifications' ? 'active' : '' }}">
                <span class="icon">🔔</span>
                <span>Alerts</span>
            </a>
        </nav>
    </div>

    <div id="switcher-modal" class="switcher-modal">
        <div class="switcher-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0;">Switch Pharmacy</h3>
                <button type="button" class="close-btn" id="close-switcher-btn">&times;</button>
            </div>
            <div id="pharmacy-list"></div>
            <div style="margin-top: 16px;">
                <a href="/customer/pharmacies/join" class="btn btn-secondary btn-block">Join Another Pharmacy</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/minimal/js/customer/api.js') }}"></script>
    <script src="{{ asset('assets/minimal/js/customer/auth.js') }}"></script>
    <script>
        CustomerAuth.requireAuth();
        
        const customerUser = CustomerApi.getCustomer();
        document.getElementById('logout-btn').addEventListener('click', function() {
            CustomerAuth.logout();
        });
        
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
            container.innerHTML = '';
            
            if (!pharmacies || pharmacies.length === 0) {
                container.innerHTML = '<p class="empty-state">No pharmacies linked yet.</p>';
                return;
            }
            
            pharmacies.forEach(function(pharmacy) {
                const div = document.createElement('div');
                div.className = 'pharmacy-option' + (pharmacy.is_active ? ' active' : '');
                div.innerHTML = `
                    <div>
                        <strong>${pharmacy.name}</strong>
                        <div style="font-size: 12px; color: var(--text-muted);">Linked: ${new Date(pharmacy.linked_at).toLocaleDateString()}</div>
                    </div>
                    <span class="badge badge-success">Active</span>
                `;
                div.onclick = function() {
                    switchPharmacy(pharmacy.id);
                };
                container.appendChild(div);
            });
        }
        
        async function switchPharmacy(pharmacyId) {
            try {
                await CustomerApi.patch('/customer/pharmacies/switch', { pharmacy_id: pharmacyId });
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Unable to switch pharmacy.');
            }
        }
        
        document.getElementById('pharmacy-switcher').addEventListener('click', function() {
            document.getElementById('switcher-modal').classList.add('open');
        });
        
        document.getElementById('close-switcher-btn').addEventListener('click', function() {
            document.getElementById('switcher-modal').classList.remove('open');
        });
        
        loadPharmacies();
    </script>
    {{ $scripts ?? '' }}
</body>
</html>