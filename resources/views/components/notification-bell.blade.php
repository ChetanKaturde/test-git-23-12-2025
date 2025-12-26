<div class="nav-item dropdown">
    <a class="nav-link" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="badge badge-danger badge-pill notification-count" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <h6 class="dropdown-header">Recent Notifications</h6>
        <div class="notification-list">
            <!-- Notifications will be loaded here via AJAX -->
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">View All Notifications</a>
    </div>
</div>

<script>
// Load notifications periodically
function loadNotifications() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const countBadge = document.querySelector('.notification-count');
            if (data.count > 0) {
                countBadge.textContent = data.count;
                countBadge.style.display = 'inline';
            } else {
                countBadge.style.display = 'none';
            }
        });
}

// Load notifications on page load and every 30 seconds
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    setInterval(loadNotifications, 30000);
});
</script>