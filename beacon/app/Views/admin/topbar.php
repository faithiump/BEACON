<?php
/**
 * Admin Top Bar
 */
$pending_organizations = $pending_organizations ?? [];
$unread_count = session()->get('unread_notifications_count') ?? 0;
// Filter to only show unread notifications
$unread_organizations = array_filter($pending_organizations, function($org) {
    return !isset($org['is_viewed']) || !$org['is_viewed'];
});
?>
<div class="admin-topbar">
    <div class="topbar-left">
        <img src="<?= base_url('assets/images/beacon-logo-text-v1.png') ?>" alt="BEACON" class="topbar-title-image" style="height: 30px;">
    </div>
    
    <div class="topbar-right">
        <div class="notification-wrapper">
            <button class="topbar-icon-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                <?php if ($unread_count > 0): ?>
                    <span class="icon-badge" id="notificationBadge"><?= $unread_count ?></span>
                <?php endif; ?>
            </button>
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <span class="notification-count" id="notificationCount"><?= $unread_count ?> new</span>
                </div>
                <div class="notification-list">
                    <?php if (!empty($pending_organizations)): ?>
                        <?php foreach (array_slice($pending_organizations, 0, 5) as $org): ?>
                            <?php $isUnread = !isset($org['is_viewed']) || !$org['is_viewed']; ?>
                            <div class="notification-item <?= $isUnread ? 'unread' : '' ?>" 
                                 data-org-id="<?= $org['id'] ?>"
                                 onclick="markNotificationRead(<?= $org['id'] ?>); window.location.href='<?= base_url('admin/organizations/pending/view/' . $org['id']) ?>'" 
                                 style="cursor: pointer;">
                                <div class="notification-icon organization">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-title">Organization Pending Approval</p>
                                    <p class="notification-text"><?= esc($org['name']) ?></p>
                                    <span class="notification-time"><?= esc($org['submitted_at']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="notification-empty">No notifications</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>" type="text/css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Notification dropdown
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
        }
    });
});

// Mark notification as read
function markNotificationRead(orgId) {
    // Mark as read via AJAX
    fetch('<?= base_url('admin/notifications/mark-read') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'org_id=' + orgId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove unread class from notification item
            const notifItem = document.querySelector(`.notification-item[data-org-id="${orgId}"]`);
            if (notifItem) {
                notifItem.classList.remove('unread');
            }
            
            // Update badge count
            updateNotificationBadge();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Update notification badge count
function updateNotificationBadge() {
    const unreadItems = document.querySelectorAll('.notification-item.unread');
    const badge = document.getElementById('notificationBadge');
    const countSpan = document.getElementById('notificationCount');
    const unreadCount = unreadItems.length;
    
    if (unreadCount > 0) {
        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = 'flex';
        }
        if (countSpan) {
            countSpan.textContent = unreadCount + ' new';
        }
    } else {
        if (badge) {
            badge.style.display = 'none';
        }
        if (countSpan) {
            countSpan.textContent = '0 new';
        }
    }
}
</script>

