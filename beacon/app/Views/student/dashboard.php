<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - BEACON</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/student.css') ?>" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Top Navigation -->
        <header class="top-nav">
            <div class="nav-container">
                <!-- Logo -->
                <div class="nav-brand">
                    <img src="<?= base_url('assets/images/beacon-logo-v3.png') ?>" alt="BEACON Logo" class="nav-logo-img">
                    <span class="logo-text">BEACON</span>
                </div>

                <!-- Main Navigation Links -->
                <nav class="nav-menu" id="navMenu">
                    <a href="#overview" class="nav-link active" data-section="overview">
                        <i class="fas fa-th-large"></i>
                        <span>Overview</span>
                    </a>
                    <a href="#events" class="nav-link" data-section="events">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                        <?php if(!empty($allEvents) && count($allEvents) > 0): ?>
                            <span class="nav-badge" id="eventsNavBadge"><?= count($allEvents) ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="#announcements" class="nav-link" data-section="announcements">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                        <?php if(!empty($allAnnouncements) && count($allAnnouncements) > 0): ?>
                            <span class="nav-badge" id="announcementsNavBadge"><?= count($allAnnouncements) ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="#organizations" class="nav-link" data-section="organizations">
                        <i class="fas fa-users"></i>
                        <span>Organizations</span>
                    </a>
                    <a href="#shop" class="nav-link" data-section="shop">
                        <i class="fas fa-store"></i>
                        <span>Shop</span>
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
                            <i class="fas fa-grip-horizontal"></i>
                            <span class="quick-actions-label">Menu</span>
                            <span class="combined-badge" id="combinedBadge">5</span>
                        </button>
                        <div class="quick-actions-dropdown" id="quickActionsDropdown">
                            <div class="quick-actions-header">
                                <h4>Quick Actions</h4>
                            </div>
                            <div class="quick-actions-grid">
                                <!-- Cart Action -->
                                <div class="quick-action-item" id="cartActionBtn">
                                    <div class="quick-action-icon cart">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="item-badge" id="cartCount">0</span>
                                    </div>
                                    <span class="quick-action-label">My Cart</span>
                                    <span class="quick-action-desc">View your items</span>
                                </div>
                                
                                <!-- Notifications Action -->
                                <div class="quick-action-item" id="notificationActionBtn">
                                    <div class="quick-action-icon notification">
                                        <i class="fas fa-bell"></i>
                                        <span class="item-badge" id="notificationCount">5</span>
                                    </div>
                                    <span class="quick-action-label">Notifications</span>
                                    <span class="quick-action-desc">Updates & alerts</span>
                                </div>
                                
                                <!-- Payments Action -->
                                <div class="quick-action-item" id="paymentsActionBtn">
                                    <div class="quick-action-icon payment">
                                        <i class="fas fa-credit-card"></i>
                                        <span class="item-badge" id="pendingPaymentCount">2</span>
                                    </div>
                                    <span class="quick-action-label">Payments</span>
                                    <span class="quick-action-desc">Pending & history</span>
                                </div>
                            </div>
                            
                            <!-- Notification Panel (shown when clicking notifications) -->
                            <div class="notification-panel" id="notificationPanel">
                                <div class="panel-header">
                                    <button class="back-btn" id="backToActions">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <h4>Notifications</h4>
                                    <button class="mark-all-read" id="markAllRead">Mark all read</button>
                                </div>
                                <div class="notification-tabs">
                                    <button class="notif-tab active" data-type="all">All</button>
                                    <button class="notif-tab" data-type="unread">Unread</button>
                                </div>
                                <div class="notification-list" id="notificationList">
                                    <div class="notification-item unread" data-id="1">
                                        <div class="notif-icon event">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-title">New Event: Tech Innovation Summit</p>
                                            <p class="notif-text">Computer Science Society posted a new event.</p>
                                            <span class="notif-time"><i class="fas fa-clock"></i> 2 hours ago</span>
                                        </div>
                                    </div>
                                    <div class="notification-item unread" data-id="2">
                                        <div class="notif-icon payment">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-title">Payment Reminder</p>
                                            <p class="notif-text">Your payment for CSS T-Shirt is due Dec 1.</p>
                                            <span class="notif-time"><i class="fas fa-clock"></i> 5 hours ago</span>
                                        </div>
                                    </div>
                                    <div class="notification-item unread" data-id="3">
                                        <div class="notif-icon announcement">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-title">Important: Enrollment Extended</p>
                                            <p class="notif-text">Extended until December 15, 2025.</p>
                                            <span class="notif-time"><i class="fas fa-clock"></i> 1 day ago</span>
                                        </div>
                                    </div>
                                    <div class="notification-item" data-id="4">
                                        <div class="notif-icon org">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-title">Membership Approved</p>
                                            <p class="notif-text">Tech Innovation Hub membership approved!</p>
                                            <span class="notif-time"><i class="fas fa-clock"></i> 2 days ago</span>
                                        </div>
                                    </div>
                                    <div class="notification-item" data-id="5">
                                        <div class="notif-icon comment">
                                            <i class="fas fa-comment"></i>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-title">New Reply to Your Comment</p>
                                            <p class="notif-text">John Doe replied to your comment.</p>
                                            <span class="notif-time"><i class="fas fa-clock"></i> 3 days ago</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="user-dropdown" id="userDropdown">
                        <button class="user-btn" id="userBtn">
                            <?php if(session()->get('photo')): ?>
                                <img src="<?= esc(session()->get('photo')) ?>" alt="Profile" class="user-avatar-img">
                            <?php else: ?>
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <span class="user-name"><?= esc(session()->get('name') ?? 'Student') ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="dropdownMenu">
                            <a href="#profile" class="dropdown-item" data-section="profile">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item logout" onclick="confirmLogout(event)">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Navigation Overlay -->
        <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
        <div class="mobile-nav" id="mobileNav">
            <div class="mobile-nav-header">
                <img src="<?= base_url('assets/images/beacon-logo-v3.png') ?>" alt="BEACON Logo" class="nav-logo-img">
                <span class="logo-text">BEACON</span>
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
                    <?php if(!empty($allEvents) && count($allEvents) > 0): ?>
                        <span class="nav-badge" id="eventsMobileNavBadge"><?= count($allEvents) ?></span>
                    <?php endif; ?>
                </a>
                <a href="#announcements" class="mobile-nav-link" data-section="announcements">
                    <i class="fas fa-bullhorn"></i> Announcements
                    <?php if(!empty($allAnnouncements) && count($allAnnouncements) > 0): ?>
                        <span class="nav-badge" id="announcementsMobileNavBadge"><?= count($allAnnouncements) ?></span>
                    <?php endif; ?>
                </a>
                <a href="#organizations" class="mobile-nav-link" data-section="organizations">
                    <i class="fas fa-users"></i> Organizations
                </a>
                <a href="#shop" class="mobile-nav-link" data-section="shop">
                    <i class="fas fa-store"></i> Shop
                </a>
                <a href="#forum" class="mobile-nav-link" data-section="forum">
                    <i class="fas fa-comments"></i> Forum
                </a>
                <div class="mobile-nav-divider"></div>
                <a href="#profile" class="mobile-nav-link" data-section="profile">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a href="#" class="mobile-nav-link logout" onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-area">
                <!-- Overview Section - Facebook Style Feed -->
                <section class="content-section active" id="overview">
                    <div class="student-feed-layout">
                        <!-- Left Sidebar -->
                        <aside class="student-sidebar-left">
                            <!-- Profile Card -->
                            <div class="student-profile-card">
                                <div class="student-cover">
                                    <div class="student-cover-gradient"></div>
                                </div>
                                <div class="student-profile-info">
                                    <div class="student-avatar-large">
                                        <?php if(session()->get('photo')): ?>
                                            <img src="<?= esc(session()->get('photo')) ?>" alt="Profile" id="sidebarProfilePhoto">
                                        <?php else: ?>
                                            <img src="" alt="Profile" id="sidebarProfilePhoto" style="display: none;">
                                            <div class="avatar-placeholder-lg" id="sidebarProfilePlaceholder">
                                                <?= strtoupper(substr($profile['firstname'] ?? 'S', 0, 1) . substr($profile['lastname'] ?? 'T', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="student-name"><?= esc(($profile['firstname'] ?? '') . ' ' . ($profile['lastname'] ?? '')) ?></h2>
                                    <span class="student-id"><?= session()->get('student_number') ?? 'Student' ?></span>
                                    <p class="student-course">
                                        <i class="fas fa-graduation-cap"></i>
                                        <?= esc(strtoupper($student['course'] ?? 'Bachelor of Science')) ?>
                                    </p>
                                </div>
                                <div class="student-stats-row">
                                    <div class="student-stat">
                                        <span class="sstat-num"><?= $eventCount ?? 0 ?></span>
                                        <span class="sstat-text">EVENTS</span>
                                    </div>
                                    <div class="student-stat">
                                        <span class="sstat-num"><?= $orgCount ?? 0 ?></span>
                                        <span class="sstat-text">ORGS</span>
                                    </div>
                                    <div class="student-stat">
                                        <span class="sstat-num">₱0</span>
                                        <span class="sstat-text">PAID</span>
                                    </div>
                                </div>
                                <div class="student-profile-actions">
                                    <a href="#profile" class="btn btn-outline-primary btn-sm" onclick="switchSection('profile')">
                                        <i class="fas fa-user-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>

                            <!-- My Organizations -->
                            <div class="student-sidebar-card">
                                <h4 class="sidebar-card-title"><i class="fas fa-users"></i> My Organizations</h4>
                                <div class="my-org-list">
                                    <?php if(!empty($allJoinedOrganizations)): ?>
                                        <?php foreach($allJoinedOrganizations as $joinedOrg): ?>
                                        <div class="my-org-item">
                                            <?php if(!empty($joinedOrg['photo'])): ?>
                                                <img src="<?= esc($joinedOrg['photo']) ?>" alt="<?= esc($joinedOrg['name']) ?>" class="my-org-avatar <?= $joinedOrg['status'] === 'pending' ? 'pending' : '' ?>" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="my-org-avatar <?= $joinedOrg['status'] === 'pending' ? 'pending' : '' ?>"><?= esc(strtoupper(substr($joinedOrg['acronym'], 0, 2))) ?></div>
                                            <?php endif; ?>
                                            <div class="my-org-info">
                                                <span class="my-org-name"><?= esc($joinedOrg['name']) ?></span>
                                                <?php if($joinedOrg['status'] === 'pending'): ?>
                                                <span class="my-org-status pending">Pending Approval</span>
                                                <?php else: ?>
                                                <span class="my-org-status active">Active Member</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="my-org-item">
                                            <div class="my-org-info" style="width: 100%; text-align: center; padding: 1rem;">
                                                <span style="color: var(--gray-500); font-size: 0.875rem;">No organizations joined yet</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a href="#organizations" class="sidebar-view-all" onclick="switchSection('organizations')">
                                    Explore Organizations <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <!-- Upcoming Events -->
                            <div class="student-sidebar-card">
                                <h4 class="sidebar-card-title"><i class="fas fa-calendar-alt"></i> Upcoming Events</h4>
                                <div class="sidebar-events-list">
                                    <?php if(!empty($upcomingEvents)): ?>
                                        <?php foreach($upcomingEvents as $event): ?>
                                        <div class="sidebar-event-item">
                                            <div class="se-date-box">
                                                <span class="se-day"><?= date('d', strtotime($event['date'])) ?></span>
                                                <span class="se-month"><?= strtoupper(date('M', strtotime($event['date']))) ?></span>
                                            </div>
                                            <div class="se-details">
                                                <span class="se-title"><?= esc($event['title']) ?></span>
                                                <span class="se-org"><?= esc($event['org_name']) ?></span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="sidebar-empty-state" style="padding: 1rem; text-align: center; color: var(--gray-500); font-size: 0.875rem;">
                                            <?php if($hasJoinedOrg): ?>
                                                No upcoming events from your organizations
                                            <?php else: ?>
                                                Join an organization to see upcoming events
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a href="#events" class="sidebar-view-all" onclick="switchSection('events')">
                                    View all events <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </aside>

                        <!-- Main Feed -->
                        <div class="student-feed-main">
                            <!-- Welcome Banner -->
                            <div class="welcome-banner">
                                <div class="welcome-content">
                                    <h1>Welcome back, <?= esc(ucfirst($profile['firstname'] ?? 'Student')) ?>! 👋</h1>
                                    <p>Here's what's happening in your campus community</p>
                                </div>
                                <div class="welcome-date">
                                    <i class="fas fa-calendar-day"></i>
                                    <span id="currentDate"><?= date('l, F j, Y') ?></span>
                                </div>
                            </div>

                            <!-- Organization Posts (Announcements and Events) -->
                            <?php 
                            // Combine and sort posts by date
                            $allPosts = [];
                            
                            // Add announcements
                            if (!empty($organizationPosts['announcements'])) {
                                foreach($organizationPosts['announcements'] as $announcement) {
                                    $allPosts[] = [
                                        'type' => 'announcement',
                                        'data' => $announcement,
                                        'date' => strtotime($announcement['created_at'])
                                    ];
                                }
                            }
                            
                            // Add events
                            if (!empty($organizationPosts['events'])) {
                                foreach($organizationPosts['events'] as $event) {
                                    $allPosts[] = [
                                        'type' => 'event',
                                        'data' => $event,
                                        'date' => strtotime($event['created_at'] ?? $event['date'])
                                    ];
                                }
                            }
                            
                            // Sort by date (newest first)
                            if (!empty($allPosts)) {
                                usort($allPosts, function($a, $b) {
                                    return $b['date'] - $a['date'];
                                });
                            }
                            
                            if(!empty($allPosts)):
                                foreach($allPosts as $post):
                                    if($post['type'] === 'announcement'):
                                        $announcement = $post['data'];
                            ?>
                            <!-- Announcement Post -->
                            <div class="feed-post announcement-post <?= $announcement['priority'] === 'high' ? 'priority-high' : '' ?>">
                                <div class="post-header">
                                    <a href="<?= base_url('student/organization/' . $announcement['org_id']) ?>" style="text-decoration: none; color: inherit; cursor: pointer;">
                                        <div class="post-author-avatar org">
                                            <?php if(!empty($announcement['org_photo'])): ?>
                                                <img src="<?= esc($announcement['org_photo']) ?>" alt="<?= esc($announcement['org_name'] ?? 'Organization') ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <?= strtoupper(substr($announcement['org_acronym'] ?? 'ORG', 0, 2)) ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="post-author-info">
                                        <a href="<?= base_url('student/organization/' . $announcement['org_id']) ?>" class="post-author-name" style="text-decoration: none; color: inherit; cursor: pointer;"><?= esc($announcement['org_name'] ?? 'Organization') ?></a>
                                        <span class="post-time">
                                            <i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                            <?php if($announcement['priority'] === 'high'): ?>
                                            <span class="post-badge important">Important</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <h3 class="post-title"><?= esc($announcement['title']) ?></h3>
                                    <p class="post-text"><?= nl2br(esc($announcement['content'])) ?></p>
                                </div>
                                <div class="post-actions">
                                    <div class="reaction-wrapper" data-post-type="announcement" data-post-id="<?= $announcement['id'] ?>">
                                        <button class="post-action reaction-btn <?= ($announcement['user_reaction'] ?? null) ? 'reacted reaction-' . ($announcement['user_reaction'] ?? '') : '' ?>" 
                                                onmouseenter="showReactionPicker(this)" 
                                                onmouseleave="hideReactionPicker(this)"
                                                onclick="quickReact('announcement', <?= $announcement['id'] ?>, this, '<?= $announcement['user_reaction'] ?? '' ?>')">
                                            <span class="reaction-icon">
                                                <?php 
                                                $userReaction = $announcement['user_reaction'] ?? null;
                                                $reactionCounts = $announcement['reaction_counts'] ?? ['total' => 0];
                                                if ($userReaction):
                                                    $icons = [
                                                        'like' => '👍',
                                                        'love' => '❤️',
                                                        'care' => '🥰',
                                                        'haha' => '😂',
                                                        'wow' => '😮',
                                                        'sad' => '😢',
                                                        'angry' => '😠'
                                                    ];
                                                    echo $icons[$userReaction] ?? '👍';
                                                else:
                                                    echo '👍';
                                                endif;
                                                ?>
                                            </span>
                                            <?php if($reactionCounts['total'] > 0): ?>
                                            <span class="reaction-count"><?= $reactionCounts['total'] ?></span>
                                            <?php endif; ?>
                                        </button>
                                        <div class="reaction-picker" style="display: none;">
                                            <div class="reaction-option" data-reaction="like" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'like', this)">👍</div>
                                            <div class="reaction-option" data-reaction="love" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'love', this)">❤️</div>
                                            <div class="reaction-option" data-reaction="care" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'care', this)">🥰</div>
                                            <div class="reaction-option" data-reaction="haha" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'haha', this)">😂</div>
                                            <div class="reaction-option" data-reaction="wow" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'wow', this)">😮</div>
                                            <div class="reaction-option" data-reaction="sad" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'sad', this)">😢</div>
                                            <div class="reaction-option" data-reaction="angry" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'angry', this)">😠</div>
                                        </div>
                                    </div>
                                    <button class="post-action comment-btn" onclick="toggleComments(<?= $announcement['id'] ?>, 'announcement')">
                                        <i class="far fa-comment"></i> Comment
                                        <?php if(($announcement['comment_count'] ?? 0) > 0): ?>
                                        <span class="comment-count"><?= $announcement['comment_count'] ?></span>
                                        <?php endif; ?>
                                    </button>
                                </div>
                                <div class="comments-section" id="comments-announcement-<?= $announcement['id'] ?>" style="display: none;">
                                    <div class="comments-list" id="comments-list-announcement-<?= $announcement['id'] ?>"></div>
                                    <div class="comment-input-wrapper">
                                        <input type="text" class="comment-input" id="comment-input-announcement-<?= $announcement['id'] ?>" placeholder="Write a comment...">
                                        <button class="btn-send" onclick="postComment(<?= $announcement['id'] ?>, 'announcement')"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                                    elseif($post['type'] === 'event'):
                                        $event = $post['data'];
                            ?>
                            <!-- Event Post -->
                            <div class="feed-post event-post-card">
                                <div class="post-header">
                                    <a href="<?= base_url('student/organization/' . $event['org_id']) ?>" style="text-decoration: none; color: inherit; cursor: pointer;">
                                        <div class="post-author-avatar org">
                                            <?php if(!empty($event['org_photo'])): ?>
                                                <img src="<?= esc($event['org_photo']) ?>" alt="<?= esc($event['org_name'] ?? 'Organization') ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <?= strtoupper(substr($event['org_acronym'] ?? 'ORG', 0, 2)) ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="post-author-info">
                                        <a href="<?= base_url('student/organization/' . $event['org_id']) ?>" class="post-author-name" style="text-decoration: none; color: inherit; cursor: pointer;"><?= esc($event['org_name'] ?? 'Organization') ?></a>
                                        <span class="post-time">
                                            <i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($event['created_at'] ?? $event['date'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <p class="post-text">🎉 New event from <?= esc($event['org_acronym'] ?? 'Organization') ?>!</p>
                                </div>
                                <div class="event-preview-card">
                                    <div class="event-preview-banner" style="position: relative; overflow: hidden;">
                                        <?php if(!empty($event['image'])): ?>
                                            <img src="<?= base_url('uploads/events/' . $event['image']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
                                        <?php endif; ?>
                                        <div class="event-preview-overlay" style="position: relative; z-index: 2;">
                                            <div class="event-date-badge">
                                                <span class="edb-day"><?= date('d', strtotime($event['date'])) ?></span>
                                                <span class="edb-month"><?= strtoupper(date('M', strtotime($event['date']))) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="event-preview-info">
                                        <h3><?= esc($event['title']) ?></h3>
                                        <p><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></p>
                                        <p><i class="fas fa-users"></i> <?= $event['attendees'] ?> going</p>
                                        <div class="event-preview-actions">
                                            <?php if(isset($event['can_join']) && $event['can_join']): ?>
                                                <?php if(isset($event['has_joined']) && $event['has_joined']): ?>
                                                    <button class="btn btn-success btn-sm" onclick="joinEvent(<?= $event['id'] ?>)" style="background-color: #10b981; border: none; border-radius: 25px; padding: 0.5rem 1.25rem; font-weight: 500; color: white; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);">
                                                        <i class="fas fa-check"></i> <span>Joined</span>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-primary btn-sm" onclick="joinEvent(<?= $event['id'] ?>)" style="border-radius: 25px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                                        <i class="fas fa-plus"></i> <span>Join Event</span>
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button class="btn btn-outline btn-sm" disabled style="opacity: 0.6; cursor: not-allowed; border-radius: 25px; padding: 0.5rem 1.25rem;" title="This event is only for specific invited students">
                                                    <i class="fas fa-lock"></i> <span>Invitation Only</span>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-outline btn-sm interested-btn" onclick="toggleInterested(<?= $event['id'] ?>)" data-event-id="<?= $event['id'] ?>" style="border-radius: 25px; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; <?= (isset($event['is_interested']) && $event['is_interested']) ? 'background-color: #fef3c7; border-color: #fbbf24; color: #92400e;' : '' ?>">
                                                <i class="fas fa-star <?= (isset($event['is_interested']) && $event['is_interested']) ? 'fas' : 'far' ?>"></i> <span>Interested</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <div class="reaction-wrapper" data-post-type="event" data-post-id="<?= $event['id'] ?>">
                                        <button class="post-action reaction-btn <?= ($event['user_reaction'] ?? null) ? 'reacted reaction-' . ($event['user_reaction'] ?? '') : '' ?>" 
                                                onmouseenter="showReactionPicker(this)" 
                                                onmouseleave="hideReactionPicker(this)"
                                                onclick="quickReact('event', <?= $event['id'] ?>, this, '<?= $event['user_reaction'] ?? '' ?>')">
                                            <span class="reaction-icon">
                                                <?php 
                                                $userReaction = $event['user_reaction'] ?? null;
                                                $reactionCounts = $event['reaction_counts'] ?? ['total' => 0];
                                                if ($userReaction):
                                                    $icons = [
                                                        'like' => '👍',
                                                        'love' => '❤️',
                                                        'care' => '🥰',
                                                        'haha' => '😂',
                                                        'wow' => '😮',
                                                        'sad' => '😢',
                                                        'angry' => '😠'
                                                    ];
                                                    echo $icons[$userReaction] ?? '👍';
                                                else:
                                                    echo '👍';
                                                endif;
                                                ?>
                                            </span>
                                            <?php if($reactionCounts['total'] > 0): ?>
                                            <span class="reaction-count"><?= $reactionCounts['total'] ?></span>
                                            <?php endif; ?>
                                        </button>
                                        <div class="reaction-picker" style="display: none;">
                                            <div class="reaction-option" data-reaction="like" onclick="setReaction('event', <?= $event['id'] ?>, 'like', this)">👍</div>
                                            <div class="reaction-option" data-reaction="love" onclick="setReaction('event', <?= $event['id'] ?>, 'love', this)">❤️</div>
                                            <div class="reaction-option" data-reaction="care" onclick="setReaction('event', <?= $event['id'] ?>, 'care', this)">🥰</div>
                                            <div class="reaction-option" data-reaction="haha" onclick="setReaction('event', <?= $event['id'] ?>, 'haha', this)">😂</div>
                                            <div class="reaction-option" data-reaction="wow" onclick="setReaction('event', <?= $event['id'] ?>, 'wow', this)">😮</div>
                                            <div class="reaction-option" data-reaction="sad" onclick="setReaction('event', <?= $event['id'] ?>, 'sad', this)">😢</div>
                                            <div class="reaction-option" data-reaction="angry" onclick="setReaction('event', <?= $event['id'] ?>, 'angry', this)">😠</div>
                                        </div>
                                    </div>
                                    <button class="post-action comment-btn" onclick="toggleComments(<?= $event['id'] ?>, 'event')">
                                        <i class="far fa-comment"></i> Comment
                                        <?php if(($event['comment_count'] ?? 0) > 0): ?>
                                        <span class="comment-count"><?= $event['comment_count'] ?></span>
                                        <?php endif; ?>
                                    </button>
                                </div>
                                <div class="comments-section" id="comments-event-<?= $event['id'] ?>" style="display: none;">
                                    <div class="comments-list" id="comments-list-event-<?= $event['id'] ?>"></div>
                                    <div class="comment-input-wrapper">
                                        <input type="text" class="comment-input" id="comment-input-event-<?= $event['id'] ?>" placeholder="Write a comment...">
                                        <button class="btn-send" onclick="postComment(<?= $event['id'] ?>, 'event')"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <?php 
                                    endif;
                                endforeach;
                            else:
                            ?>
                            <!-- Empty State -->
                            <div class="feed-post empty-feed">
                                <div class="empty-feed-content">
                                    <i class="fas fa-inbox"></i>
                                    <h3>No Posts Yet</h3>
                                    <p>Your followed organizations haven't posted anything yet. Check back later!</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Sidebar -->
                        <aside class="student-sidebar-right">
                            <!-- Pending Payments -->
                            <div class="student-sidebar-card payments-sidebar">
                                <div class="sidebar-header-flex">
                                    <h4 class="sidebar-card-title"><i class="fas fa-credit-card"></i> Pending Payments</h4>
                                    <span class="pending-count">2</span>
                                </div>
                                <div class="pending-payments-list">
                                    <div class="pending-payment-item">
                                        <div class="pp-icon">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                        <div class="pp-info">
                                            <span class="pp-name">CSS Official T-Shirt x2</span>
                                            <span class="pp-due">Due: Dec 1, 2025</span>
                                        </div>
                                        <span class="pp-amount">₱700</span>
                                    </div>
                                    <div class="pending-payment-item">
                                        <div class="pp-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="pp-info">
                                            <span class="pp-name">Tech Summit Registration</span>
                                            <span class="pp-due">Due: Dec 3, 2025</span>
                                        </div>
                                        <span class="pp-amount">₱150</span>
                                    </div>
                                </div>
                                <div class="pending-total">
                                    <span>Total Pending</span>
                                    <span class="total-value">₱850</span>
                                </div>
                                <a href="#payments" class="btn btn-primary btn-block" onclick="switchSection('payments')">
                                    <i class="fas fa-credit-card"></i> Pay Now
                                </a>
                            </div>

                            <!-- Suggested Organizations -->
                            <div class="student-sidebar-card">
                                <h4 class="sidebar-card-title"><i class="fas fa-compass"></i> Suggested for You</h4>
                                <div class="suggested-orgs-list">
                                    <?php if(!empty($suggestedOrganizations)): ?>
                                        <?php foreach($suggestedOrganizations as $sugOrg): ?>
                                        <div class="suggested-org-item">
                                            <?php if(!empty($sugOrg['photo'])): ?>
                                                <img src="<?= esc($sugOrg['photo']) ?>" alt="<?= esc($sugOrg['name']) ?>" class="sug-org-avatar" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="sug-org-avatar"><?= esc(strtoupper(substr($sugOrg['acronym'], 0, 2))) ?></div>
                                            <?php endif; ?>
                                            <div class="sug-org-info">
                                                <span class="sug-org-name"><?= esc($sugOrg['name']) ?></span>
                                                <span class="sug-org-members"><?= esc($sugOrg['members'] ?? 0) ?> members</span>
                                            </div>
                                            <?php if($sugOrg['is_member'] ?? false): ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <i class="fas fa-check"></i> Member
                                                </button>
                                            <?php elseif($sugOrg['is_pending'] ?? false): ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <i class="fas fa-clock"></i> Pending
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-primary" onclick="joinOrg(<?= $sugOrg['id'] ?>)">
                                                    Join
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="sidebar-empty-state" style="padding: 1rem; text-align: center; color: var(--gray-500); font-size: 0.875rem;">
                                            No organizations available to join
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a href="#organizations" class="sidebar-view-all" onclick="switchSection('organizations')">
                                    See all organizations <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <!-- Quick Links -->
                            <div class="student-sidebar-card quick-links-card">
                                <h4 class="sidebar-card-title"><i class="fas fa-link"></i> Quick Links</h4>
                                <div class="quick-links-list">
                                    <a href="#events" class="quick-link-item" onclick="switchSection('events')">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Browse Events</span>
                                    </a>
                                    <a href="#shop" class="quick-link-item" onclick="switchSection('shop')">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Shop Products</span>
                                    </a>
                                    <a href="#announcements" class="quick-link-item" onclick="switchSection('announcements')">
                                        <i class="fas fa-bullhorn"></i>
                                        <span>Announcements</span>
                                    </a>
                                    <a href="#payments" class="quick-link-item" onclick="switchSection('payments')">
                                        <i class="fas fa-history"></i>
                                        <span>Payment History</span>
                                    </a>
                                </div>
                            </div>
                        </aside>
                    </div>
                </section>

                <!-- Events Section -->
                <section class="content-section" id="events">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Campus Events</h1>
                            <p class="section-subtitle">Discover and join exciting events from organizations</p>
                        </div>
                        <div class="filter-group">
                            <select class="filter-select" id="eventFilter">
                                <option value="all">All Events</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="joined">Joined</option>
                            </select>
                        </div>
                    </div>
                    <div class="events-grid" id="eventsGrid">
                        <?php if(!empty($allEvents)): ?>
                            <?php foreach($allEvents as $event): ?>
                            <div class="event-card" data-event-id="<?= $event['id'] ?>" data-has-joined="<?= isset($event['has_joined']) && $event['has_joined'] ? 'true' : 'false' ?>" data-event-date="<?= $event['date'] ?>">
                                <div class="event-card-header">
                                    <span class="event-tag"><?= esc($event['org_type']) ?></span>
                                    <span class="event-fee free">Free</span>
                                </div>
                                <div class="event-card-body">
                                    <h3><?= esc($event['title']) ?></h3>
                                    <p><?= esc($event['description']) ?></p>
                                    <div class="event-info">
                                        <div class="info-item">
                                            <i class="fas fa-calendar"></i>
                                            <span><?= esc($event['date_formatted']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?= esc($event['time']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= esc($event['location']) ?></span>
                                        </div>
                                    </div>
                                    <div class="event-org">
                                        <?php if(!empty($event['org_photo'])): ?>
                                            <img src="<?= esc($event['org_photo']) ?>" alt="<?= esc($event['org_name']) ?>" class="org-avatar small" style="width: 32px; height: 32px; border-radius: 6px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="org-avatar small"><?= esc(strtoupper(substr($event['org_acronym'], 0, 2))) ?></div>
                                        <?php endif; ?>
                                        <span><?= esc($event['org_name']) ?></span>
                                    </div>
                                </div>
                                <div class="event-card-footer">
                                    <?php if(isset($event['can_join']) && $event['can_join']): ?>
                                        <?php if(isset($event['has_joined']) && $event['has_joined']): ?>
                                            <button class="btn-primary" onclick="joinEvent(<?= $event['id'] ?>)" style="background-color: #10b981; border-color: #10b981; border-radius: 20px; padding: 0.625rem 1.25rem; font-weight: 500; color: white;">
                                                <i class="fas fa-check" style="margin-right: 0.5rem;"></i> Joined
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-primary" onclick="joinEvent(<?= $event['id'] ?>)">
                                                <i class="fas fa-plus"></i> Join Event
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn-secondary" disabled style="opacity: 0.6; cursor: not-allowed;" title="This event is only for specific invited students">
                                            <i class="fas fa-lock"></i> Invitation Only
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn-secondary" onclick="viewEventDetails(<?= $event['id'] ?>)">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-events-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                                <div style="color: var(--gray-500);">
                                    <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <h3 style="margin-bottom: 0.5rem;">No Events Available</h3>
                                    <?php if($hasJoinedOrg): ?>
                                        <p>Your organizations haven't posted any events yet. Check back later!</p>
                                    <?php else: ?>
                                        <p>Join an organization to see events from your campus community.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Announcements Section -->
                <section class="content-section" id="announcements">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Announcements</h1>
                            <p class="section-subtitle">Stay updated with the latest news and updates</p>
                        </div>
                    </div>
                    <div class="announcements-list-full">
                        <?php if(!empty($allAnnouncements)): ?>
                            <?php foreach($allAnnouncements as $announcement): ?>
                            <div class="announcement-card <?= $announcement['priority'] === 'high' ? 'priority-high' : '' ?>">
                                <div class="announcement-card-header">
                                    <?php if($announcement['priority'] === 'high'): ?>
                                    <span class="priority-badge high"><i class="fas fa-exclamation-circle"></i> Important</span>
                                    <?php else: ?>
                                    <span class="priority-badge medium"><i class="fas fa-info-circle"></i> Info</span>
                                    <?php endif; ?>
                                    <span class="announcement-date"><?= date('F j, Y', strtotime($announcement['created_at'])) ?></span>
                                </div>
                                <h3><?= esc($announcement['title']) ?></h3>
                                <p><?= nl2br(esc($announcement['content'])) ?></p>
                                <div class="announcement-footer">
                                    <a href="<?= base_url('student/organization/' . $announcement['org_id']) ?>" class="announcement-author" style="text-decoration: none; color: inherit; cursor: pointer;"><i class="fas fa-building"></i> <?= esc($announcement['org_name']) ?></a>
                                    <div style="display: flex; gap: 1rem; align-items: center;">
                                        <div class="reaction-wrapper" data-post-type="announcement" data-post-id="<?= $announcement['id'] ?>" style="position: relative;">
                                            <button class="btn-comment reaction-btn <?= ($announcement['user_reaction'] ?? null) ? 'reacted reaction-' . ($announcement['user_reaction'] ?? '') : '' ?>" 
                                                    onmouseenter="showReactionPicker(this)" 
                                                    onmouseleave="hideReactionPicker(this)"
                                                    onclick="quickReact('announcement', <?= $announcement['id'] ?>, this, '<?= $announcement['user_reaction'] ?? '' ?>')"
                                                    style="background: transparent; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                                <span class="reaction-icon">
                                                    <?php 
                                                    $userReaction = $announcement['user_reaction'] ?? null;
                                                    $reactionCounts = $announcement['reaction_counts'] ?? ['total' => 0];
                                                    if ($userReaction):
                                                        $icons = ['like' => '👍', 'love' => '❤️', 'care' => '🥰', 'haha' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😠'];
                                                        echo $icons[$userReaction] ?? '👍';
                                                    else:
                                                        echo '👍';
                                                    endif;
                                                    ?>
                                                </span>
                                                <?php if($reactionCounts['total'] > 0): ?>
                                                <span class="reaction-count"><?= $reactionCounts['total'] ?></span>
                                                <?php endif; ?>
                                            </button>
                                            <div class="reaction-picker" style="display: none;">
                                                <div class="reaction-option" data-reaction="like" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'like', this)">👍</div>
                                                <div class="reaction-option" data-reaction="love" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'love', this)">❤️</div>
                                                <div class="reaction-option" data-reaction="care" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'care', this)">🥰</div>
                                                <div class="reaction-option" data-reaction="haha" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'haha', this)">😂</div>
                                                <div class="reaction-option" data-reaction="wow" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'wow', this)">😮</div>
                                                <div class="reaction-option" data-reaction="sad" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'sad', this)">😢</div>
                                                <div class="reaction-option" data-reaction="angry" onclick="setReaction('announcement', <?= $announcement['id'] ?>, 'angry', this)">😠</div>
                                            </div>
                                        </div>
                                        <button class="btn-comment" onclick="toggleComments(<?= $announcement['id'] ?>, 'announcement')" style="background: transparent; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                            <i class="fas fa-comment"></i> Comment
                                            <?php if(($announcement['comment_count'] ?? 0) > 0): ?>
                                            <span class="comment-count"><?= $announcement['comment_count'] ?></span>
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="comments-section" id="comments-announcement-<?= $announcement['id'] ?>" style="display: none;">
                                    <div class="comments-list" id="comments-list-announcement-<?= $announcement['id'] ?>"></div>
                                    <div class="comment-input-wrapper">
                                        <input type="text" class="comment-input" id="comment-input-announcement-<?= $announcement['id'] ?>" placeholder="Write a comment...">
                                        <button class="btn-send" onclick="postComment(<?= $announcement['id'] ?>, 'announcement')"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-announcements-state" style="text-align: center; padding: 3rem;">
                                <div style="color: var(--gray-500);">
                                    <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <h3 style="margin-bottom: 0.5rem;">No Announcements Available</h3>
                                    <?php if($hasJoinedOrg): ?>
                                        <p>Your organizations haven't posted any announcements yet. Check back later!</p>
                                    <?php else: ?>
                                        <p>Join an organization to see announcements from your campus community.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Organizations Section -->
                <section class="content-section" id="organizations">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Organizations</h1>
                            <p class="section-subtitle">Explore and join campus organizations</p>
                        </div>
                        <div class="filter-group">
                            <select class="filter-select" id="orgFilter">
                                <option value="all">All Organizations</option>
                                <option value="academic">Academic</option>
                                <option value="sports">Sports</option>
                                <option value="cultural">Cultural</option>
                                <option value="service">Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="orgs-grid">
                        <?php if(!empty($availableOrganizations)): ?>
                            <?php foreach($availableOrganizations as $org): ?>
                            <div class="org-card">
                                <div class="org-card-header">
                                    <?php if(!empty($org['photo'])): ?>
                                        <img src="<?= esc($org['photo']) ?>" alt="<?= esc($org['name']) ?>" class="org-avatar large" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="org-avatar large"><?= esc(strtoupper(substr($org['acronym'], 0, 2))) ?></div>
                                    <?php endif; ?>
                                    <div class="org-card-info">
                                        <h3><?= esc($org['name']) ?></h3>
                                        <span class="org-type"><?= esc($org['type']) ?></span>
                                    </div>
                                    <?php if($org['is_member'] ?? false): ?>
                                    <span class="member-badge"><i class="fas fa-check"></i> Member</span>
                                    <?php elseif($org['is_pending'] ?? false): ?>
                                    <span class="pending-badge"><i class="fas fa-clock"></i> Pending</span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($org['description'])): ?>
                                <p class="org-description"><?= esc($org['description']) ?></p>
                                <?php else: ?>
                                <p class="org-description">Join this organization to connect with like-minded students and participate in campus activities.</p>
                                <?php endif; ?>
                                <div class="org-stats">
                                    <div class="stat">
                                        <i class="fas fa-users"></i>
                                        <span><?= number_format($org['members']) ?> Members</span>
                                    </div>
                                    <div class="stat">
                                        <i class="fas fa-calendar"></i>
                                        <span><?= $org['event_count'] ?? 0 ?> Events</span>
                                    </div>
                                </div>
                                <div class="org-card-footer">
                                    <?php if($org['is_member'] ?? false): ?>
                                        <button class="btn-secondary" onclick="viewOrgDetails(<?= $org['id'] ?>)">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    <?php elseif($org['is_pending'] ?? false): ?>
                                        <button class="btn-secondary" onclick="viewOrgDetails(<?= $org['id'] ?>)">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-primary" onclick="joinOrg(<?= $org['id'] ?>)">
                                            <i class="fas fa-plus"></i> Join Organization
                                        </button>
                                        <?php if($org['is_following'] ?? false): ?>
                                            <button class="btn-secondary" id="followBtn_<?= $org['id'] ?>" onclick="unfollowOrg(<?= $org['id'] ?>)">
                                                <i class="fas fa-check"></i> Following
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-outline" id="followBtn_<?= $org['id'] ?>" onclick="followOrg(<?= $org['id'] ?>)">
                                                <i class="fas fa-user-plus"></i> Follow
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn-secondary" onclick="viewOrgDetails(<?= $org['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="org-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                                <div style="color: var(--gray-500);">
                                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <h3 style="margin-bottom: 0.5rem;">No Organizations Available</h3>
                                    <p>There are no active organizations at the moment. Please check back later.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Shop Section -->
                <section class="content-section" id="shop">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Organization Shop</h1>
                            <p class="section-subtitle">Browse and purchase organization merchandise</p>
                        </div>
                        <div class="filter-group">
                            <select class="filter-select" id="shopFilter">
                                <option value="all">All Products</option>
                                <option value="apparel">Apparel</option>
                                <option value="accessories">Accessories</option>
                                <option value="merchandise">Merchandise</option>
                            </select>
                        </div>
                    </div>
                    <div class="products-grid">
                        <?php if(!empty($allProducts)): ?>
                            <?php foreach($allProducts as $product): ?>
                            <div class="product-card">
                                <div class="product-image <?= $product['image'] ? '' : 'default' ?>">
                                    <?php if($product['image']): ?>
                                        <img src="<?= base_url('uploads/products/' . esc($product['image'])) ?>" alt="<?= esc($product['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <i class="fas fa-box"></i>
                                    <?php endif; ?>
                                    <?php if($product['status'] === 'low_stock'): ?>
                                        <span class="product-badge">Low Stock</span>
                                    <?php elseif($product['sold'] > 10): ?>
                                        <span class="product-badge">Popular</span>
                                    <?php endif; ?>
                                    <?php if($product['status'] === 'out_of_stock'): ?>
                                        <span class="product-badge out-of-stock">Out of Stock</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-body">
                                    <h3><?= esc($product['name']) ?></h3>
                                    <p class="product-org"><?= esc($product['org_name']) ?></p>
                                    <p class="product-desc"><?= esc($product['description'] ?: 'No description available.') ?></p>
                                    <?php if($product['sizes']): ?>
                                        <p class="product-sizes" style="font-size: 0.75rem; color: var(--gray-500); margin: 0.25rem 0;">
                                            <i class="fas fa-ruler"></i> Sizes: <?= esc($product['sizes']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="product-footer">
                                        <span class="product-price">₱<?= esc($product['price']) ?></span>
                                        <button class="btn-add-cart" 
                                                onclick="addToCart(<?= $product['id'] ?>)" 
                                                <?= $product['status'] === 'out_of_stock' || $product['stock'] <= 0 ? 'disabled' : '' ?>
                                                title="<?= $product['status'] === 'out_of_stock' || $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart' ?>">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                    <?php if($product['stock'] > 0 && $product['stock'] <= 10): ?>
                                        <p style="font-size: 0.75rem; color: var(--warning); margin-top: 0.5rem;">
                                            <i class="fas fa-exclamation-triangle"></i> Only <?= $product['stock'] ?> left in stock
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-products-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                                <div style="color: var(--gray-500);">
                                    <i class="fas fa-shopping-bag" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                    <h3 style="margin-bottom: 0.5rem;">No Products Available</h3>
                                    <?php if($hasJoinedOrg): ?>
                                        <p>Your organizations haven't added any products yet. Check back later!</p>
                                    <?php else: ?>
                                        <p>Join an organization to see products from your campus community.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Payments Section -->
                <section class="content-section" id="payments">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Payments</h1>
                            <p class="section-subtitle">Manage your pending payments and view history</p>
                        </div>
                    </div>
                    
                    <div class="payments-tabs">
                        <button class="payment-tab active" data-tab="pending">Pending Payments</button>
                        <button class="payment-tab" data-tab="history">Payment History</button>
                    </div>

                    <div class="payment-content active" id="pending-content">
                        <div class="payment-cards">
                            <div class="payment-card pending">
                                <div class="payment-card-header">
                                    <span class="payment-status pending"><i class="fas fa-clock"></i> Pending</span>
                                    <span class="payment-id">ORD-6745ABC1</span>
                                </div>
                                <div class="payment-card-body">
                                    <h3>CSS Official T-Shirt x2</h3>
                                    <p class="payment-org"><i class="fas fa-building"></i> Computer Science Society</p>
                                    <div class="payment-due">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Due: December 1, 2025</span>
                                    </div>
                                </div>
                                <div class="payment-card-footer">
                                    <span class="payment-amount">₱700.00</span>
                                    <button class="btn-primary" onclick="initiatePayment(1)">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </button>
                                </div>
                            </div>

                            <div class="payment-card pending">
                                <div class="payment-card-header">
                                    <span class="payment-status pending"><i class="fas fa-clock"></i> Pending</span>
                                    <span class="payment-id">ORD-6745ABC2</span>
                                </div>
                                <div class="payment-card-body">
                                    <h3>Tech Summit 2025 Registration</h3>
                                    <p class="payment-org"><i class="fas fa-building"></i> Computer Science Society</p>
                                    <div class="payment-due">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Due: December 3, 2025</span>
                                    </div>
                                </div>
                                <div class="payment-card-footer">
                                    <span class="payment-amount">₱150.00</span>
                                    <button class="btn-primary" onclick="initiatePayment(2)">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-content" id="history-content">
                        <div class="payment-history-table">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Description</th>
                                        <th>Organization</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="txn-id">TXN-9876DEF1</span></td>
                                        <td>GEI Eco-Bag</td>
                                        <td>Green Energy Initiative</td>
                                        <td>₱150.00</td>
                                        <td>Nov 15, 2025</td>
                                        <td><span class="method-badge gcash">GCash</span></td>
                                        <td><span class="status-badge completed"><i class="fas fa-check"></i> Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="txn-id">TXN-9876DEF2</span></td>
                                        <td>CSS Membership Fee</td>
                                        <td>Computer Science Society</td>
                                        <td>₱200.00</td>
                                        <td>Nov 10, 2025</td>
                                        <td><span class="method-badge cash">Cash</span></td>
                                        <td><span class="status-badge completed"><i class="fas fa-check"></i> Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="txn-id">TXN-9876DEF3</span></td>
                                        <td>Business Workshop Registration</td>
                                        <td>Business Administration Club</td>
                                        <td>₱300.00</td>
                                        <td>Nov 5, 2025</td>
                                        <td><span class="method-badge bank">Bank Transfer</span></td>
                                        <td><span class="status-badge completed"><i class="fas fa-check"></i> Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Forum Section -->
                <section class="content-section" id="forum">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Community Forum</h1>
                            <p class="section-subtitle">Discuss with fellow students and organizations</p>
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
                                    <li class="forum-category-item" data-category="general">
                                        <i class="fas fa-comment-dots"></i>
                                        <span>General Discussion</span>
                                        <span class="category-count">8</span>
                                    </li>
                                    <li class="forum-category-item" data-category="events">
                                        <i class="fas fa-calendar-star"></i>
                                        <span>Events & Activities</span>
                                        <span class="category-count">5</span>
                                    </li>
                                    <li class="forum-category-item" data-category="academics">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>Academics</span>
                                        <span class="category-count">6</span>
                                    </li>
                                    <li class="forum-category-item" data-category="marketplace">
                                        <i class="fas fa-store"></i>
                                        <span>Buy & Sell</span>
                                        <span class="category-count">3</span>
                                    </li>
                                    <li class="forum-category-item" data-category="help">
                                        <i class="fas fa-question-circle"></i>
                                        <span>Help & Support</span>
                                        <span class="category-count">2</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="forum-trending-card">
                                <h4 class="forum-sidebar-title"><i class="fas fa-fire"></i> Trending Topics</h4>
                                <ul class="trending-list">
                                    <li class="trending-item">
                                        <span class="trending-rank">#1</span>
                                        <span class="trending-topic">University Week 2025</span>
                                    </li>
                                    <li class="trending-item">
                                        <span class="trending-rank">#2</span>
                                        <span class="trending-topic">Enrollment Tips</span>
                                    </li>
                                    <li class="trending-item">
                                        <span class="trending-rank">#3</span>
                                        <span class="trending-topic">Org Recruitment</span>
                                    </li>
                                </ul>
                            </div>
                        </aside>

                        <!-- Forum Main Content -->
                        <div class="forum-main">
                            <!-- Create Post Box -->
                            <div class="forum-create-box">
                                <div class="create-box-avatar">
                                    <?php if(session()->get('photo')): ?>
                                        <img src="<?= esc(session()->get('photo')) ?>" alt="Profile">
                                    <?php else: ?>
                                        <div class="avatar-placeholder-sm"><?= strtoupper(substr($profile['firstname'] ?? 'S', 0, 1)) ?></div>
                                    <?php endif; ?>
                                </div>
                                <input type="text" class="create-box-input" placeholder="What's on your mind, <?= esc($profile['firstname'] ?? 'Student') ?>?" onclick="openCreatePostModal()">
                                <button class="create-box-btn" onclick="openCreatePostModal()">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>

                            <!-- Forum Filter Bar -->
                            <div class="forum-filter-bar">
                                <div class="forum-tabs">
                                    <button class="forum-tab active" data-filter="latest">
                                        <i class="fas fa-clock"></i> Latest
                                    </button>
                                    <button class="forum-tab" data-filter="popular">
                                        <i class="fas fa-fire-alt"></i> Popular
                                    </button>
                                    <button class="forum-tab" data-filter="following">
                                        <i class="fas fa-user-friends"></i> Following
                                    </button>
                                </div>
                                <div class="forum-search">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Search posts...">
                                </div>
                            </div>

                            <!-- Forum Posts -->
                            <div class="forum-posts-list">
                                <!-- Post 1 -->
                                <article class="forum-post">
                                    <div class="post-vote">
                                        <button class="vote-btn upvote" onclick="votePost(1, 'up')">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <span class="vote-count">42</span>
                                        <button class="vote-btn downvote" onclick="votePost(1, 'down')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div class="post-content">
                                        <div class="post-header">
                                            <img src="https://ui-avatars.com/api/?name=Tech+Society&background=6366f1&color=fff" alt="Tech Society" class="post-author-img">
                                            <div class="post-meta">
                                                <div class="post-author">
                                                    <span class="author-name">Tech Society</span>
                                                    <span class="author-badge org">Organization</span>
                                                </div>
                                                <span class="post-time">2 hours ago • <span class="post-category">Events & Activities</span></span>
                                            </div>
                                            <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                        </div>
                                        <h3 class="post-title">🚀 Hackathon 2025 Registration Now Open!</h3>
                                        <p class="post-body">
                                            Join us for the biggest coding event of the year! This 48-hour hackathon will challenge you to build innovative solutions. 
                                            Great prizes await including ₱50,000 for the winning team! Registration ends Nov 30.
                                        </p>
                                        <div class="post-tags">
                                            <span class="post-tag">hackathon</span>
                                            <span class="post-tag">coding</span>
                                            <span class="post-tag">competition</span>
                                        </div>
                                        <div class="post-footer">
                                            <button class="post-action-btn">
                                                <i class="fas fa-comment"></i>
                                                <span>15 Comments</span>
                                            </button>
                                            <button class="post-action-btn">
                                                <i class="fas fa-share"></i>
                                                <span>Share</span>
                                            </button>
                                            <button class="post-action-btn">
                                                <i class="fas fa-bookmark"></i>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </article>

                                <!-- Post 2 -->
                                <article class="forum-post">
                                    <div class="post-vote">
                                        <button class="vote-btn upvote active" onclick="votePost(2, 'up')">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <span class="vote-count">28</span>
                                        <button class="vote-btn downvote" onclick="votePost(2, 'down')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div class="post-content">
                                        <div class="post-header">
                                            <img src="https://ui-avatars.com/api/?name=Juan+Cruz&background=10b981&color=fff" alt="Juan Cruz" class="post-author-img">
                                            <div class="post-meta">
                                                <div class="post-author">
                                                    <span class="author-name">Juan Cruz</span>
                                                    <span class="author-badge student">Student</span>
                                                </div>
                                                <span class="post-time">5 hours ago • <span class="post-category">General Discussion</span></span>
                                            </div>
                                            <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                        </div>
                                        <h3 class="post-title">Best study spots on campus?</h3>
                                        <p class="post-body">
                                            Hey everyone! I'm looking for quiet places to study on campus. The library is always full during exam season. 
                                            Any recommendations? I prefer places with good WiFi and outlets for charging.
                                        </p>
                                        <div class="post-tags">
                                            <span class="post-tag">study</span>
                                            <span class="post-tag">campus</span>
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
                                                <i class="fas fa-bookmark"></i>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </article>

                                <!-- Post 3 -->
                                <article class="forum-post">
                                    <div class="post-vote">
                                        <button class="vote-btn upvote" onclick="votePost(3, 'up')">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <span class="vote-count">19</span>
                                        <button class="vote-btn downvote" onclick="votePost(3, 'down')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div class="post-content">
                                        <div class="post-header">
                                            <img src="https://ui-avatars.com/api/?name=Arts+Club&background=f59e0b&color=fff" alt="Arts Club" class="post-author-img">
                                            <div class="post-meta">
                                                <div class="post-author">
                                                    <span class="author-name">Arts & Culture Club</span>
                                                    <span class="author-badge org">Organization</span>
                                                </div>
                                                <span class="post-time">Yesterday • <span class="post-category">Events & Activities</span></span>
                                            </div>
                                            <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                        </div>
                                        <h3 class="post-title">🎨 Art Exhibition: "Expressions" - Free Entry!</h3>
                                        <p class="post-body">
                                            We're excited to announce our annual art exhibition featuring works from talented student artists! 
                                            The exhibition runs from Dec 1-5 at the University Gallery. Free admission for all students.
                                        </p>
                                        <div class="post-image">
                                            <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=600" alt="Art Exhibition">
                                        </div>
                                        <div class="post-tags">
                                            <span class="post-tag">art</span>
                                            <span class="post-tag">exhibition</span>
                                            <span class="post-tag">free</span>
                                        </div>
                                        <div class="post-footer">
                                            <button class="post-action-btn">
                                                <i class="fas fa-comment"></i>
                                                <span>8 Comments</span>
                                            </button>
                                            <button class="post-action-btn">
                                                <i class="fas fa-share"></i>
                                                <span>Share</span>
                                            </button>
                                            <button class="post-action-btn saved">
                                                <i class="fas fa-bookmark"></i>
                                                <span>Saved</span>
                                            </button>
                                        </div>
                                    </div>
                                </article>

                                <!-- Post 4 -->
                                <article class="forum-post">
                                    <div class="post-vote">
                                        <button class="vote-btn upvote" onclick="votePost(4, 'up')">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <span class="vote-count">12</span>
                                        <button class="vote-btn downvote" onclick="votePost(4, 'down')">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div class="post-content">
                                        <div class="post-header">
                                            <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=ec4899&color=fff" alt="Maria Santos" class="post-author-img">
                                            <div class="post-meta">
                                                <div class="post-author">
                                                    <span class="author-name">Maria Santos</span>
                                                    <span class="author-badge student">Student</span>
                                                </div>
                                                <span class="post-time">Yesterday • <span class="post-category">Buy & Sell</span></span>
                                            </div>
                                            <button class="post-menu-btn"><i class="fas fa-ellipsis-h"></i></button>
                                        </div>
                                        <h3 class="post-title">Selling: Calculus Textbook (10th Edition) - ₱500</h3>
                                        <p class="post-body">
                                            Selling my calculus textbook, barely used! Still in great condition with no highlights or markings. 
                                            Perfect for Math 101 students. Meet up at the library. DM me if interested!
                                        </p>
                                        <div class="post-tags">
                                            <span class="post-tag">selling</span>
                                            <span class="post-tag">textbook</span>
                                            <span class="post-tag">math</span>
                                        </div>
                                        <div class="post-footer">
                                            <button class="post-action-btn">
                                                <i class="fas fa-comment"></i>
                                                <span>5 Comments</span>
                                            </button>
                                            <button class="post-action-btn">
                                                <i class="fas fa-share"></i>
                                                <span>Share</span>
                                            </button>
                                            <button class="post-action-btn">
                                                <i class="fas fa-bookmark"></i>
                                                <span>Save</span>
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

                <!-- Profile Section -->
                <section class="content-section" id="profile">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Edit Profile</h1>
                            <p class="section-subtitle">Update your personal information</p>
                        </div>
                    </div>
                    <div class="profile-container">
                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="profile-avatar-large">
                                    <?php if(session()->get('photo')): ?>
                                        <img src="<?= esc(session()->get('photo')) ?>" alt="Profile" id="profilePhotoPreview">
                                    <?php else: ?>
                                        <i class="fas fa-user" id="profilePhotoIcon"></i>
                                        <img src="" alt="Profile" id="profilePhotoPreview" style="display: none;">
                                    <?php endif; ?>
                                    <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;">
                                    <button type="button" class="change-photo-btn" onclick="document.getElementById('profilePhotoInput').click()"><i class="fas fa-camera"></i></button>
                                </div>
                                <div class="profile-header-info">
                                    <h2><?= esc(($profile['firstname'] ?? '') . ' ' . ($profile['lastname'] ?? '')) ?></h2>
                                    <p><?= esc($student['student_id'] ?? '') ?></p>
                                    <span class="profile-badge"><?= esc(strtoupper($student['department'] ?? '')) ?> - <?= esc($student['course'] ?? '') ?></span>
                                </div>
                            </div>
                            <form class="profile-form" id="profileForm">
                                <!-- Personal Information -->
                                <div class="form-section-header">
                                    <i class="fas fa-user"></i>
                                    <span>Personal Information</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="firstname" value="<?= esc($profile['firstname'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" name="middlename" value="<?= esc($profile['middlename'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="lastname" value="<?= esc($profile['lastname'] ?? '') ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" name="birthday" value="<?= esc($profile['birthday'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="">Select gender</option>
                                            <option value="male" <?= ($profile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= ($profile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                            <option value="other" <?= ($profile['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="<?= esc($profile['phone'] ?? '') ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Province</label>
                                        <input type="text" name="province" value="<?= esc($address['province'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>City/Municipality</label>
                                        <input type="text" name="city_municipality" value="<?= esc($address['city_municipality'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Barangay</label>
                                        <input type="text" name="barangay" value="<?= esc($address['barangay'] ?? '') ?>" class="form-control">
                                    </div>
                                </div>
                                
                                <!-- Student Information -->
                                <div class="form-section-header">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>Student Information</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Student ID</label>
                                        <input type="text" name="student_id" value="<?= esc($student['student_id'] ?? '') ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Year Level</label>
                                        <select name="year_level" class="form-control">
                                            <option value="">Select year level</option>
                                            <option value="1" <?= ($student['year_level'] ?? '') == '1' ? 'selected' : '' ?>>1st Year</option>
                                            <option value="2" <?= ($student['year_level'] ?? '') == '2' ? 'selected' : '' ?>>2nd Year</option>
                                            <option value="3" <?= ($student['year_level'] ?? '') == '3' ? 'selected' : '' ?>>3rd Year</option>
                                            <option value="4" <?= ($student['year_level'] ?? '') == '4' ? 'selected' : '' ?>>4th Year</option>
                                            <option value="5" <?= ($student['year_level'] ?? '') == '5' ? 'selected' : '' ?>>5th Year</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" name="department" value="<?= esc($student['department'] ?? '') ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Course/Program</label>
                                        <input type="text" name="course" value="<?= esc($student['course'] ?? '') ?>" class="form-control">
                                    </div>
                                    <?php if(isset($student['in_organization']) && $student['in_organization'] == 1): ?>
                                    <div class="form-group">
                                        <label>Organization</label>
                                        <input type="text" name="organization_name" value="<?= esc($student['organization_name'] ?? '') ?>" class="form-control">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Account Credentials -->
                                <div class="form-section-header">
                                    <i class="fas fa-lock"></i>
                                    <span>Account Credentials</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" value="<?= esc($user['email'] ?? '') ?>" class="form-control" disabled>
                                        <small class="form-hint">Email cannot be changed</small>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="button" class="btn-secondary" onclick="resetForm()">Cancel</button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart"></i> Your Cart</h3>
            <button class="close-cart" id="closeCart"><i class="fas fa-times"></i></button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-cart">
                <i class="fas fa-shopping-bag"></i>
                <p>Your cart is empty</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total</span>
                <span class="total-value" id="cartTotal">₱0.00</span>
            </div>
            <button class="btn-checkout" id="checkoutBtn" onclick="proceedToCheckout()">
                <i class="fas fa-credit-card"></i> Proceed to Checkout
            </button>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay"></div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Join Organization Modal -->
    <div class="modal-overlay" id="joinOrgModal" style="display: none;">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-users"></i> Available Organizations</h3>
                <button class="modal-close" onclick="closeJoinOrgModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="organizations-list" id="organizationsList">
                    <?php if(!empty($availableOrganizations)): ?>
                        <?php foreach($availableOrganizations as $org): ?>
                        <div class="org-card-modal">
                            <div class="org-card-header">
                                <div class="org-card-avatar"><?= esc(strtoupper($org['acronym'])) ?></div>
                                <div class="org-card-info">
                                    <h4><?= esc($org['name']) ?></h4>
                                    <span class="org-card-type"><?= esc($org['type']) ?></span>
                                </div>
                            </div>
                            <p class="org-card-description"><?= esc($org['description']) ?></p>
                            <div class="org-card-footer">
                                <span class="org-members"><i class="fas fa-users"></i> <?= number_format($org['members']) ?> members</span>
                                <button class="btn btn-primary" onclick="joinOrg(<?= $org['id'] ?>)">
                                    <i class="fas fa-plus"></i> Join Organization
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No organizations available at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal-overlay" id="eventDetailsModal" style="display: none;">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3 id="eventDetailsTitle"><i class="fas fa-calendar"></i> Event Details</h3>
                <button class="modal-close" onclick="closeEventDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="eventDetailsContent">
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #64748b;"></i>
                        <p style="margin-top: 1rem; color: #64748b;">Loading event details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const baseUrl = '<?= base_url() ?>';
        
        // Hide badges if user has already viewed the sections
        document.addEventListener('DOMContentLoaded', function() {
            // Hide events badge if already viewed
            const eventsBadgeViewed = localStorage.getItem('eventsBadgeViewed');
            if (eventsBadgeViewed === 'true') {
                const eventsNavBadge = document.getElementById('eventsNavBadge');
                const eventsMobileNavBadge = document.getElementById('eventsMobileNavBadge');
                if (eventsNavBadge) {
                    eventsNavBadge.style.display = 'none';
                }
                if (eventsMobileNavBadge) {
                    eventsMobileNavBadge.style.display = 'none';
                }
            }
            
            // Hide announcements badge if already viewed
            const announcementsBadgeViewed = localStorage.getItem('announcementsBadgeViewed');
            if (announcementsBadgeViewed === 'true') {
                const announcementsNavBadge = document.getElementById('announcementsNavBadge');
                const announcementsMobileNavBadge = document.getElementById('announcementsMobileNavBadge');
                if (announcementsNavBadge) {
                    announcementsNavBadge.style.display = 'none';
                }
                if (announcementsMobileNavBadge) {
                    announcementsMobileNavBadge.style.display = 'none';
                }
            }
        });
        
        // Logout Confirmation
        function confirmLogout(event) {
            event.preventDefault();
            
            // Create confirmation modal
            const overlay = document.createElement('div');
            overlay.className = 'logout-modal-overlay';
            overlay.innerHTML = `
                <div class="logout-modal">
                    <div class="logout-modal-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <h3>Confirm Logout</h3>
                    <p>Are you sure you want to logout from your account?</p>
                    <div class="logout-modal-actions">
                        <button class="btn-cancel" onclick="closeLogoutModal()">Cancel</button>
                        <button class="btn-confirm" onclick="performLogout()">Yes, Logout</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            setTimeout(() => overlay.classList.add('active'), 10);
        }
        
        function closeLogoutModal() {
            const overlay = document.querySelector('.logout-modal-overlay');
            if (overlay) {
                overlay.classList.remove('active');
                setTimeout(() => overlay.remove(), 300);
            }
        }
        
        function performLogout() {
            closeLogoutModal();
            window.location.href = baseUrl + 'student/logout';
        }
        
        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('logout-modal-overlay')) {
                closeLogoutModal();
            }
        });
        
        // Mobile Navigation
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const closeMobileNav = document.getElementById('closeMobileNav');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileNav.classList.add('open');
            mobileNavOverlay.classList.add('active');
        });
        
        closeMobileNav.addEventListener('click', closeMobileMenu);
        mobileNavOverlay.addEventListener('click', closeMobileMenu);
        
        function closeMobileMenu() {
            mobileNav.classList.remove('open');
            mobileNavOverlay.classList.remove('active');
        }

        // User Dropdown
        const userBtn = document.getElementById('userBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
            quickActionsDropdown.classList.remove('active');
        });
        
        // Quick Actions Dropdown
        const quickActionsBtn = document.getElementById('quickActionsBtn');
        const quickActionsDropdown = document.getElementById('quickActionsDropdown');
        const notificationPanel = document.getElementById('notificationPanel');
        const cartActionBtn = document.getElementById('cartActionBtn');
        const notificationActionBtn = document.getElementById('notificationActionBtn');
        const backToActions = document.getElementById('backToActions');
        const notifTabs = document.querySelectorAll('.notif-tab');
        
        quickActionsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            quickActionsDropdown.classList.toggle('active');
            dropdownMenu.classList.remove('active');
            // Reset to main view when opening
            notificationPanel.classList.remove('active');
        });
        
        // Cart action - opens cart sidebar
        cartActionBtn.addEventListener('click', () => {
            quickActionsDropdown.classList.remove('active');
            cartSidebar.classList.add('open');
            cartOverlay.classList.add('active');
        });
        
        // Notification action - shows notification panel
        notificationActionBtn.addEventListener('click', () => {
            notificationPanel.classList.add('active');
        });
        
        // Payments action - navigates to payments section
        const paymentsActionBtn = document.getElementById('paymentsActionBtn');
        paymentsActionBtn.addEventListener('click', () => {
            quickActionsDropdown.classList.remove('active');
            // Switch to payments tab
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.mobile-nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.dashboard-section').forEach(section => section.classList.remove('active'));
            document.getElementById('payments').classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Back button in notification panel
        backToActions.addEventListener('click', () => {
            notificationPanel.classList.remove('active');
        });
        
        // Notification tabs
        notifTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                notifTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                filterNotifications(tab.dataset.type);
            });
        });
        
        function filterNotifications(type) {
            const items = document.querySelectorAll('.notification-item');
            items.forEach(item => {
                if (type === 'all') {
                    item.style.display = 'flex';
                } else if (type === 'unread') {
                    item.style.display = item.classList.contains('unread') ? 'flex' : 'none';
                }
            });
        }
        
        document.getElementById('markAllRead').addEventListener('click', () => {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            updateNotificationCount();
            showToast('All notifications marked as read', 'success');
        });
        
        function updateNotificationCount() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const notifBadge = document.getElementById('notificationCount');
            const combinedBadge = document.getElementById('combinedBadge');
            const cartCount = parseInt(document.getElementById('cartCount').textContent) || 0;
            
            notifBadge.textContent = unreadCount;
            notifBadge.style.display = unreadCount > 0 ? 'flex' : 'none';
            
            // Update combined badge
            const totalCount = unreadCount + cartCount;
            combinedBadge.textContent = totalCount;
            combinedBadge.style.display = totalCount > 0 ? 'flex' : 'none';
        }
        
        function updateCombinedBadge() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const cartCount = parseInt(document.getElementById('cartCount').textContent) || 0;
            const pendingPayments = parseInt(document.getElementById('pendingPaymentCount').textContent) || 0;
            const combinedBadge = document.getElementById('combinedBadge');
            
            const totalCount = unreadCount + cartCount + pendingPayments;
            combinedBadge.textContent = totalCount;
            combinedBadge.style.display = totalCount > 0 ? 'flex' : 'none';
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!quickActionsDropdown.contains(e.target) && !quickActionsBtn.contains(e.target)) {
                quickActionsDropdown.classList.remove('active');
                notificationPanel.classList.remove('active');
            }
            if (!dropdownMenu.contains(e.target) && !userBtn.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });

        // Navigation
        const navLinks = document.querySelectorAll('.nav-link[data-section], .mobile-nav-link[data-section], .dropdown-item[data-section]');
        const sections = document.querySelectorAll('.content-section');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const sectionId = link.dataset.section;
                switchSection(sectionId);
                closeMobileMenu();
                dropdownMenu.classList.remove('active');
            });
        });

        function switchSection(sectionId) {
            // Update nav
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.mobile-nav-link').forEach(link => link.classList.remove('active'));
            document.querySelector(`.nav-link[data-section="${sectionId}"]`)?.classList.add('active');
            document.querySelector(`.mobile-nav-link[data-section="${sectionId}"]`)?.classList.add('active');
            
            // Update sections
            sections.forEach(section => section.classList.remove('active'));
            document.getElementById(sectionId)?.classList.add('active');
            
            // Hide events badge when events section is viewed
            if (sectionId === 'events') {
                const eventsNavBadge = document.getElementById('eventsNavBadge');
                const eventsMobileNavBadge = document.getElementById('eventsMobileNavBadge');
                
                if (eventsNavBadge) {
                    eventsNavBadge.style.display = 'none';
                    localStorage.setItem('eventsBadgeViewed', 'true');
                }
                if (eventsMobileNavBadge) {
                    eventsMobileNavBadge.style.display = 'none';
                    localStorage.setItem('eventsBadgeViewed', 'true');
                }
                
                setTimeout(() => {
                    initializeEventFilter();
                }, 100);
            }
            
            // Hide announcements badge when announcements section is viewed
            if (sectionId === 'announcements') {
                const announcementsNavBadge = document.getElementById('announcementsNavBadge');
                const announcementsMobileNavBadge = document.getElementById('announcementsMobileNavBadge');
                
                if (announcementsNavBadge) {
                    announcementsNavBadge.style.display = 'none';
                    localStorage.setItem('announcementsBadgeViewed', 'true');
                }
                if (announcementsMobileNavBadge) {
                    announcementsMobileNavBadge.style.display = 'none';
                    localStorage.setItem('announcementsBadgeViewed', 'true');
                }
            }
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Payment Tabs
        const paymentTabs = document.querySelectorAll('.payment-tab');
        const paymentContents = document.querySelectorAll('.payment-content');
        
        paymentTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                paymentTabs.forEach(t => t.classList.remove('active'));
                paymentContents.forEach(c => c.classList.remove('active'));
                
                tab.classList.add('active');
                document.getElementById(`${tab.dataset.tab}-content`).classList.add('active');
            });
        });

        // Cart Functionality
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        const closeCart = document.getElementById('closeCart');
        let cart = [];

        closeCart.addEventListener('click', closeCartSidebar);
        cartOverlay.addEventListener('click', closeCartSidebar);

        function closeCartSidebar() {
            cartSidebar.classList.remove('open');
            cartOverlay.classList.remove('active');
        }

        function addToCart(productId) {
            fetch(baseUrl + 'student/cart/manage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `action=add&product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updateCartCount(data.cart_count);
                    loadCartItems();
                }
            });
        }

        function updateCartCount(count) {
            document.getElementById('cartCount').textContent = count;
            updateCombinedBadge();
        }

        function loadCartItems() {
            fetch(baseUrl + 'student/cart', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                renderCartItems(data.cart_items, data.total);
            });
        }

        function renderCartItems(items, total) {
            const cartItemsContainer = document.getElementById('cartItems');
            
            if (items.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Your cart is empty</p>
                    </div>
                `;
            } else {
                cartItemsContainer.innerHTML = items.map(item => `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <h4>${item.name}</h4>
                            <p>${item.organization}</p>
                            <span class="cart-item-price">₱${item.price.toFixed(2)}</span>
                        </div>
                        <div class="cart-item-actions">
                            <div class="quantity-control">
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                                <span>${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                            </div>
                            <button class="remove-item" onclick="removeFromCart(${item.product_id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
            
            document.getElementById('cartTotal').textContent = `₱${total.toFixed(2)}`;
        }

        // Event Filter Functions
        const eventFilter = document.getElementById('eventFilter');
        if (eventFilter) {
            eventFilter.addEventListener('change', function() {
                filterEvents(this.value);
            });
        }

        function filterEvents(filterType) {
            const eventCards = document.querySelectorAll('.event-card');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            let visibleCount = 0;
            
            eventCards.forEach(card => {
                const hasJoined = card.getAttribute('data-has-joined') === 'true';
                const eventDateStr = card.getAttribute('data-event-date');
                const eventDate = new Date(eventDateStr);
                eventDate.setHours(0, 0, 0, 0);
                
                let shouldShow = false;
                
                switch(filterType) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'upcoming':
                        // Show events where date is today or in the future
                        shouldShow = eventDate >= today;
                        break;
                    case 'joined':
                        // Show only events the student has joined
                        shouldShow = hasJoined;
                        break;
                    default:
                        shouldShow = true;
                }
                
                if (shouldShow) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show empty state if no events match the filter
            const eventsGrid = document.getElementById('eventsGrid');
            const emptyState = eventsGrid.querySelector('.empty-events-state');
            
            if (visibleCount === 0) {
                if (!emptyState) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-events-state';
                    emptyDiv.style.cssText = 'grid-column: 1 / -1; text-align: center; padding: 3rem;';
                    emptyDiv.innerHTML = `
                        <div style="color: var(--gray-500);">
                            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <h3 style="margin-bottom: 0.5rem;">No Events Found</h3>
                            <p>No events match the selected filter.</p>
                        </div>
                    `;
                    eventsGrid.appendChild(emptyDiv);
                } else {
                    emptyState.style.display = 'block';
                }
            } else {
                if (emptyState) {
                    emptyState.style.display = 'none';
                }
            }
        }

        // Initialize event filter when events section is loaded
        function initializeEventFilter() {
            const eventFilter = document.getElementById('eventFilter');
            if (eventFilter) {
                // Apply initial filter (defaults to 'all')
                filterEvents(eventFilter.value || 'all');
            }
        }

        // Event Functions
        function joinEvent(eventId) {
            const button = event.target.closest('button');
            const originalHtml = button.innerHTML;
            
            // Disable button during request
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Joining...';
            
            fetch(baseUrl + 'student/events/join', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.has_joined) {
                    // Update button to show joined state
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    button.style.backgroundColor = '#10b981';
                    button.style.border = 'none';
                    button.style.borderRadius = '25px';
                    button.style.padding = '0.5rem 1.25rem';
                    button.style.fontWeight = '500';
                    button.style.color = 'white';
                    button.style.display = 'inline-flex';
                    button.style.alignItems = 'center';
                    button.style.gap = '0.5rem';
                    button.style.boxShadow = '0 2px 4px rgba(16, 185, 129, 0.2)';
                    button.innerHTML = '<i class="fas fa-check"></i> <span>Joined</span>';
                    
                    // Update attendees count if provided
                    if (data.attendees !== undefined) {
                        const attendeesElement = document.querySelector(`[data-event-id="${eventId}"] .event-attendees`);
                        if (attendeesElement) {
                            attendeesElement.textContent = `${data.attendees} going`;
                        }
                    }
                    
                    // Update the event card's data attribute
                    const eventCard = document.querySelector(`[data-event-id="${eventId}"]`);
                    if (eventCard) {
                        eventCard.setAttribute('data-has-joined', 'true');
                    }
                    
                    // Refresh filter if active
                    const eventFilter = document.getElementById('eventFilter');
                    if (eventFilter && eventFilter.value) {
                        filterEvents(eventFilter.value);
                    }
                } else {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
                showToast(data.message, data.success ? 'success' : 'error');
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.innerHTML = originalHtml;
                showToast('An error occurred. Please try again.', 'error');
            });
        }

        function viewEventDetails(eventId) {
            const modal = document.getElementById('eventDetailsModal');
            const content = document.getElementById('eventDetailsContent');
            const title = document.getElementById('eventDetailsTitle');
            
            // Show modal with loading state
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
            content.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #64748b;"></i>
                    <p style="margin-top: 1rem; color: #64748b;">Loading event details...</p>
                </div>
            `;
            
            // Fetch event details
            fetch(baseUrl + 'student/events/get/' + eventId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        const event = data.data;
                        
                        // Update modal title
                        title.innerHTML = `<i class="fas fa-calendar"></i> ${event.title || 'Event Details'}`;
                        
                        // Build event details HTML
                        let html = '';
                        
                        // Event image if available
                        if (event.image) {
                            html += `
                                <div style="margin-bottom: 1.5rem;">
                                    <img src="${event.image}" alt="${event.title}" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px;" onerror="this.style.display='none';">
                                </div>
                            `;
                        }
                        
                        // Event description
                        if (event.description) {
                            html += `
                                <div style="margin-bottom: 1.5rem;">
                                    <h4 style="margin-bottom: 0.5rem; color: #1e293b; font-size: 1rem; font-weight: 600;">Description</h4>
                                    <p style="color: #64748b; line-height: 1.6; white-space: pre-wrap;">${event.description}</p>
                                </div>
                            `;
                        }
                        
                        // Event details grid
                        html += `
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-calendar" style="color: #06b6d4;"></i>
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Date</span>
                                    </div>
                                    <p style="color: #64748b; margin: 0; font-size: 0.875rem;">${event.date_formatted || 'N/A'}</p>
                                </div>
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-clock" style="color: #06b6d4;"></i>
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Time</span>
                                    </div>
                                    <p style="color: #64748b; margin: 0; font-size: 0.875rem;">${event.time || 'N/A'}</p>
                                </div>
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-map-marker-alt" style="color: #06b6d4;"></i>
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Location</span>
                                    </div>
                                    <p style="color: #64748b; margin: 0; font-size: 0.875rem;">${event.location || 'N/A'}</p>
                                </div>
                            </div>
                        `;
                        
                        // Attendees info
                        if (event.max_attendees) {
                            html += `
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-users" style="color: #06b6d4;"></i>
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Attendees</span>
                                    </div>
                                    <p style="color: #64748b; margin: 0; font-size: 0.875rem;">${event.current_attendees || 0} / ${event.max_attendees} registered</p>
                                </div>
                            `;
                        } else {
                            html += `
                                <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-users" style="color: #06b6d4;"></i>
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Attendees</span>
                                    </div>
                                    <p style="color: #64748b; margin: 0; font-size: 0.875rem;">${event.current_attendees || 0} registered</p>
                                </div>
                            `;
                        }
                        
                        // Organization info
                        html += `
                            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-building" style="color: #06b6d4;"></i>
                                    <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">Organized by</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    ${event.org_photo ? `
                                        <img src="${event.org_photo}" alt="${event.org_name}" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                    ` : `
                                        <div style="width: 40px; height: 40px; border-radius: 6px; background: #06b6d4; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            ${(event.org_acronym || 'ORG').substring(0, 2).toUpperCase()}
                                        </div>
                                    `}
                                    <div>
                                        <p style="margin: 0; font-weight: 600; color: #1e293b; font-size: 0.875rem;">${event.org_name || 'N/A'}</p>
                                        <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.75rem;">${event.org_type || 'Organization'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Action buttons (view only - no functionality)
                        html += `
                            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                                ${event.can_join ? (
                                    event.has_joined ? `
                                        <button class="btn-primary" disabled style="background-color: #10b981; border-color: #10b981; flex: 1; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; color: white; border: none; cursor: default; opacity: 1;">
                                            <i class="fas fa-check" style="margin-right: 0.5rem;"></i> Joined
                                        </button>
                                    ` : `
                                        <button class="btn-primary" disabled style="flex: 1; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; color: white; border: none; cursor: default; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); opacity: 0.8;">
                                            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Join Event
                                        </button>
                                    `
                                ) : `
                                    <button class="btn-secondary" disabled style="flex: 1; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; opacity: 0.6; cursor: not-allowed; background: #e2e8f0; color: #64748b; border: none;">
                                        <i class="fas fa-lock" style="margin-right: 0.5rem;"></i> Invitation Only
                                    </button>
                                `}
                            </div>
                        `;
                        
                        content.innerHTML = html;
                    } else {
                        content.innerHTML = `
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-exclamation-circle" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem;"></i>
                                <p style="color: #64748b;">${data.message || 'Failed to load event details. Please try again.'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div style="text-align: center; padding: 2rem;">
                            <i class="fas fa-exclamation-circle" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem;"></i>
                            <p style="color: #64748b;">An error occurred while loading event details. Please try again.</p>
                        </div>
                    `;
                });
        }

        function closeEventDetailsModal() {
            const modal = document.getElementById('eventDetailsModal');
            modal.style.display = 'none';
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('eventDetailsModal');
            if (e.target === modal) {
                closeEventDetailsModal();
            }
        });

        function toggleInterested(eventId) {
            const button = event.target.closest('.interested-btn');
            if (!button) return;
            
            const originalHtml = button.innerHTML;
            const isCurrentlyInterested = button.style.backgroundColor === 'rgb(254, 243, 199)' || button.style.backgroundColor === '#fef3c7';
            
            // Disable button during request
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>';
            
            fetch(baseUrl + 'student/events/interested', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                if (data.success) {
                    if (data.is_interested) {
                        // Mark as interested
                        button.style.backgroundColor = '#fef3c7';
                        button.style.borderColor = '#fbbf24';
                        button.style.color = '#92400e';
                        const starIcon = button.querySelector('.fa-star');
                        if (starIcon) {
                            starIcon.classList.remove('far');
                            starIcon.classList.add('fas');
                        }
                    } else {
                        // Remove interested
                        button.style.backgroundColor = '';
                        button.style.borderColor = '';
                        button.style.color = '';
                        const starIcon = button.querySelector('.fa-star');
                        if (starIcon) {
                            starIcon.classList.remove('fas');
                            starIcon.classList.add('far');
                        }
                    }
                    showToast(data.message, 'success');
                } else {
                    button.innerHTML = originalHtml;
                    showToast(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.innerHTML = originalHtml;
                showToast('An error occurred. Please try again.', 'error');
            });
        }

        // Organization Functions
        function showAvailableOrganizations() {
            const joinCard = document.getElementById('joinOrgCard');
            const orgsContainer = document.getElementById('availableOrgsContainer');
            
            if (joinCard && orgsContainer) {
                joinCard.style.display = 'none';
                orgsContainer.style.display = 'block';
                // Smooth scroll to organizations
                setTimeout(() => {
                    orgsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        function hideAvailableOrganizations() {
            const joinCard = document.getElementById('joinOrgCard');
            const orgsContainer = document.getElementById('availableOrgsContainer');
            
            if (joinCard && orgsContainer) {
                orgsContainer.style.display = 'none';
                joinCard.style.display = 'block';
                // Smooth scroll back to join card
                setTimeout(() => {
                    joinCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        function openJoinOrgModal() {
            const modal = document.getElementById('joinOrgModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => modal.classList.add('active'), 10);
            }
        }

        function closeJoinOrgModal() {
            const modal = document.getElementById('joinOrgModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => modal.style.display = 'none', 300);
            }
        }

        // Close modal when clicking overlay
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('joinOrgModal');
            if (e.target === modal) {
                closeJoinOrgModal();
            }
        });

        function followOrg(orgId) {
            const followBtn = document.getElementById('followBtn_' + orgId);
            if (!followBtn) return;

            const originalText = followBtn.innerHTML;
            followBtn.disabled = true;
            followBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(baseUrl + 'student/followOrg', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'org_id=' + orgId
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message || 'Failed to follow organization', data.success ? 'success' : 'error');
                if (data.success) {
                    followBtn.innerHTML = '<i class="fas fa-check"></i> Following';
                    followBtn.className = 'btn-secondary';
                    followBtn.setAttribute('onclick', 'unfollowOrg(' + orgId + ')');
                } else {
                    followBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                showToast('An error occurred while following the organization', 'error');
                followBtn.innerHTML = originalText;
            })
            .finally(() => {
                followBtn.disabled = false;
            });
        }

        function unfollowOrg(orgId) {
            const followBtn = document.getElementById('followBtn_' + orgId);
            if (!followBtn) return;

            const originalText = followBtn.innerHTML;
            followBtn.disabled = true;
            followBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(baseUrl + 'student/unfollowOrg', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'org_id=' + orgId
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message || 'Failed to unfollow organization', data.success ? 'success' : 'error');
                if (data.success) {
                    followBtn.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
                    followBtn.className = 'btn-outline';
                    followBtn.setAttribute('onclick', 'followOrg(' + orgId + ')');
                } else {
                    followBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                showToast('An error occurred while unfollowing the organization', 'error');
                followBtn.innerHTML = originalText;
            })
            .finally(() => {
                followBtn.disabled = false;
            });
        }

        function joinOrg(orgId) {
            fetch(baseUrl + 'student/organizations/join', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `org_id=${orgId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeJoinOrgModal();
                    hideAvailableOrganizations();
                    // Reload page to update dashboard
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Failed to join organization', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        }

        function viewOrgDetails(orgId) {
            showToast('Organization details coming soon!', 'info');
        }

        // Payment Functions
        function initiatePayment(paymentId) {
            showToast('Payment gateway coming soon!', 'info');
        }

        function proceedToCheckout() {
            showToast('Checkout page coming soon!', 'info');
        }

        // Comment Functions
        function toggleComments(postId, postType) {
            const commentsSection = document.getElementById(`comments-${postType}-${postId}`);
            const commentsList = document.getElementById(`comments-list-${postType}-${postId}`);
            
            if (commentsSection.style.display === 'none' || !commentsSection.style.display) {
                commentsSection.style.display = 'block';
                loadComments(postId, postType);
            } else {
                commentsSection.style.display = 'none';
            }
        }

        function loadComments(postId, postType) {
            const commentsList = document.getElementById(`comments-list-${postType}-${postId}`);
            if (!commentsList) return;
            
            fetch(baseUrl + `student/getComments?type=${postType}&post_id=${postId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentsList.innerHTML = '';
                    if (data.comments.length === 0) {
                        commentsList.innerHTML = '<p style="padding: 1rem; color: #64748b; text-align: center; font-size: 0.875rem;">No comments yet. Be the first to comment!</p>';
                    } else {
                        // Show first 2 comments, hide the rest
                        const totalComments = data.comments.length;
                        const visibleComments = totalComments > 2 ? data.comments.slice(0, 2) : data.comments;
                        const hiddenComments = totalComments > 2 ? data.comments.slice(2) : [];
                        
                        visibleComments.forEach(comment => {
                            const commentDiv = createCommentElement(comment, postType, postId);
                            commentsList.appendChild(commentDiv);
                        });
                        
                        // Add "See more" button if there are more than 2 comments
                        if (totalComments > 2) {
                            const seeMoreBtn = document.createElement('button');
                            seeMoreBtn.className = 'see-more-comments';
                            seeMoreBtn.textContent = `See more comments (${hiddenComments.length} more)`;
                            seeMoreBtn.style.cssText = 'width: 100%; padding: 0.75rem; margin-top: 0.5rem; background: transparent; border: 1px solid #e2e8f0; border-radius: 8px; color: #3b82f6; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s;';
                            seeMoreBtn.onmouseenter = function() { this.style.backgroundColor = '#f1f5f9'; };
                            seeMoreBtn.onmouseleave = function() { this.style.backgroundColor = 'transparent'; };
                            seeMoreBtn.onclick = function() {
                                hiddenComments.forEach(comment => {
                                    const commentDiv = createCommentElement(comment, postType, postId);
                                    commentsList.insertBefore(commentDiv, seeMoreBtn);
                                });
                                seeMoreBtn.remove();
                            };
                            commentsList.appendChild(seeMoreBtn);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
            });
        }

        function formatTime(dateString) {
            if (!dateString) return 'Just now';
            
            const date = new Date(dateString);
            
            // Check if date is valid
            if (isNaN(date.getTime())) {
                // If invalid, try to parse as MySQL datetime format
                const mysqlDate = new Date(dateString.replace(' ', 'T'));
                if (isNaN(mysqlDate.getTime())) {
                    return 'Just now';
                }
                return formatRelativeTime(mysqlDate);
            }
            
            return formatRelativeTime(date);
        }
        
        function formatRelativeTime(date) {
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);
            
            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            if (days < 7) return `${days}d ago`;
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function renderReplies(replies, postType, postId) {
            let html = '';
            replies.forEach(reply => {
                const replyUserName = reply.user_name || 'User';
                const replyId = reply.id;
                const hasNestedReplies = reply.replies && reply.replies.length > 0;
                
                html += `
                    <div class="comment-item" style="padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; gap: 0.75rem;">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; flex-shrink: 0;">
                                ${replyUserName.charAt(0).toUpperCase()}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.8125rem; color: #1e293b; margin-bottom: 0.25rem;">${replyUserName}</div>
                                <div style="color: #475569; font-size: 0.8125rem; margin-bottom: 0.25rem;">${reply.content}</div>
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                                    <div style="color: #94a3b8; font-size: 0.7rem;">${formatTime(reply.created_at)}</div>
                                    <button onclick="showReplyInput(${replyId}, '${postType}', ${postId})" style="background: none; border: none; color: #3b82f6; font-size: 0.7rem; cursor: pointer; font-weight: 500; padding: 0;">Reply</button>
                                </div>
                                <div id="reply-input-${replyId}" style="display: none; margin-top: 0.75rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <input type="text" id="reply-text-${replyId}" placeholder="Write a reply..." style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                                        <button onclick="postReply(${replyId}, '${postType}', ${postId})" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.875rem;">Send</button>
                                    </div>
                                </div>
                                ${hasNestedReplies ? '<div class="comment-replies" style="margin-left: 2rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">' + renderReplies(reply.replies, postType, postId) + '</div>' : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            return html;
        }

        function createCommentElement(comment, postType, postId) {
            const commentDiv = document.createElement('div');
            commentDiv.className = 'comment-item';
            commentDiv.style.cssText = 'padding: 0.75rem; border-bottom: 1px solid #e2e8f0;';
            const userName = comment.user_name || 'User';
            const commentId = comment.id;
            const hasReplies = comment.replies && comment.replies.length > 0;
            
            let repliesHtml = '';
            if (hasReplies) {
                repliesHtml = '<div class="comment-replies" style="margin-left: 2.5rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #e2e8f0;">';
                repliesHtml += renderReplies(comment.replies, postType, postId);
                repliesHtml += '</div>';
            }
            
            commentDiv.innerHTML = `
                <div style="display: flex; gap: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem; flex-shrink: 0;">
                        ${userName.charAt(0).toUpperCase()}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.875rem; color: #1e293b; margin-bottom: 0.25rem;">${userName}</div>
                        <div style="color: #475569; font-size: 0.875rem; margin-bottom: 0.25rem;">${comment.content}</div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                            <div style="color: #94a3b8; font-size: 0.75rem;">${formatTime(comment.created_at)}</div>
                            <button onclick="showReplyInput(${commentId}, '${postType}', ${postId})" style="background: none; border: none; color: #3b82f6; font-size: 0.75rem; cursor: pointer; font-weight: 500; padding: 0;">Reply</button>
                        </div>
                        <div id="reply-input-${commentId}" style="display: none; margin-top: 0.75rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="text" id="reply-text-${commentId}" placeholder="Write a reply..." style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                                <button onclick="postReply(${commentId}, '${postType}', ${postId})" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.875rem;">Send</button>
                            </div>
                        </div>
                        ${repliesHtml}
                    </div>
                </div>
            `;
            
            return commentDiv;
        }

        function showReplyInput(commentId, postType, postId) {
            const replyInput = document.getElementById(`reply-input-${commentId}`);
            if (replyInput) {
                replyInput.style.display = replyInput.style.display === 'none' ? 'block' : 'none';
            }
        }

        function postReply(parentCommentId, postType, postId) {
            const input = document.getElementById(`reply-text-${parentCommentId}`);
            if (!input) return;

            const content = input.value.trim();
            if (!content) {
                showToast('Please enter a reply', 'error');
                return;
            }

            fetch(baseUrl + 'student/comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `content=${encodeURIComponent(content)}&type=${postType}&target_id=${postId}&parent_comment_id=${parentCommentId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    input.value = '';
                    document.getElementById(`reply-input-${parentCommentId}`).style.display = 'none';
                    loadComments(postId, postType);
                } else {
                    showToast(data.message || 'Failed to post reply', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while posting reply', 'error');
            });
        }

        function postComment(postId, postType) {
            const input = document.getElementById(`comment-input-${postType}-${postId}`);
            if (!input) return;
            
            const content = input.value.trim();
            
            if (!content) {
                showToast('Please enter a comment', 'error');
                return;
            }

            fetch(baseUrl + 'student/comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `content=${encodeURIComponent(content)}&type=${postType}&target_id=${postId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    input.value = '';
                    loadComments(postId, postType);
                    // Update comment count
                    const commentBtn = document.querySelector(`.comment-btn[onclick*="${postId}"]`);
                    if (commentBtn) {
                        const countSpan = commentBtn.querySelector('.comment-count');
                        const currentCount = countSpan ? parseInt(countSpan.textContent) || 0 : 0;
                        if (countSpan) {
                            countSpan.textContent = currentCount + 1;
                        } else {
                            const newCount = document.createElement('span');
                            newCount.className = 'comment-count';
                            newCount.textContent = currentCount + 1;
                            commentBtn.appendChild(newCount);
                        }
                    }
                } else {
                    showToast(data.message || 'Failed to post comment', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while posting comment', 'error');
            });
        }

        // Reaction functions
        const reactionIcons = {
            'like': '👍',
            'love': '❤️',
            'care': '🥰',
            'haha': '😂',
            'wow': '😮',
            'sad': '😢',
            'angry': '😠'
        };
        

        function showReactionPicker(button) {
            const wrapper = button.closest('.reaction-wrapper');
            if (wrapper) {
                const picker = wrapper.querySelector('.reaction-picker');
                if (picker) {
                    picker.style.display = 'flex';
                }
            }
        }

        function hideReactionPicker(button) {
            const wrapper = button.closest('.reaction-wrapper');
            if (wrapper) {
                const picker = wrapper.querySelector('.reaction-picker');
                if (picker) {
                    // Delay hiding to allow clicking on reactions
                    setTimeout(() => {
                        if (!picker.matches(':hover') && !button.matches(':hover')) {
                            picker.style.display = 'none';
                        }
                    }, 200);
                }
            }
        }

        // Hide all reaction pickers
        function hideAllReactionPickers() {
            const allPickers = document.querySelectorAll('.reaction-picker');
            allPickers.forEach(picker => {
                picker.style.display = 'none';
            });
        }

        // Hide reaction pickers on scroll
        window.addEventListener('scroll', function() {
            hideAllReactionPickers();
        }, true);

        // Hide reaction pickers when clicking outside
        document.addEventListener('click', function(event) {
            const clickedElement = event.target;
            const isReactionButton = clickedElement.closest('.reaction-btn');
            const isReactionPicker = clickedElement.closest('.reaction-picker');
            const isReactionOption = clickedElement.closest('.reaction-option');
            
            // If click is not on reaction button, picker, or option, hide all pickers
            if (!isReactionButton && !isReactionPicker && !isReactionOption) {
                hideAllReactionPickers();
            }
        });

        function quickReact(postType, postId, button, currentReaction) {
            // If already reacted, remove reaction. Otherwise, add like reaction
            if (currentReaction) {
                setReaction(postType, postId, 'like', button); // Toggle off
            } else {
                setReaction(postType, postId, 'like', button);
            }
        }

        function setReaction(postType, postId, reactionType, button) {
            const wrapper = button.closest('.reaction-wrapper');
            const reactionIcon = wrapper ? wrapper.querySelector('.reaction-icon') : null;
            const reactionCount = wrapper ? wrapper.querySelector('.reaction-count') : null;
            
            fetch(baseUrl + 'student/likePost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `type=${postType}&post_id=${postId}&reaction_type=${reactionType}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    if (data.reacted && data.reaction_type) {
                        // User reacted
                        button.classList.add('reacted', 'reaction-' + data.reaction_type);
                        button.classList.remove('reaction-like', 'reaction-love', 'reaction-care', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');
                        button.classList.add('reaction-' + data.reaction_type);
                        
                        if (reactionIcon) {
                            reactionIcon.textContent = reactionIcons[data.reaction_type] || '👍';
                        }
                    } else {
                        // User removed reaction
                        button.classList.remove('reacted', 'reaction-like', 'reaction-love', 'reaction-care', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');
                        
                        if (reactionIcon) {
                            reactionIcon.textContent = '👍';
                        }
                    }
                    
                    // Update count
                    const totalCount = data.counts ? data.counts.total : 0;
                    if (totalCount > 0) {
                        if (reactionCount) {
                            reactionCount.textContent = totalCount;
                        } else {
                            const newCount = document.createElement('span');
                            newCount.className = 'reaction-count';
                            newCount.textContent = totalCount;
                            button.appendChild(newCount);
                        }
                    } else {
                        if (reactionCount) {
                            reactionCount.remove();
                        }
                    }
                    
                    // Hide picker
                    const picker = wrapper ? wrapper.querySelector('.reaction-picker') : null;
                    if (picker) {
                        picker.style.display = 'none';
                    }
                } else {
                    showToast(data.message || 'Failed to react to post', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while reacting to post', 'error');
            });
        }

        // Profile Form
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            submitBtn.disabled = true;
            
            fetch(baseUrl + 'student/updateProfile', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => {
                showToast('An error occurred while saving your profile', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Reset Form (Cancel button)
        function resetForm() {
            document.getElementById('profileForm').reset();
            showToast('Changes discarded', 'info');
            setTimeout(() => {
                // Navigate to overview (dashboard) section
                switchSection('overview');
            }, 1500);
        }
        
        // Profile Photo Upload
        document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
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
                
                // Preview the image
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('profilePhotoPreview');
                    const icon = document.getElementById('profilePhotoIcon');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    if (icon) icon.style.display = 'none';
                    
                    // Update left sidebar profile photo
                    const sidebarPhoto = document.getElementById('sidebarProfilePhoto');
                    const sidebarPlaceholder = document.getElementById('sidebarProfilePlaceholder');
                    if (sidebarPhoto) {
                        sidebarPhoto.src = event.target.result;
                        sidebarPhoto.style.display = 'block';
                        if (sidebarPlaceholder) sidebarPlaceholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
                
                // Upload the photo
                const formData = new FormData();
                formData.append('photo', file);
                
                fetch(baseUrl + 'student/uploadPhoto', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        // Update left sidebar with the new photo URL if provided
                        if (data.photo) {
                            const sidebarPhoto = document.getElementById('sidebarProfilePhoto');
                            const sidebarPlaceholder = document.getElementById('sidebarProfilePlaceholder');
                            if (sidebarPhoto) {
                                sidebarPhoto.src = data.photo;
                                sidebarPhoto.style.display = 'block';
                                if (sidebarPlaceholder) sidebarPlaceholder.style.display = 'none';
                            }
                        }
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(error => {
                    showToast('An error occurred while uploading photo', 'error');
                });
            }
        });

        // Toast Notification
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            toast.innerHTML = `
                <i class="${icons[type]}"></i>
                <span>${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Update current date automatically
        function updateCurrentDate() {
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateElement.textContent = now.toLocaleDateString('en-US', options);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCurrentDate();
            loadCartItems();
            updateCombinedBadge();
        });
    </script>

    <style>
        /* Reaction Picker Styles */
        .reaction-wrapper {
            position: relative;
            display: inline-block;
        }

        .reaction-btn {
            position: relative;
            transition: all 0.2s ease;
        }

        .reaction-btn:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .reaction-btn.reacted {
            color: #3b82f6;
            font-weight: 600;
        }

        .reaction-btn.reaction-love {
            color: #ef4444;
        }

        .reaction-btn.reaction-care {
            color: #f59e0b;
        }

        .reaction-btn.reaction-haha {
            color: #f59e0b;
        }

        .reaction-btn.reaction-wow {
            color: #f59e0b;
        }

        .reaction-btn.reaction-sad {
            color: #3b82f6;
        }

        .reaction-btn.reaction-angry {
            color: #ef4444;
        }

        .reaction-icon {
            font-size: 1.1em;
            margin-right: 0.25rem;
        }

        .reaction-text {
            font-size: 0.875rem;
        }

        .reaction-count {
            margin-left: 0.25rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .reaction-picker {
            position: absolute;
            bottom: 100%;
            left: 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 30px;
            padding: 0.5rem 0.35rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 0.15rem;
            z-index: 1000;
            margin-bottom: 0.75rem;
            animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(10px);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .reaction-option {
            width: 48px;
            height: 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.9rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            padding: 0.3rem;
        }

        .reaction-option:hover {
            transform: scale(1.25) translateY(-3px);
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .reaction-option::before {
            content: attr(data-reaction);
            position: absolute;
            bottom: calc(100% + 0.6rem);
            left: 50%;
            transform: translateX(-50%) translateY(5px);
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-transform: capitalize;
            font-weight: 600;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .reaction-option:hover::before {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .reaction-option::after {
            content: '';
            position: absolute;
            bottom: calc(100% - 0.2rem);
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #1e293b;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .reaction-option:hover::after {
            opacity: 1;
        }

        .reaction-option[data-reaction="like"]:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .reaction-option[data-reaction="love"]:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .reaction-option[data-reaction="care"]:hover {
            background: rgba(245, 158, 11, 0.1);
        }

        .reaction-option[data-reaction="haha"]:hover {
            background: rgba(245, 158, 11, 0.1);
        }

        .reaction-option[data-reaction="wow"]:hover {
            background: rgba(245, 158, 11, 0.1);
        }

        .reaction-option[data-reaction="sad"]:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .reaction-option[data-reaction="angry"]:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .reaction-label {
            font-size: 0.65rem;
            color: #475569;
            margin-top: 0.15rem;
            font-weight: 600;
            text-align: center;
            line-height: 1;
            letter-spacing: 0.2px;
            transition: color 0.2s ease;
        }

        .reaction-option:hover .reaction-label {
            color: #1e293b;
        }

        .reaction-option span:first-child {
            font-size: 1.9rem;
            line-height: 1;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: filter 0.3s ease;
        }

        .reaction-option:hover span:first-child {
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
        }
    </style>
</body>
</html>
