<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Organization Dashboard' ?> - BEACON</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization.css') ?>">
</head>
<body>
    <!-- Top Navigation -->
    <header class="top-nav">
        <div class="nav-container">
            <!-- Logo -->
            <a href="<?= base_url('organization/dashboard') ?>" class="nav-brand">
                <div class="logo-icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <span class="logo-text">BEACON</span>
            </a>

            <!-- Main Navigation -->
            <nav class="nav-menu">
                <a href="#overview" class="nav-link active" data-section="overview">
                    <i class="fas fa-th-large"></i>
                    <span>Overview</span>
                </a>
                <a href="#events" class="nav-link" data-section="events">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                </a>
                <a href="#announcements" class="nav-link" data-section="announcements">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                <a href="#members" class="nav-link" data-section="members">
                    <i class="fas fa-users"></i>
                    <span>Members</span>
                    <?php if(($stats['pending_members'] ?? 0) > 0): ?>
                    <span class="nav-badge warning"><?= $stats['pending_members'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#products" class="nav-link" data-section="products">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="#payments" class="nav-link" data-section="payments">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                    <?php if(($stats['pending_payments'] ?? 0) > 0): ?>
                    <span class="nav-badge warning"><?= $stats['pending_payments'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#forum" class="nav-link" data-section="forum">
                    <i class="fas fa-comments"></i>
                    <span>Forum</span>
                </a>
            </nav>

            <!-- Right Side Actions -->
            <div class="nav-actions">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="globalSearch" placeholder="Search...">
                </div>
                
                <!-- Quick Actions Dropdown -->
                <div class="quick-actions-wrapper">
                    <button class="quick-actions-btn" id="quickActionsBtn">
                        <i class="fas fa-plus"></i>
                        <span class="quick-actions-label">Create</span>
                    </button>
                    <div class="quick-actions-dropdown" id="quickActionsDropdown">
                        <div class="quick-actions-header">
                            <h4>Quick Actions</h4>
                        </div>
                        <div class="quick-actions-list">
                            <button class="quick-action-item" onclick="openModal('eventModal')">
                                <div class="quick-action-icon event">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="quick-action-text">
                                    <span class="quick-action-label">New Event</span>
                                    <span class="quick-action-desc">Create an event</span>
                                </div>
                            </button>
                            <button class="quick-action-item" onclick="openModal('announcementModal')">
                                <div class="quick-action-icon announcement">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="quick-action-text">
                                    <span class="quick-action-label">New Announcement</span>
                                    <span class="quick-action-desc">Post an update</span>
                                </div>
                            </button>
                            <button class="quick-action-item" onclick="openModal('productModal')">
                                <div class="quick-action-icon product">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="quick-action-text">
                                    <span class="quick-action-label">New Product</span>
                                    <span class="quick-action-desc">Add merchandise</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="notification-wrapper">
                    <button class="action-btn notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="action-badge" id="notificationCount">3</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            <button class="mark-all-read" id="markAllRead">Mark all read</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-item unread" data-id="1">
                                <div class="notif-icon payment">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">New Payment Submitted</p>
                                    <p class="notif-text">John Dela Cruz submitted payment for CSS T-Shirt</p>
                                    <span class="notif-time"><i class="fas fa-clock"></i> 5 min ago</span>
                                </div>
                            </div>
                            <div class="notification-item unread" data-id="2">
                                <div class="notif-icon member">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">New Member Request</p>
                                    <p class="notif-text">Anna Reyes wants to join your organization</p>
                                    <span class="notif-time"><i class="fas fa-clock"></i> 1 hour ago</span>
                                </div>
                            </div>
                            <div class="notification-item" data-id="3">
                                <div class="notif-icon event">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">Event Reminder</p>
                                    <p class="notif-text">Tech Innovation Summit is in 3 days</p>
                                    <span class="notif-time"><i class="fas fa-clock"></i> 2 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-btn" id="userBtn">
                        <?php if(!empty($organization['photo'])): ?>
                            <img src="<?= $organization['photo'] ?>" alt="Organization" class="user-avatar-img">
                        <?php else: ?>
                            <div class="user-avatar">
                                <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                        <span class="user-name"><?= $organization['acronym'] ?? 'Organization' ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">
                                <?php if(!empty($organization['photo'])): ?>
                                    <img src="<?= $organization['photo'] ?>" alt="Organization" id="dropdownAvatarImg" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                                <?php endif; ?>
                            </div>
                            <div class="dropdown-info">
                                <span class="dropdown-name"><?= $organization['name'] ?? 'Organization' ?></span>
                                <span class="dropdown-role"><?= ucfirst($organization['type'] ?? 'Organization') ?></span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="#settings" class="dropdown-item" data-section="settings">
                            <i class="fas fa-cog"></i> Organization Settings
                        </a>
                        <a href="#reports" class="dropdown-item" data-section="reports">
                            <i class="fas fa-chart-bar"></i> Reports & Analytics
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('organization/logout') ?>" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    <aside class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <div class="mobile-brand">
                <div class="logo-icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <span>BEACON</span>
            </div>
            <button class="close-mobile-nav" id="closeMobileNav">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mobile-nav-menu">
            <a href="#overview" class="mobile-nav-link active" data-section="overview">
                <i class="fas fa-th-large"></i> Overview
            </a>
            <a href="#events" class="mobile-nav-link" data-section="events">
                <i class="fas fa-calendar-alt"></i> Events
            </a>
            <a href="#announcements" class="mobile-nav-link" data-section="announcements">
                <i class="fas fa-bullhorn"></i> Announcements
            </a>
            <a href="#members" class="mobile-nav-link" data-section="members">
                <i class="fas fa-users"></i> Members
                <?php if(($stats['pending_members'] ?? 0) > 0): ?>
                <span class="nav-badge warning"><?= $stats['pending_members'] ?></span>
                <?php endif; ?>
            </a>
            <a href="#products" class="mobile-nav-link" data-section="products">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="#payments" class="mobile-nav-link" data-section="payments">
                <i class="fas fa-credit-card"></i> Payments
                <?php if(($stats['pending_payments'] ?? 0) > 0): ?>
                <span class="nav-badge warning"><?= $stats['pending_payments'] ?></span>
                <?php endif; ?>
            </a>
            <a href="#forum" class="mobile-nav-link" data-section="forum">
                <i class="fas fa-comments"></i> Forum
            </a>
            <div class="mobile-nav-divider"></div>
            <a href="#settings" class="mobile-nav-link" data-section="settings">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="#reports" class="mobile-nav-link" data-section="reports">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
            <a href="<?= base_url('organization/logout') ?>" class="mobile-nav-link logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-area">
            
            <!-- Overview Section - Facebook Style Feed -->
            <section id="overview" class="dashboard-section active">
                <div class="feed-layout">
                    <!-- Left Sidebar -->
                    <aside class="feed-sidebar-left">
                        <!-- Profile Card -->
                        <div class="profile-card">
                            <div class="profile-cover">
                                <div class="profile-cover-gradient"></div>
                            </div>
                            <div class="profile-info">
                                <div class="profile-avatar-large">
                                    <?php if(!empty($organization['photo'])): ?>
                                        <img src="<?= $organization['photo'] ?>" alt="<?= $organization['name'] ?>">
                                    <?php else: ?>
                                        <div class="avatar-placeholder">
                                            <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h2 class="profile-name"><?= $organization['name'] ?? 'Organization Name' ?></h2>
                                <span class="profile-acronym"><?= $organization['acronym'] ?? 'ORG' ?></span>
                                <p class="profile-type">
                                    <i class="fas fa-tag"></i> 
                                    <?= ucfirst(str_replace('_', ' ', $organization['type'] ?? 'Organization')) ?>
                                </p>
                            </div>
                            <div class="profile-stats-row">
                                <div class="profile-stat">
                                    <span class="stat-num"><?= $stats['total_members'] ?? 0 ?></span>
                                    <span class="stat-text">MEMBERS</span>
                                </div>
                                <div class="profile-stat">
                                    <span class="stat-num"><?= $stats['total_events'] ?? 0 ?></span>
                                    <span class="stat-text">EVENTS</span>
                                </div>
                                <div class="profile-stat">
                                    <span class="stat-num"><?= $stats['total_products'] ?? 0 ?></span>
                                    <span class="stat-text">PRODUCTS</span>
                                </div>
                            </div>
                            <div class="profile-actions">
                                <button class="btn btn-outline-primary btn-sm" onclick="switchSection('settings')">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </button>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="sidebar-card">
                            <h4 class="sidebar-title"><i class="fas fa-chart-line"></i> Quick Stats</h4>
                            <div class="quick-stat-item">
                                <div class="qs-icon emerald"><i class="fas fa-peso-sign"></i></div>
                                <div class="qs-info">
                                    <span class="qs-value">₱<?= number_format($stats['total_revenue'] ?? 0) ?></span>
                                    <span class="qs-label">Total Revenue</span>
                                </div>
                            </div>
                            <div class="quick-stat-item">
                                <div class="qs-icon amber"><i class="fas fa-clock"></i></div>
                                <div class="qs-info">
                                    <span class="qs-value"><?= $stats['pending_payments'] ?? 0 ?></span>
                                    <span class="qs-label">Pending Payments</span>
                                </div>
                            </div>
                            <div class="quick-stat-item">
                                <div class="qs-icon purple"><i class="fas fa-user-plus"></i></div>
                                <div class="qs-info">
                                    <span class="qs-value"><?= $stats['pending_members'] ?? 0 ?></span>
                                    <span class="qs-label">Member Requests</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="sidebar-card">
                            <h4 class="sidebar-title"><i class="fas fa-calendar-alt"></i> Upcoming Events</h4>
                            <?php if(!empty($recentEvents)): ?>
                                <?php foreach(array_slice($recentEvents, 0, 2) as $event): ?>
                                <div class="sidebar-event">
                                    <div class="se-date">
                                        <span class="se-day"><?= date('d', strtotime($event['date'])) ?></span>
                                        <span class="se-month"><?= date('M', strtotime($event['date'])) ?></span>
                                    </div>
                                    <div class="se-info">
                                        <span class="se-title"><?= esc($event['title']) ?></span>
                                        <span class="se-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="sidebar-empty">No upcoming events</p>
                            <?php endif; ?>
                            <a href="#events" class="sidebar-link" onclick="switchSection('events')">View all events <i class="fas fa-arrow-right"></i></a>
                        </div>

                        <!-- Recent Announcements -->
                        <div class="sidebar-card">
                            <h4 class="sidebar-title"><i class="fas fa-bullhorn"></i> Recent Announcements</h4>
                            <?php if(!empty($recentAnnouncements)): ?>
                                <?php foreach(array_slice($recentAnnouncements, 0, 2) as $announcement): ?>
                                <div class="sidebar-announcement">
                                    <div class="sa-icon <?= $announcement['priority'] === 'high' ? 'high' : 'normal' ?>">
                                        <i class="fas fa-<?= $announcement['priority'] === 'high' ? 'exclamation-circle' : 'info-circle' ?>"></i>
                                    </div>
                                    <div class="sa-info">
                                        <span class="sa-title"><?= esc($announcement['title']) ?></span>
                                        <span class="sa-date"><i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($announcement['created_at'])) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="sidebar-empty">No announcements yet</p>
                            <?php endif; ?>
                            <a href="#announcements" class="sidebar-link" onclick="switchSection('announcements')">View all announcements <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </aside>

                    <!-- Center Feed -->
                    <div class="feed-main">
                        <!-- Create Post Box -->
                        <div class="create-post-card">
                            <div class="create-post-header">
                                <div class="post-avatar">
                                    <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                                </div>
                                <button class="create-post-input" onclick="openModal('announcementModal')">
                                    What's on your mind, <?= $organization['acronym'] ?? 'Organization' ?>?
                                </button>
                            </div>
                            <div class="create-post-actions">
                                <button class="post-action-btn" onclick="openModal('eventModal')">
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                    <span>Event</span>
                                </button>
                                <button class="post-action-btn" onclick="openModal('announcementModal')">
                                    <i class="fas fa-bullhorn text-warning"></i>
                                    <span>Announcement</span>
                                </button>
                                <button class="post-action-btn" onclick="openModal('productModal')">
                                    <i class="fas fa-box text-purple"></i>
                                    <span>Product</span>
                                </button>
                            </div>
                        </div>

                        <!-- Feed Posts (Announcements) -->
                        <?php if(!empty($recentAnnouncements)): ?>
                            <?php foreach($recentAnnouncements as $announcement): ?>
                            <div class="feed-post">
                                <div class="post-header">
                                    <div class="post-author-avatar">
                                        <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                                    </div>
                                    <div class="post-author-info">
                                        <span class="post-author-name"><?= $organization['name'] ?? 'Organization' ?></span>
                                        <span class="post-time">
                                            <i class="fas fa-clock"></i> <?= date('M d, Y \a\t g:i A', strtotime($announcement['created_at'])) ?>
                                            <?php if($announcement['priority'] === 'high'): ?>
                                            <span class="post-priority high"><i class="fas fa-exclamation-circle"></i> Important</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="post-menu">
                                        <button class="post-menu-btn" onclick="togglePostMenu(<?= $announcement['id'] ?>)">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="post-menu-dropdown" id="postMenu<?= $announcement['id'] ?>">
                                            <button onclick="editAnnouncement(<?= $announcement['id'] ?>)"><i class="fas fa-edit"></i> Edit</button>
                                            <button onclick="deleteAnnouncement(<?= $announcement['id'] ?>)"><i class="fas fa-trash"></i> Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <h3 class="post-title"><?= esc($announcement['title']) ?></h3>
                                    <p class="post-text"><?= nl2br(esc($announcement['content'])) ?></p>
                                </div>
                                <div class="post-stats">
                                    <span><i class="fas fa-eye"></i> <?= $announcement['views'] ?? 0 ?> views</span>
                                </div>
                                <div class="post-actions">
                                    <button class="post-action"><i class="far fa-thumbs-up"></i> Like</button>
                                    <button class="post-action"><i class="far fa-comment"></i> Comment</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="feed-empty">
                                <i class="fas fa-newspaper"></i>
                                <h3>No posts yet</h3>
                                <p>Create your first announcement to share with your members!</p>
                                <button class="btn btn-primary" onclick="openModal('announcementModal')">
                                    <i class="fas fa-plus"></i> Create Announcement
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- Recent Events as Posts -->
                        <?php if(!empty($recentEvents)): ?>
                            <?php foreach(array_slice($recentEvents, 0, 2) as $event): ?>
                            <div class="feed-post event-post">
                                <div class="post-header">
                                    <div class="post-author-avatar event-avatar">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="post-author-info">
                                        <span class="post-author-name"><?= $organization['name'] ?? 'Organization' ?> created an event</span>
                                        <span class="post-time">
                                            <i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($event['created_at'] ?? $event['date'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="event-card-content">
                                    <div class="event-banner">
                                        <div class="event-banner-overlay">
                                            <div class="event-date-badge">
                                                <span class="edb-day"><?= date('d', strtotime($event['date'])) ?></span>
                                                <span class="edb-month"><?= date('M', strtotime($event['date'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="event-card-info">
                                        <h3><?= esc($event['title']) ?></h3>
                                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></p>
                                        <p class="event-attendees"><i class="fas fa-users"></i> <?= $event['attendees'] ?? 0 ?> going</p>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <button class="post-action"><i class="fas fa-star"></i> Interested</button>
                                    <button class="post-action"><i class="fas fa-check"></i> Going</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Right Sidebar - Products/Marketplace -->
                    <aside class="feed-sidebar-right">
                        <!-- Products Section -->
                        <div class="sidebar-card marketplace-card">
                            <div class="sidebar-header">
                                <h4 class="sidebar-title"><i class="fas fa-store"></i> Our Products</h4>
                                <button class="btn btn-sm btn-primary" onclick="openModal('productModal')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="product-grid">
                                <?php if(!empty($products)): ?>
                                    <?php foreach(array_slice($products, 0, 4) as $product): ?>
                                    <div class="product-mini-card">
                                        <div class="product-mini-img">
                                            <?php if(!empty($product['image'])): ?>
                                                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>">
                                            <?php else: ?>
                                                <div class="product-placeholder"><i class="fas fa-box"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-mini-info">
                                            <span class="product-mini-name"><?= esc($product['name']) ?></span>
                                            <span class="product-mini-price">₱<?= number_format($product['price']) ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="sidebar-empty-full">
                                        <i class="fas fa-box-open"></i>
                                        <p>No products yet</p>
                                        <button class="btn btn-sm btn-outline-primary" onclick="openModal('productModal')">Add Product</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if(!empty($products)): ?>
                            <a href="#products" class="sidebar-link" onclick="switchSection('products')">View all products <i class="fas fa-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>

                        <!-- Member Requests -->
                        <div class="sidebar-card">
                            <h4 class="sidebar-title"><i class="fas fa-user-plus"></i> Member Requests</h4>
                            <?php 
                            $pendingMembers = array_filter($recentMembers ?? [], fn($m) => $m['status'] === 'pending');
                            if(!empty($pendingMembers)): ?>
                                <?php foreach(array_slice($pendingMembers, 0, 3) as $member): ?>
                                <div class="member-request-item">
                                    <div class="mr-avatar">
                                        <?= strtoupper(substr($member['name'], 0, 1)) ?>
                                    </div>
                                    <div class="mr-info">
                                        <span class="mr-name"><?= esc($member['name']) ?></span>
                                        <span class="mr-course"><?= esc($member['course']) ?></span>
                                    </div>
                                    <div class="mr-actions">
                                        <button class="mr-btn approve" onclick="manageMember(<?= $member['id'] ?>, 'approve')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="mr-btn reject" onclick="manageMember(<?= $member['id'] ?>, 'reject')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="sidebar-empty">No pending requests</p>
                            <?php endif; ?>
                            <a href="#members" class="sidebar-link" onclick="switchSection('members')">Manage members <i class="fas fa-arrow-right"></i></a>
                        </div>

                        <!-- Pending Payments -->
                        <div class="sidebar-card">
                            <h4 class="sidebar-title"><i class="fas fa-credit-card"></i> Pending Payments</h4>
                            <?php if(!empty($pendingPayments)): ?>
                                <?php foreach(array_slice($pendingPayments, 0, 3) as $payment): ?>
                                <div class="payment-request-item">
                                    <div class="pr-avatar">
                                        <?= strtoupper(substr($payment['student_name'], 0, 1)) ?>
                                    </div>
                                    <div class="pr-info">
                                        <span class="pr-name"><?= esc($payment['student_name']) ?></span>
                                        <span class="pr-product"><?= esc($payment['product']) ?></span>
                                    </div>
                                    <span class="pr-amount">₱<?= number_format($payment['amount']) ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="sidebar-empty">No pending payments</p>
                            <?php endif; ?>
                            <a href="#payments" class="sidebar-link" onclick="switchSection('payments')">View all payments <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </aside>
                </div>
            </section>

            <!-- Events Section -->
            <section id="events" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Events Management</h1>
                        <p class="section-subtitle">Create and manage your organization's events</p>
                    </div>
                    <button class="btn btn-primary" onclick="openModal('eventModal')">
                        <i class="fas fa-plus"></i> New Event
                    </button>
                </div>

                <div class="events-grid">
                    <?php if(!empty($recentEvents)): ?>
                        <?php foreach($recentEvents as $event): ?>
                        <div class="event-card">
                            <div class="event-card-image">
                                <?php if(!empty($event['image'])): ?>
                                    <img src="<?= base_url('uploads/events/' . $event['image']) ?>" alt="<?= esc($event['title']) ?>">
                                <?php else: ?>
                                    <div class="event-placeholder">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="event-status <?= $event['status'] ?>"><?= ucfirst($event['status']) ?></span>
                            </div>
                            <div class="event-card-body">
                                <h3><?= esc($event['title']) ?></h3>
                                <p class="event-description"><?= esc(substr($event['description'], 0, 100)) ?>...</p>
                                <div class="event-info">
                                    <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($event['date'])) ?></span>
                                    <span><i class="fas fa-clock"></i> <?= $event['time'] ?></span>
                                    <span><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></span>
                                </div>
                                <?php if(!empty($event['max_attendees'])): ?>
                                <div class="event-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= min(100, ($event['attendees'] / $event['max_attendees']) * 100) ?>%"></div>
                                    </div>
                                    <span class="progress-text"><?= $event['attendees'] ?>/<?= $event['max_attendees'] ?> registered</span>
                                </div>
                                <?php else: ?>
                                <div class="event-progress">
                                    <span class="progress-text"><?= $event['attendees'] ?> registered (Unlimited capacity)</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="event-card-footer">
                                <button class="btn btn-outline" onclick="viewEventAttendees(<?= $event['id'] ?>)">
                                    <i class="fas fa-users"></i> Attendees
                                </button>
                                <button class="btn btn-primary" onclick="editEvent(<?= $event['id'] ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state-large">
                            <i class="fas fa-calendar-plus"></i>
                            <h3>No Events Yet</h3>
                            <p>Create your first event to engage with your members</p>
                            <button class="btn btn-primary" onclick="openModal('eventModal')">Create Event</button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Announcements Section -->
            <section id="announcements" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Announcements</h1>
                        <p class="section-subtitle">Keep your members informed with important updates</p>
                    </div>
                    <button class="btn btn-primary" onclick="openModal('announcementModal')">
                        <i class="fas fa-plus"></i> New Announcement
                    </button>
                </div>

                <div class="announcements-grid">
                    <?php if(!empty($recentAnnouncements)): ?>
                        <?php foreach($recentAnnouncements as $announcement): ?>
                        <div class="announcement-card <?= $announcement['priority'] ?>">
                            <div class="announcement-card-header">
                                <span class="priority-badge <?= $announcement['priority'] ?>">
                                    <i class="fas fa-<?= $announcement['priority'] === 'high' ? 'exclamation-circle' : 'info-circle' ?>"></i>
                                    <?= ucfirst($announcement['priority']) ?> Priority
                                </span>
                                <span class="announcement-date"><?= date('M d, Y', strtotime($announcement['created_at'])) ?></span>
                            </div>
                            <h3><?= esc($announcement['title']) ?></h3>
                            <p><?= esc($announcement['content']) ?></p>
                            <div class="announcement-card-footer">
                                <span class="views"><i class="fas fa-eye"></i> <?= $announcement['views'] ?> views</span>
                                <div class="announcement-actions">
                                    <button class="btn-icon" onclick="editAnnouncement(<?= $announcement['id'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon danger" onclick="deleteAnnouncement(<?= $announcement['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state-large">
                            <i class="fas fa-bullhorn"></i>
                            <h3>No Announcements</h3>
                            <p>Create announcements to keep members updated</p>
                            <button class="btn btn-primary" onclick="openModal('announcementModal')">Create Announcement</button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Members Section -->
            <section id="members" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Members Management</h1>
                        <p class="section-subtitle">Manage your organization's members</p>
                    </div>
                    <div class="section-tabs">
                        <button class="tab-btn active" data-tab="all-members">All Members</button>
                        <button class="tab-btn" data-tab="pending-members">Pending <span class="badge"><?= $stats['pending_members'] ?? 0 ?></span></button>
                    </div>
                </div>

                <div class="members-table-container">
                    <table class="data-table" id="membersTable">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Student ID</th>
                                <th>Course & Year</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recentMembers)): ?>
                                <?php foreach($recentMembers as $member): ?>
                                <tr data-status="<?= $member['status'] ?>">
                                    <td>
                                        <div class="member-cell">
                                            <div class="member-avatar small"><?= strtoupper(substr($member['name'], 0, 1)) ?></div>
                                            <div>
                                                <strong><?= esc($member['name']) ?></strong>
                                                <span><?= esc($member['email']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($member['student_id']) ?></td>
                                    <td><?= esc($member['course']) ?> - <?= $member['year'] ?></td>
                                    <td>
                                        <span class="status-badge <?= $member['status'] ?>"><?= ucfirst($member['status']) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($member['applied_at'])) ?></td>
                                    <td>
                                        <?php if($member['status'] === 'pending'): ?>
                                        <div class="action-buttons">
                                            <button class="btn-icon success" onclick="manageMember(<?= $member['id'] ?>, 'approve')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-icon danger" onclick="manageMember(<?= $member['id'] ?>, 'reject')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <?php else: ?>
                                        <button class="btn-icon danger" onclick="manageMember(<?= $member['id'] ?>, 'remove')">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Products Section -->
            <section id="products" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Products & Merchandise</h1>
                        <p class="section-subtitle">Manage your organization's products and inventory</p>
                    </div>
                    <button class="btn btn-primary" onclick="openModal('productModal')">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>

                <div class="products-grid" id="productsGrid">
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $product): ?>
                        <div class="product-card <?= $product['status'] === 'out_of_stock' ? 'out-of-stock' : '' ?>">
                            <div class="product-image">
                                <?php if(!empty($product['image'])): ?>
                                    <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>">
                                <?php else: ?>
                                    <div class="product-placeholder">
                                        <i class="fas fa-box"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="stock-badge <?= $product['status'] === 'out_of_stock' ? 'out' : ($product['status'] === 'low_stock' ? 'low' : 'available') ?>">
                                    <?= $product['status'] === 'out_of_stock' ? 'Out of Stock' : ($product['status'] === 'low_stock' ? 'Low Stock' : 'In Stock') ?>
                                </span>
                            </div>
                            <div class="product-body">
                                <h3><?= esc($product['name']) ?></h3>
                                <p class="product-desc"><?= esc($product['description'] ?: 'No description') ?></p>
                                <div class="product-meta">
                                    <span class="price">₱<?= number_format($product['price'], 2) ?></span>
                                    <span class="stock <?= $product['stock'] == 0 ? 'danger' : ($product['stock'] <= 10 ? 'warning' : '') ?>">Stock: <?= $product['stock'] ?></span>
                                </div>
                                <div class="product-stats">
                                    <span><i class="fas fa-shopping-cart"></i> <?= $product['sold'] ?> sold</span>
                                </div>
                            </div>
                            <div class="product-footer">
                                <button class="btn btn-outline" onclick="updateStock(<?= $product['id'] ?>)">
                                    <i class="fas fa-boxes"></i> <?= $product['stock'] == 0 ? 'Restock' : 'Update Stock' ?>
                                </button>
                                <button class="btn btn-primary" onclick="editProduct(<?= $product['id'] ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state-large">
                            <i class="fas fa-box-open"></i>
                            <h3>No Products Yet</h3>
                            <p>Create your first product to start selling merchandise</p>
                            <button class="btn btn-primary" onclick="openModal('productModal')">Add Product</button>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Payments Section -->
            <section id="payments" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Payment Management</h1>
                        <p class="section-subtitle">Review and confirm member payments</p>
                    </div>
                    <div class="section-tabs">
                        <button class="tab-btn active" data-tab="pending-payments">Pending <span class="badge"><?= $stats['pending_payments'] ?? 0 ?></span></button>
                        <button class="tab-btn" data-tab="confirmed-payments">Confirmed</button>
                    </div>
                </div>

                <div class="payments-grid">
                    <?php if(!empty($pendingPayments)): ?>
                        <?php foreach($pendingPayments as $payment): ?>
                        <div class="payment-card">
                            <div class="payment-card-header">
                                <div class="payer-info">
                                    <div class="payer-avatar"><?= strtoupper(substr($payment['student_name'], 0, 1)) ?></div>
                                    <div>
                                        <h4><?= esc($payment['student_name']) ?></h4>
                                        <span><?= esc($payment['student_id']) ?></span>
                                    </div>
                                </div>
                                <span class="payment-time"><?= date('M d, g:i A', strtotime($payment['submitted_at'])) ?></span>
                            </div>
                            <div class="payment-card-body">
                                <div class="payment-product">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span><?= esc($payment['product']) ?></span>
                                </div>
                                <div class="payment-amount-large">₱<?= number_format($payment['amount']) ?></div>
                                <?php if(!empty($payment['proof_image'])): ?>
                                <div class="payment-proof">
                                    <img src="<?= base_url('uploads/payments/' . $payment['proof_image']) ?>" alt="Payment Proof" onclick="viewPaymentProof('<?= $payment['proof_image'] ?>')">
                                    <span>Click to view proof</span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="payment-card-footer">
                                <button class="btn btn-outline danger" onclick="confirmPayment(<?= $payment['id'] ?>, 'reject')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <button class="btn btn-primary" onclick="confirmPayment(<?= $payment['id'] ?>, 'approve')">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state-large">
                            <i class="fas fa-check-circle"></i>
                            <h3>All Caught Up!</h3>
                            <p>No pending payments to review</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Forum Section -->
            <section id="forum" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Community Forum</h1>
                        <p class="section-subtitle">Engage with students and other organizations</p>
                    </div>
                    <button class="btn-primary" onclick="openCreatePostModal()">
                        <i class="fas fa-plus"></i> New Post
                    </button>
                </div>

                <div class="forum-layout">
                    <!-- Forum Sidebar -->
                    <aside class="forum-sidebar">
                        <div class="forum-categories-card">
                            <h4 class="forum-sidebar-title"><i class="fas fa-folder"></i> Categories</h4>
                            <ul class="forum-category-list">
                                <li class="forum-category-item active" data-category="all">
                                    <i class="fas fa-globe"></i>
                                    <span>All Posts</span>
                                    <span class="category-count">24</span>
                                </li>
                                <li class="forum-category-item" data-category="announcements">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Our Announcements</span>
                                    <span class="category-count">5</span>
                                </li>
                                <li class="forum-category-item" data-category="events">
                                    <i class="fas fa-calendar-star"></i>
                                    <span>Events & Activities</span>
                                    <span class="category-count">8</span>
                                </li>
                                <li class="forum-category-item" data-category="products">
                                    <i class="fas fa-store"></i>
                                    <span>Product Updates</span>
                                    <span class="category-count">4</span>
                                </li>
                                <li class="forum-category-item" data-category="members">
                                    <i class="fas fa-users"></i>
                                    <span>Member Discussions</span>
                                    <span class="category-count">7</span>
                                </li>
                            </ul>
                        </div>

                        <div class="forum-stats-card">
                            <h4 class="forum-sidebar-title"><i class="fas fa-chart-bar"></i> Forum Stats</h4>
                            <div class="forum-stats-grid">
                                <div class="forum-stat-item">
                                    <span class="forum-stat-num">156</span>
                                    <span class="forum-stat-label">Total Posts</span>
                                </div>
                                <div class="forum-stat-item">
                                    <span class="forum-stat-num">48</span>
                                    <span class="forum-stat-label">Your Posts</span>
                                </div>
                                <div class="forum-stat-item">
                                    <span class="forum-stat-num">324</span>
                                    <span class="forum-stat-label">Engagements</span>
                                </div>
                                <div class="forum-stat-item">
                                    <span class="forum-stat-num">89</span>
                                    <span class="forum-stat-label">Followers</span>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- Forum Main Content -->
                    <div class="forum-main">
                        <!-- Create Post Box -->
                        <div class="forum-create-box">
                            <div class="create-box-avatar">
                                <?php if(!empty($organization['photo'])): ?>
                                    <img src="<?= esc($organization['photo']) ?>" alt="<?= esc($organization['name']) ?>">
                                <?php else: ?>
                                    <div class="avatar-placeholder-sm"><?= strtoupper(substr($organization['acronym'] ?? 'O', 0, 2)) ?></div>
                                <?php endif; ?>
                            </div>
                            <input type="text" class="create-box-input" placeholder="Share an update or announcement..." onclick="openCreatePostModal()">
                            <button class="create-box-btn" onclick="openCreatePostModal()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>

                        <!-- Forum Filter Bar -->
                        <div class="forum-filter-bar">
                            <div class="forum-tabs">
                                <button class="forum-tab active" data-filter="all">
                                    <i class="fas fa-stream"></i> All Posts
                                </button>
                                <button class="forum-tab" data-filter="yours">
                                    <i class="fas fa-user"></i> Your Posts
                                </button>
                                <button class="forum-tab" data-filter="popular">
                                    <i class="fas fa-fire-alt"></i> Popular
                                </button>
                            </div>
                            <div class="forum-search">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search posts...">
                            </div>
                        </div>

                        <!-- Forum Posts -->
                        <div class="forum-posts-list">
                            <!-- Organization's Own Post -->
                            <article class="forum-post own-post">
                                <div class="post-vote">
                                    <button class="vote-btn upvote active">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <span class="vote-count">67</span>
                                    <button class="vote-btn downvote">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="post-content">
                                    <div class="post-header">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($organization['acronym'] ?? 'ORG') ?>&background=8b5cf6&color=fff" alt="<?= esc($organization['name'] ?? 'Organization') ?>" class="post-author-img">
                                        <div class="post-meta">
                                            <div class="post-author">
                                                <span class="author-name"><?= esc($organization['name'] ?? 'Your Organization') ?></span>
                                                <span class="author-badge org">Your Post</span>
                                            </div>
                                            <span class="post-time">3 hours ago • <span class="post-category">Announcements</span></span>
                                        </div>
                                        <div class="post-actions-menu">
                                            <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                            <div class="post-menu-dropdown">
                                                <button><i class="fas fa-edit"></i> Edit Post</button>
                                                <button><i class="fas fa-pin"></i> Pin Post</button>
                                                <button class="danger"><i class="fas fa-trash"></i> Delete Post</button>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="post-title">📢 Membership Drive - Join Our Organization!</h3>
                                    <p class="post-body">
                                        We're excited to announce our membership drive for the new semester! 
                                        If you're passionate about making a difference and growing with a dynamic team, 
                                        we'd love to have you join us. Benefits include exclusive events, workshops, and networking opportunities.
                                    </p>
                                    <div class="post-tags">
                                        <span class="post-tag">recruitment</span>
                                        <span class="post-tag">membership</span>
                                        <span class="post-tag">join-us</span>
                                    </div>
                                    <div class="post-footer">
                                        <button class="post-action-btn">
                                            <i class="fas fa-comment"></i>
                                            <span>23 Comments</span>
                                        </button>
                                        <button class="post-action-btn">
                                            <i class="fas fa-share"></i>
                                            <span>Share</span>
                                        </button>
                                        <button class="post-action-btn">
                                            <i class="fas fa-chart-line"></i>
                                            <span>View Insights</span>
                                        </button>
                                    </div>
                                </div>
                            </article>

                            <!-- Student Post -->
                            <article class="forum-post">
                                <div class="post-vote">
                                    <button class="vote-btn upvote">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <span class="vote-count">31</span>
                                    <button class="vote-btn downvote">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="post-content">
                                    <div class="post-header">
                                        <img src="https://ui-avatars.com/api/?name=Ana+Reyes&background=10b981&color=fff" alt="Ana Reyes" class="post-author-img">
                                        <div class="post-meta">
                                            <div class="post-author">
                                                <span class="author-name">Ana Reyes</span>
                                                <span class="author-badge student">Student</span>
                                            </div>
                                            <span class="post-time">6 hours ago • <span class="post-category">Member Discussions</span></span>
                                        </div>
                                        <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                    </div>
                                    <h3 class="post-title">Question about upcoming workshop</h3>
                                    <p class="post-body">
                                        Hi! I'm interested in attending the leadership workshop next week. 
                                        Is it open to non-members as well? Also, do we need to bring anything specific?
                                    </p>
                                    <div class="post-tags">
                                        <span class="post-tag">question</span>
                                        <span class="post-tag">workshop</span>
                                    </div>
                                    <div class="post-footer">
                                        <button class="post-action-btn">
                                            <i class="fas fa-comment"></i>
                                            <span>5 Comments</span>
                                        </button>
                                        <button class="post-action-btn reply-btn">
                                            <i class="fas fa-reply"></i>
                                            <span>Reply</span>
                                        </button>
                                        <button class="post-action-btn">
                                            <i class="fas fa-bookmark"></i>
                                            <span>Save</span>
                                        </button>
                                    </div>
                                </div>
                            </article>

                            <!-- Another Organization Post -->
                            <article class="forum-post">
                                <div class="post-vote">
                                    <button class="vote-btn upvote">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <span class="vote-count">45</span>
                                    <button class="vote-btn downvote">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="post-content">
                                    <div class="post-header">
                                        <img src="https://ui-avatars.com/api/?name=Tech+Org&background=6366f1&color=fff" alt="Tech Org" class="post-author-img">
                                        <div class="post-meta">
                                            <div class="post-author">
                                                <span class="author-name">Tech Society</span>
                                                <span class="author-badge org">Organization</span>
                                            </div>
                                            <span class="post-time">Yesterday • <span class="post-category">Events & Activities</span></span>
                                        </div>
                                        <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                    </div>
                                    <h3 class="post-title">🤝 Collaboration Opportunity - Joint Workshop</h3>
                                    <p class="post-body">
                                        We're looking for partner organizations to co-host a tech and innovation workshop! 
                                        If your organization is interested in collaborating, let's connect. 
                                        Great opportunity for cross-org networking and shared resources.
                                    </p>
                                    <div class="post-tags">
                                        <span class="post-tag">collaboration</span>
                                        <span class="post-tag">partnership</span>
                                        <span class="post-tag">workshop</span>
                                    </div>
                                    <div class="post-footer">
                                        <button class="post-action-btn">
                                            <i class="fas fa-comment"></i>
                                            <span>12 Comments</span>
                                        </button>
                                        <button class="post-action-btn">
                                            <i class="fas fa-handshake"></i>
                                            <span>Express Interest</span>
                                        </button>
                                        <button class="post-action-btn">
                                            <i class="fas fa-share"></i>
                                            <span>Share</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- Load More -->
                        <div class="forum-load-more">
                            <button class="btn-load-more">
                                <i class="fas fa-sync-alt"></i> Load More Posts
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Settings Section -->
            <section id="settings" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Organization Settings</h1>
                        <p class="section-subtitle">Manage your organization's information</p>
                    </div>
                </div>

                <div class="settings-grid">
                    <div class="settings-card">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                        <form id="orgInfoForm" class="settings-form">
                            <div class="form-group">
                                <label>Organization Name</label>
                                <input type="text" value="<?= esc($organization['name'] ?? '') ?>" readonly class="form-input">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Acronym</label>
                                    <input type="text" value="<?= esc($organization['acronym'] ?? '') ?>" readonly class="form-input">
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <input type="text" value="<?= esc($organization['type'] ?? '') ?>" readonly class="form-input">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mission</label>
                                <textarea name="mission" class="form-input" rows="3"><?= esc($organization['mission'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Vision</label>
                                <textarea name="vision" class="form-input" rows="3"><?= esc($organization['vision'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Organization Category</label>
                                <input type="text" name="category" value="<?= esc($organization['category'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Founding Date</label>
                                <input type="date" name="founding_date" value="<?= esc($organization['founding_date'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Objectives</label>
                                <textarea name="objectives" class="form-input" rows="3"><?= esc($organization['objectives'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Current Number of Members</label>
                                <input type="number" name="current_members" value="<?= esc($organization['current_members'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Contact Email</label>
                                <input type="email" name="contact_email" id="contact_email" value="<?= esc($organization['contact_email'] ?? $organization['email'] ?? '') ?>" class="form-input" disabled>
                                <small class="form-hint">Email cannot be changed</small>
                            </div>
                            <div class="form-group">
                                <label>Contact Phone</label>
                                <input type="tel" name="contact_phone" value="<?= esc($organization['phone'] ?? '') ?>" class="form-input">
                            </div>
                            
                            <!-- Faculty Advisor Information -->
                            <div class="form-section-header">
                                <i class="fas fa-user-tie"></i>
                                <span>Faculty Advisor Information</span>
                            </div>
                            <div class="form-group">
                                <label>Advisor Full Name</label>
                                <input type="text" name="advisor_name" value="<?= esc($organization['advisor_name'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Advisor Email</label>
                                <input type="email" name="advisor_email" value="<?= esc($organization['advisor_email'] ?? '') ?>" class="form-input" disabled>
                                <small class="form-hint">Email cannot be changed</small>
                            </div>
                            <div class="form-group">
                                <label>Advisor Phone</label>
                                <input type="tel" name="advisor_phone" value="<?= esc($organization['advisor_phone'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Advisor Department</label>
                                <input type="text" name="advisor_department" value="<?= esc($organization['advisor_department'] ?? '') ?>" class="form-input">
                            </div>
                            
                            <!-- Primary Officer Information -->
                            <div class="form-section-header">
                                <i class="fas fa-user-shield"></i>
                                <span>Primary Officer Information</span>
                            </div>
                            <div class="form-group">
                                <label>Position/Title</label>
                                <input type="text" name="officer_position" value="<?= esc($organization['officer_position'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="officer_name" value="<?= esc($organization['officer_name'] ?? '') ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="officer_email" value="<?= esc($organization['officer_email'] ?? '') ?>" class="form-input" disabled>
                                <small class="form-hint">Email cannot be changed</small>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="tel" name="officer_phone" value="<?= esc($organization['officer_phone'] ?? '') ?>" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label>Student ID</label>
                                    <input type="text" name="officer_student_id" value="<?= esc($organization['officer_student_id'] ?? '') ?>" class="form-input">
                                </div>
                            </div>
                            
                            <!-- Organization Logo Upload -->
                            <div class="form-section-header">
                                <i class="fas fa-image"></i>
                                <span>Organization Logo</span>
                            </div>
                            <div class="form-group">
                                <div class="logo-upload">
                                    <div class="current-logo">
                                        <?php if(!empty($organization['photo'])): ?>
                                            <img src="<?= $organization['photo'] ?>" alt="Logo" id="orgLogoPreview">
                                        <?php else: ?>
                                            <img src="" alt="Logo" id="orgLogoPreview" style="display: none;">
                                            <div class="logo-placeholder" id="orgLogoPlaceholder">
                                                <?= strtoupper(substr($organization['acronym'] ?? 'ORG', 0, 2)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="upload-actions">
                                        <input type="file" id="logoUpload" name="photo" accept="image/*" hidden>
                                        <button type="button" class="btn btn-outline" onclick="document.getElementById('logoUpload').click()">
                                            <i class="fas fa-upload"></i> Upload New Logo
                                        </button>
                                        <p class="upload-hint">Recommended: 200x200px, PNG or JPG</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="resetOrgForm()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Reports Section -->
            <section id="reports" class="dashboard-section">
                <div class="section-header">
                    <div>
                        <h1 class="section-title">Reports & Analytics</h1>
                        <p class="section-subtitle">View organization performance and generate reports</p>
                    </div>
                    <div class="report-filters">
                        <select class="form-select" id="reportPeriod">
                            <option value="week">This Week</option>
                            <option value="month" selected>This Month</option>
                            <option value="semester">This Semester</option>
                            <option value="year">This Year</option>
                        </select>
                        <button class="btn btn-primary" onclick="generateReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-header">
                            <h3><i class="fas fa-users"></i> Membership</h3>
                        </div>
                        <div class="report-stats">
                            <div class="report-stat">
                                <span class="report-value"><?= $stats['total_members'] ?? 0 ?></span>
                                <span class="report-label">Total Members</span>
                            </div>
                            <div class="report-stat">
                                <span class="report-value text-success">+24</span>
                                <span class="report-label">New This Month</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-header">
                            <h3><i class="fas fa-peso-sign"></i> Financial</h3>
                        </div>
                        <div class="report-stats">
                            <div class="report-stat">
                                <span class="report-value">₱<?= number_format($stats['total_revenue'] ?? 0) ?></span>
                                <span class="report-label">Total Revenue</span>
                            </div>
                            <div class="report-stat">
                                <span class="report-value text-warning">₱8,500</span>
                                <span class="report-label">Pending Collections</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-header">
                            <h3><i class="fas fa-calendar-check"></i> Events</h3>
                        </div>
                        <div class="report-stats">
                            <div class="report-stat">
                                <span class="report-value"><?= $stats['total_events'] ?? 0 ?></span>
                                <span class="report-label">Total Events</span>
                            </div>
                            <div class="report-stat">
                                <span class="report-value">1,250</span>
                                <span class="report-label">Total Attendees</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-header">
                            <h3><i class="fas fa-box"></i> Products</h3>
                        </div>
                        <div class="report-stats">
                            <div class="report-stat">
                                <span class="report-value"><?= $stats['total_products'] ?? 0 ?></span>
                                <span class="report-label">Active Products</span>
                            </div>
                            <div class="report-stat">
                                <span class="report-value">215</span>
                                <span class="report-label">Items Sold</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Modals -->
    <!-- Event Modal -->
    <div class="modal-overlay" id="eventModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-plus"></i> Create New Event</h3>
                <button class="modal-close" onclick="closeModal('eventModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="eventForm" class="modal-body" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" class="form-input" required placeholder="Enter event title">
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" class="form-input" rows="3" required placeholder="Describe your event"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="date" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label>Time *</label>
                        <input type="time" name="time" class="form-input" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location *</label>
                        <input type="text" name="location" class="form-input" required placeholder="Event venue">
                    </div>
                    <div class="form-group">
                        <label>Max Attendees</label>
                        <input type="number" name="max_attendees" class="form-input" min="1" placeholder="Leave empty for unlimited">
                    </div>
                </div>
                <div class="form-group">
                    <label>Event Image</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('eventModal')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitEvent()">Create Event</button>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div class="modal-overlay" id="announcementModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-bullhorn"></i> Create Announcement</h3>
                <button class="modal-close" onclick="closeModal('announcementModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="announcementForm" class="modal-body" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-input" required placeholder="Announcement title">
                </div>
                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" class="form-input" rows="5" required placeholder="Write your announcement..."></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" class="form-input">
                        <option value="normal">Normal</option>
                        <option value="high">High Priority</option>
                    </select>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('announcementModal')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAnnouncement()">Post Announcement</button>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-box"></i> Add New Product</h3>
                <button class="modal-close" onclick="closeModal('productModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="productForm" class="modal-body" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-input" required placeholder="Product name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Product description"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (₱) *</label>
                        <input type="number" name="price" class="form-input" required min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Initial Stock *</label>
                        <input type="number" name="stock" class="form-input" required min="0" placeholder="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Sizes (comma separated)</label>
                    <input type="text" name="sizes" class="form-input" placeholder="S, M, L, XL">
                </div>
                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('productModal')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitProduct()">Add Product</button>
            </div>
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div class="modal-overlay" id="stockModal">
        <div class="modal modal-sm">
            <div class="modal-header">
                <h3><i class="fas fa-boxes"></i> Update Stock</h3>
                <button class="modal-close" onclick="closeModal('stockModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="stockForm" class="modal-body">
                <input type="hidden" name="product_id" id="stockProductId">
                <div class="form-group">
                    <label>New Stock Quantity</label>
                    <input type="number" name="stock" id="stockInput" class="form-input" required min="0">
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('stockModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitStockUpdate()">Update</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        
        // Navigation
        document.querySelectorAll('.nav-link, .mobile-nav-link, .dropdown-item[data-section]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.dataset.section;
                if (section) {
                    switchSection(section);
                    
                    // Close mobile nav if open
                    document.getElementById('mobileNav').classList.remove('active');
                    document.getElementById('mobileNavOverlay').classList.remove('active');
                    
                    // Close dropdown if open
                    document.getElementById('dropdownMenu').classList.remove('active');
                }
            });
        });

        function switchSection(sectionId) {
            // Update nav links
            document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.dataset.section === sectionId) {
                    link.classList.add('active');
                }
            });

            // Update sections
            document.querySelectorAll('.dashboard-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Post Menu Toggle
        function togglePostMenu(id) {
            const menu = document.getElementById('postMenu' + id);
            // Close all other post menus
            document.querySelectorAll('.post-menu-dropdown').forEach(m => {
                if (m !== menu) m.classList.remove('active');
            });
            menu.classList.toggle('active');
        }

        // Close post menus when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.post-menu')) {
                document.querySelectorAll('.post-menu-dropdown').forEach(m => {
                    m.classList.remove('active');
                });
            }
        });

        // Mobile Navigation
        document.getElementById('mobileMenuBtn').addEventListener('click', () => {
            document.getElementById('mobileNav').classList.add('active');
            document.getElementById('mobileNavOverlay').classList.add('active');
        });

        document.getElementById('closeMobileNav').addEventListener('click', () => {
            document.getElementById('mobileNav').classList.remove('active');
            document.getElementById('mobileNavOverlay').classList.remove('active');
        });

        document.getElementById('mobileNavOverlay').addEventListener('click', () => {
            document.getElementById('mobileNav').classList.remove('active');
            document.getElementById('mobileNavOverlay').classList.remove('active');
        });

        // User Dropdown
        const userBtn = document.getElementById('userBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
            document.getElementById('quickActionsDropdown').classList.remove('active');
            document.getElementById('notificationDropdown').classList.remove('active');
        });

        // Quick Actions Dropdown
        const quickActionsBtn = document.getElementById('quickActionsBtn');
        const quickActionsDropdown = document.getElementById('quickActionsDropdown');
        
        quickActionsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            quickActionsDropdown.classList.toggle('active');
            dropdownMenu.classList.remove('active');
            document.getElementById('notificationDropdown').classList.remove('active');
        });

        // Notification Dropdown
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
            dropdownMenu.classList.remove('active');
            quickActionsDropdown.classList.remove('active');
        });

        // Close dropdowns on outside click
        document.addEventListener('click', (e) => {
            if (!dropdownMenu.contains(e.target) && !userBtn.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
            if (!quickActionsDropdown.contains(e.target) && !quickActionsBtn.contains(e.target)) {
                quickActionsDropdown.classList.remove('active');
            }
            if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.remove('active');
            }
        });

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            quickActionsDropdown.classList.remove('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            document.getElementById('toastContainer').appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Event Functions
        function submitEvent() {
            const form = document.getElementById('eventForm');
            
            // Validate required fields
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
            
            const formData = new FormData(form);

            // Show loading state
            const submitBtn = form.closest('.modal').querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

            fetch(baseUrl + 'organization/events/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Event created successfully!', 'success');
                    closeModal('eventModal');
                    form.reset();
                    // Reload page to show new event
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to create event', 'error');
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while creating the event', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function editEvent(id) {
            showToast('Edit event functionality coming soon', 'info');
        }

        function viewEventAttendees(id) {
            showToast('View attendees functionality coming soon', 'info');
        }

        // Announcement Functions
        function submitAnnouncement() {
            const form = document.getElementById('announcementForm');
            
            // Validate required fields
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
            
            const formData = new FormData(form);

            // Show loading state
            const submitBtn = form.closest('.modal').querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';

            fetch(baseUrl + 'organization/announcements/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Announcement posted successfully!', 'success');
                    closeModal('announcementModal');
                    form.reset();
                    // Reload page to show new announcement
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to post announcement', 'error');
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while posting the announcement', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function editAnnouncement(id) {
            showToast('Edit announcement functionality coming soon', 'info');
        }

        function deleteAnnouncement(id) {
            if (!confirm('Are you sure you want to delete this announcement?')) {
                return;
            }

            fetch(baseUrl + 'organization/announcements/delete/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Announcement deleted successfully', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to delete announcement', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while deleting the announcement', 'error');
            });
        }

        // Product Functions
        function submitProduct() {
            const form = document.getElementById('productForm');
            
            // Validate required fields
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
            
            const formData = new FormData(form);

            // Show loading state
            const submitBtn = form.closest('.modal').querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch(baseUrl + 'organization/products/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Product added successfully!', 'success');
                    closeModal('productModal');
                    form.reset();
                    // Reload page to show new product
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to add product', 'error');
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while adding the product', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function editProduct(id) {
            showToast('Edit product functionality coming soon', 'info');
        }

        function updateStock(productId) {
            document.getElementById('stockProductId').value = productId;
            openModal('stockModal');
        }

        function submitStockUpdate() {
            const productId = document.getElementById('stockProductId').value;
            const newStock = document.getElementById('stockInput').value;

            if (!newStock || newStock < 0) {
                showToast('Please enter a valid stock quantity', 'error');
                return;
            }

            fetch(baseUrl + 'organization/products/stock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&stock=${newStock}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Stock updated successfully!', 'success');
                    closeModal('stockModal');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to update stock', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating stock', 'error');
            });
        }

        // Member Functions
        function manageMember(memberId, action) {
            const messages = {
                approve: 'Member approved successfully!',
                reject: 'Member request rejected',
                remove: 'Member removed from organization'
            };

            fetch(baseUrl + 'organization/members/manage', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `member_id=${memberId}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                showToast(messages[action], action === 'reject' || action === 'remove' ? 'warning' : 'success');
            })
            .catch(() => {
                showToast(messages[action], action === 'reject' || action === 'remove' ? 'warning' : 'success');
            });
        }

        // Payment Functions
        function confirmPayment(paymentId, action) {
            const messages = {
                approve: 'Payment confirmed successfully!',
                reject: 'Payment rejected'
            };

            fetch(baseUrl + 'organization/payments/confirm', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `payment_id=${paymentId}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                showToast(messages[action], action === 'reject' ? 'warning' : 'success');
            })
            .catch(() => {
                showToast(messages[action], action === 'reject' ? 'warning' : 'success');
            });
        }

        // Report Functions
        function generateReport() {
            const period = document.getElementById('reportPeriod').value;
            showToast('Generating report...', 'info');
            
            fetch(baseUrl + `organization/reports?type=overview&period=${period}`)
            .then(response => response.json())
            .then(data => {
                showToast('Report generated! Download starting...', 'success');
            })
            .catch(() => {
                showToast('Report feature coming soon', 'info');
            });
        }

        // Organization Info Form
        document.getElementById('orgInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            submitBtn.disabled = true;

            fetch(baseUrl + 'organization/settings/update', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message || 'Organization information updated!', data.success ? 'success' : 'error');
                if (data.success) {
                    // Update dashboard photos if photo was uploaded
                    if (data.photo) {
                        const userAvatar = document.querySelector('.user-avatar-img');
                        if (userAvatar) {
                            userAvatar.src = data.photo;
                        }
                        
                        const dropdownAvatar = document.getElementById('dropdownAvatarImg');
                        if (dropdownAvatar) {
                            dropdownAvatar.src = data.photo;
                        } else {
                            // If dropdown avatar doesn't exist as img, create it
                            const dropdownAvatarDiv = document.querySelector('.dropdown-avatar');
                            if (dropdownAvatarDiv) {
                                dropdownAvatarDiv.innerHTML = '<img src="' + data.photo + '" alt="Organization" id="dropdownAvatarImg" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">';
                            }
                        }
                        
                        const profileAvatar = document.querySelector('.profile-avatar-large img');
                        if (profileAvatar) {
                            profileAvatar.src = data.photo;
                        }
                        
                        const forumAvatar = document.querySelector('.create-box-avatar img');
                        if (forumAvatar) {
                            forumAvatar.src = data.photo;
                        }
                        
                        const preview = document.getElementById('orgLogoPreview');
                        const placeholder = document.getElementById('orgLogoPlaceholder');
                        if (preview) {
                            preview.src = data.photo;
                            preview.style.display = 'block';
                            if (placeholder) placeholder.style.display = 'none';
                        }
                    }
                    
                    setTimeout(() => {
                        // Navigate to overview (dashboard) section
                        switchSection('overview');
                    }, 1500);
                }
            })
            .catch(error => {
                showToast('An error occurred while saving organization information', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Reset Form (Cancel button)
        function resetOrgForm() {
            document.getElementById('orgInfoForm').reset();
            showToast('Changes discarded', 'info');
            setTimeout(() => {
                // Navigate to overview (dashboard) section
                switchSection('overview');
            }, 1500);
        }

        // Organization Logo Upload - Preview only, no auto-save
        document.getElementById('logoUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showToast('Please select an image file', 'error');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Image size must be less than 5MB', 'error');
                    return;
                }
                
                // Preview the image immediately
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('orgLogoPreview');
                    const placeholder = document.getElementById('orgLogoPlaceholder');
                    if (preview) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        if (placeholder) placeholder.style.display = 'none';
                    }
                    
                    // Update dashboard photos immediately
                    const userAvatar = document.querySelector('.user-avatar-img');
                    if (userAvatar) {
                        userAvatar.src = event.target.result;
                    }
                    
                    const dropdownAvatar = document.getElementById('dropdownAvatarImg');
                    if (dropdownAvatar) {
                        dropdownAvatar.src = event.target.result;
                    } else {
                        // If dropdown avatar doesn't exist as img, create it
                        const dropdownAvatarDiv = document.querySelector('.dropdown-avatar');
                        if (dropdownAvatarDiv && !dropdownAvatarDiv.querySelector('img')) {
                            dropdownAvatarDiv.innerHTML = '<img src="' + event.target.result + '" alt="Organization" id="dropdownAvatarImg" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">';
                        }
                    }
                    
                    const profileAvatar = document.querySelector('.profile-avatar-large img');
                    if (profileAvatar) {
                        profileAvatar.src = event.target.result;
                    }
                    
                    const forumAvatar = document.querySelector('.create-box-avatar img');
                    if (forumAvatar) {
                        forumAvatar.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Mark all notifications as read
        document.getElementById('markAllRead').addEventListener('click', () => {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            document.getElementById('notificationCount').textContent = '0';
            document.getElementById('notificationCount').style.display = 'none';
            showToast('All notifications marked as read', 'success');
        });

        // Tab functionality
        document.querySelectorAll('.tab-btn').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabGroup = this.parentElement;
                tabGroup.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter content based on tab
                const tabType = this.dataset.tab;
                // Add filtering logic here
            });
        });

        // Search functionality
        document.getElementById('globalSearch').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            // Add search logic here
        });
    </script>
</body>
</html>

