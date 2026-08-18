const notificationsError = document.getElementById('notifications-error');
const notificationsLoading = document.getElementById('notifications-loading');
const notificationsContent = document.getElementById('notifications-content');
const markAllReadBtn = document.getElementById('mark-all-read');

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString();
}

function renderNotifications(notifications) {
    if (!notifications || notifications.length === 0) {
        notificationsContent.innerHTML = `
            <div class="card">
                <div class="empty-state">No notifications</div>
            </div>
        `;
        return;
    }
    
    let html = '';
    notifications.forEach(function(notification) {
        html += `
            <div class="card" style="margin-bottom: 12px; ${notification.read_at ? '' : 'border-left: 3px solid var(--primary);'}">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <strong>${notification.data?.message || notification.data?.title || 'Notification'}</strong>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">${formatDate(notification.created_at)}</div>
                    </div>
                    ${!notification.read_at ? `
                        <button class="btn btn-secondary" onclick="markAsRead('${notification.id}')" style="font-size: 11px; padding: 4px 8px;">Mark Read</button>
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
        const data = await CustomerApi.get('/customer/notifications?per_page=50');
        renderNotifications(data.data || data);
        notificationsLoading.style.display = 'none';
        notificationsContent.style.display = 'block';
    } catch (error) {
        notificationsLoading.style.display = 'none';
        notificationsError.textContent = error.message || 'Unable to load notifications.';
        notificationsError.style.display = 'block';
    }
}

window.markAsRead = async function(notificationId) {
    try {
        await CustomerApi.patch(`/customer/notifications/${notificationId}/read`);
        loadNotifications();
    } catch (error) {
        alert(error.message || 'Unable to mark as read.');
    }
};

markAllReadBtn.addEventListener('click', async function() {
    try {
        await CustomerApi.patch('/customer/notifications/read-all');
        loadNotifications();
    } catch (error) {
        alert(error.message || 'Unable to mark all as read.');
    }
});

loadNotifications();