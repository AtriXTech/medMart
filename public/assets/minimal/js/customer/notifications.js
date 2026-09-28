/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: GET /customer/notifications endpoint, markAsRead/mark-all
    endpoints, the UIModal.alert() error handling on those two actions,
    formatDate(), every existing element ID.
  - NEW: fetchOrderPharmacyMap() — builds an order_id -> pharmacy name
    map from two calls (GET /customer/orders, GET /customer/pharmacies),
    made once per page load, not once per notification. The customer
    OrderResource includes pharmacy_id; GET /customer/pharmacies gives
    the id -> name mapping — same two-step lookup already used on the
    Orders page. LIMITATION: only covers a customer's most recent 100
    orders (per_page cap) — a notification for an older order beyond
    that could still show without a resolved pharmacy name, since
    there's no dedicated endpoint for this and the notification payload
    itself has no pharmacy field at all.
  - CHANGED: buildNotificationMessage() now reconstructs the message
    from the confirmed real {order_id, status} fields instead of
    displaying the backend's opaque message string as-is, so the
    pharmacy name can be inserted in a controlled, predictable spot
    ("Your order #42 at Pharmacy Name is now received") rather than
    string-surgery on text whose exact format isn't guaranteed. Falls
    back to the old data.message/data.title behavior if order_id or
    status is ever missing from a given notification, same as before.
  - NEW: loadNotifications() now dispatches a `notifications-updated`
    custom event with the current unread count every time it (re)loads
    — same pattern as cart.js's `cart-updated` dispatch — so the header
    badge updates immediately after mark-as-read / mark-all-read
    without a page refresh.
*/

const notificationsError = document.getElementById('notifications-error');
const notificationsLoading = document.getElementById('notifications-loading');
const notificationsContent = document.getElementById('notifications-content');
const markAllReadBtn = document.getElementById('mark-all-read');

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString();
}

function humanizeStatus(status) {
    return String(status || '').replace(/_/g, ' ');
}

async function fetchOrderPharmacyMap() {
    try {
        const [ordersResponse, pharmaciesResponse] = await Promise.all([
            CustomerApi.get('/customer/orders?per_page=100'),
            CustomerApi.get('/customer/pharmacies'),
        ]);

        const orders = ordersResponse.data || ordersResponse;
        const pharmacies = pharmaciesResponse.data || pharmaciesResponse;

        const pharmacyNameById = {};
        (pharmacies || []).forEach(function (pharmacy) {
            pharmacyNameById[pharmacy.id] = pharmacy.name;
        });

        const pharmacyNameByOrderId = {};
        (orders || []).forEach(function (order) {
            if (order.pharmacy_id && pharmacyNameById[order.pharmacy_id]) {
                pharmacyNameByOrderId[order.id] = pharmacyNameById[order.pharmacy_id];
            }
        });

        return pharmacyNameByOrderId;
    } catch (error) {
        console.error('Unable to build order-to-pharmacy map:', error);
        return {};
    }
}

function buildNotificationMessage(notification, pharmacyNameByOrderId) {
    const data = notification.data || {};

    if (data.order_id && data.status) {
        const pharmacyName = pharmacyNameByOrderId[data.order_id];
        const pharmacyPart = pharmacyName ? ` at ${pharmacyName}` : '';
        return `Your order #${data.order_id}${pharmacyPart} is now ${humanizeStatus(data.status)}`;
    }

    return data.message || data.title || 'Notification';
}

function renderNotifications(notifications, pharmacyNameByOrderId) {
    if (!notifications || notifications.length === 0) {
        notificationsContent.innerHTML = `
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-6 text-center">
                <i class="ph-light ph-bell text-3xl text-[#171E26]/20"></i>
                <p class="font-inter text-sm text-[#171E26]/50 mt-2">No notifications</p>
            </div>
        `;
        return;
    }

    let html = '';
    notifications.forEach(function(notification) {
        const isUnread = !notification.read_at;
        html += `
            <div class="bg-white rounded-2xl border border-[#EAF1FB] p-4 mb-3 ${isUnread ? 'border-l-[3px] border-l-[#2775E4]' : ''}">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <strong class="font-inter text-[14px] font-semibold text-[#171E26]">${buildNotificationMessage(notification, pharmacyNameByOrderId)}</strong>
                        <div class="font-inter text-[12px] text-[#171E26]/50 mt-1">${formatDate(notification.created_at)}</div>
                    </div>
                    ${isUnread ? `
                        <button type="button" onclick="markAsRead('${notification.id}')"
                                class="flex-shrink-0 rounded-lg border border-[#DBEBFB] px-2.5 py-1.5 font-inter text-[11px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
                            Mark Read
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    });

    notificationsContent.innerHTML = html;
}

async function loadNotifications() {
    if (!CustomerAuth.requireAuth()) return;

    notificationsLoading.style.display = 'block';
    notificationsContent.style.display = 'none';
    notificationsError.style.display = 'none';

    try {
        const [data, pharmacyNameByOrderId] = await Promise.all([
            CustomerApi.get('/customer/notifications?per_page=50'),
            fetchOrderPharmacyMap(),
        ]);

        const notifications = data.data || data;
        renderNotifications(notifications, pharmacyNameByOrderId);

        notificationsLoading.style.display = 'none';
        notificationsContent.style.display = 'block';

        const unreadCount = (notifications || []).filter(function (n) { return !n.read_at; }).length;
        window.dispatchEvent(new CustomEvent('notifications-updated', {
            detail: { count: unreadCount }
        }));
    } catch (error) {
        notificationsLoading.style.display = 'none';
        notificationsError.textContent = error.message || 'Unable to load notifications.';
        notificationsError.style.display = 'flex';
    }
}

window.markAsRead = async function(notificationId) {
    try {
        await CustomerApi.patch(`/customer/notifications/${notificationId}/read`);
        loadNotifications();
    } catch (error) {
        await UIModal.alert(error.message || 'Unable to mark as read.');
    }
};

markAllReadBtn.addEventListener('click', async function() {
    try {
        await CustomerApi.patch('/customer/notifications/read-all');
        loadNotifications();
    } catch (error) {
        await UIModal.alert(error.message || 'Unable to mark all as read.');
    }
});

loadNotifications();