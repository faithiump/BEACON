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
                    <div class="logo-icon">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
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
                        <span class="nav-badge">3</span>
                    </a>
                    <a href="#announcements" class="nav-link" data-section="announcements">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                    <a href="#organizations" class="nav-link" data-section="organizations">
                        <i class="fas fa-users"></i>
                        <span>Organizations</span>
                    </a>
                    <a href="#shop" class="nav-link" data-section="shop">
                        <i class="fas fa-store"></i>
                        <span>Shop</span>
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
                            <a href="<?= base_url('student/logout') ?>" class="dropdown-item logout">
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
                <div class="logo-icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
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
                    <span class="nav-badge">3</span>
                </a>
                <a href="#announcements" class="mobile-nav-link" data-section="announcements">
                    <i class="fas fa-bullhorn"></i> Announcements
                </a>
                <a href="#organizations" class="mobile-nav-link" data-section="organizations">
                    <i class="fas fa-users"></i> Organizations
                </a>
                <a href="#shop" class="mobile-nav-link" data-section="shop">
                    <i class="fas fa-store"></i> Shop
                </a>
                <div class="mobile-nav-divider"></div>
                <a href="#profile" class="mobile-nav-link" data-section="profile">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a href="<?= base_url('student/logout') ?>" class="mobile-nav-link logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-area">
                <!-- Overview Section -->
                <section class="content-section active" id="overview">
                    <div class="section-header">
                        <div>
                            <h1 class="section-title">Welcome back, <?= esc($profile['firstname'] ?? 'Student') ?>!</h1>
                            <p class="section-subtitle">Here's what's happening in your campus community</p>
                        </div>
                        <div class="date-display">
                            <i class="fas fa-calendar-day"></i>
                            <span><?= date('l, F j, Y') ?></span>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card gradient-blue">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">5</span>
                                <span class="stat-label">Events Joined</span>
                            </div>
                            <div class="stat-decoration"></div>
                        </div>
                        <div class="stat-card gradient-purple">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">3</span>
                                <span class="stat-label">Organizations</span>
                            </div>
                            <div class="stat-decoration"></div>
                        </div>
                        <div class="stat-card gradient-emerald">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">₱650</span>
                                <span class="stat-label">Total Paid</span>
                            </div>
                            <div class="stat-decoration"></div>
                        </div>
                        <div class="stat-card gradient-amber">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">₱850</span>
                                <span class="stat-label">Pending</span>
                            </div>
                            <div class="stat-decoration"></div>
                        </div>
                    </div>

                    <!-- Main Grid -->
                    <div class="dashboard-grid">
                        <!-- Upcoming Events -->
                        <div class="card events-card">
                            <div class="card-header">
                                <h3><i class="fas fa-calendar-alt"></i> Upcoming Events</h3>
                                <a href="#events" class="view-all-link" onclick="switchSection('events')">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="event-list">
                                    <div class="event-item">
                                        <div class="event-date">
                                            <span class="day">05</span>
                                            <span class="month">DEC</span>
                                        </div>
                                        <div class="event-details">
                                            <h4>Tech Innovation Summit 2025</h4>
                                            <p><i class="fas fa-building"></i> Computer Science Society</p>
                                            <p><i class="fas fa-map-marker-alt"></i> Main Auditorium</p>
                                        </div>
                                        <button class="btn-join" onclick="joinEvent(1)">Join</button>
                                    </div>
                                    <div class="event-item">
                                        <div class="event-date">
                                            <span class="day">10</span>
                                            <span class="month">DEC</span>
                                        </div>
                                        <div class="event-details">
                                            <h4>Business Plan Competition</h4>
                                            <p><i class="fas fa-building"></i> Business Administration Club</p>
                                            <p><i class="fas fa-map-marker-alt"></i> Conference Hall B</p>
                                        </div>
                                        <button class="btn-join" onclick="joinEvent(2)">Join</button>
                                    </div>
                                    <div class="event-item">
                                        <div class="event-date">
                                            <span class="day">15</span>
                                            <span class="month">DEC</span>
                                        </div>
                                        <div class="event-details">
                                            <h4>Environmental Awareness Week</h4>
                                            <p><i class="fas fa-building"></i> Green Energy Initiative</p>
                                            <p><i class="fas fa-map-marker-alt"></i> Campus Grounds</p>
                                        </div>
                                        <button class="btn-join free" onclick="joinEvent(3)">Free</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Announcements -->
                        <div class="card announcements-card">
                            <div class="card-header">
                                <h3><i class="fas fa-bullhorn"></i> Announcements</h3>
                                <a href="#announcements" class="view-all-link" onclick="switchSection('announcements')">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="announcement-list">
                                    <div class="announcement-item priority-high">
                                        <div class="announcement-badge">Important</div>
                                        <h4>Enrollment Period Extended</h4>
                                        <p>The enrollment period for the 2nd semester has been extended until December 15, 2025.</p>
                                        <div class="announcement-meta">
                                            <span><i class="fas fa-user"></i> Registrar Office</span>
                                            <span><i class="fas fa-clock"></i> 2 days ago</span>
                                        </div>
                                    </div>
                                    <div class="announcement-item">
                                        <h4>New Library Hours</h4>
                                        <p>Starting December 1, the library will be open 24/7 during examination week.</p>
                                        <div class="announcement-meta">
                                            <span><i class="fas fa-user"></i> Library Services</span>
                                            <span><i class="fas fa-clock"></i> 5 days ago</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- My Organizations -->
                        <div class="card orgs-card">
                            <div class="card-header">
                                <h3><i class="fas fa-users"></i> My Organizations</h3>
                                <a href="#organizations" class="view-all-link" onclick="switchSection('organizations')">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="org-list">
                                    <div class="org-item">
                                        <div class="org-avatar">CSS</div>
                                        <div class="org-details">
                                            <h4>Computer Science Society</h4>
                                            <p>Member since Sept 2024</p>
                                        </div>
                                        <span class="org-badge active">Active</span>
                                    </div>
                                    <div class="org-item">
                                        <div class="org-avatar">TIH</div>
                                        <div class="org-details">
                                            <h4>Tech Innovation Hub</h4>
                                            <p>Member since Oct 2024</p>
                                        </div>
                                        <span class="org-badge active">Active</span>
                                    </div>
                                    <div class="org-item pending">
                                        <div class="org-avatar">GEI</div>
                                        <div class="org-details">
                                            <h4>Green Energy Initiative</h4>
                                            <p>Application submitted</p>
                                        </div>
                                        <span class="org-badge pending">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Payments -->
                        <div class="card payments-card">
                            <div class="card-header">
                                <h3><i class="fas fa-credit-card"></i> Pending Payments</h3>
                                <a href="#payments" class="view-all-link" onclick="switchSection('payments')">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="payment-list">
                                    <div class="payment-item">
                                        <div class="payment-icon">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h4>CSS Official T-Shirt x2</h4>
                                            <p>Due: Dec 1, 2025</p>
                                        </div>
                                        <div class="payment-amount">₱700.00</div>
                                        <button class="btn-pay" onclick="initiatePayment(1)">Pay Now</button>
                                    </div>
                                    <div class="payment-item">
                                        <div class="payment-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h4>Tech Summit Registration</h4>
                                            <p>Due: Dec 3, 2025</p>
                                        </div>
                                        <div class="payment-amount">₱150.00</div>
                                        <button class="btn-pay" onclick="initiatePayment(2)">Pay Now</button>
                                    </div>
                                </div>
                                <div class="payment-total">
                                    <span>Total Pending</span>
                                    <span class="total-amount">₱850.00</span>
                                </div>
                            </div>
                        </div>
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
                                <option value="free">Free Events</option>
                            </select>
                        </div>
                    </div>
                    <div class="events-grid" id="eventsGrid">
                        <div class="event-card">
                            <div class="event-card-header">
                                <span class="event-tag">Technology</span>
                                <span class="event-fee">₱150</span>
                            </div>
                            <div class="event-card-body">
                                <h3>Tech Innovation Summit 2025</h3>
                                <p>Join us for the annual tech summit featuring industry leaders and workshops.</p>
                                <div class="event-info">
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>December 5, 2025</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock"></i>
                                        <span>9:00 AM</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Main Auditorium</span>
                                    </div>
                                </div>
                                <div class="event-org">
                                    <div class="org-avatar small">CSS</div>
                                    <span>Computer Science Society</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <button class="btn-primary" onclick="joinEvent(1)">
                                    <i class="fas fa-plus"></i> Join Event
                                </button>
                                <button class="btn-secondary" onclick="viewEventDetails(1)">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="event-card">
                            <div class="event-card-header">
                                <span class="event-tag">Business</span>
                                <span class="event-fee free">Free</span>
                            </div>
                            <div class="event-card-body">
                                <h3>Business Plan Competition</h3>
                                <p>Showcase your entrepreneurial skills and win exciting prizes.</p>
                                <div class="event-info">
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>December 10, 2025</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock"></i>
                                        <span>1:00 PM</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Conference Hall B</span>
                                    </div>
                                </div>
                                <div class="event-org">
                                    <div class="org-avatar small">BAC</div>
                                    <span>Business Administration Club</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <button class="btn-primary" onclick="joinEvent(2)">
                                    <i class="fas fa-plus"></i> Join Event
                                </button>
                                <button class="btn-secondary" onclick="viewEventDetails(2)">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="event-card">
                            <div class="event-card-header">
                                <span class="event-tag">Environmental</span>
                                <span class="event-fee free">Free</span>
                            </div>
                            <div class="event-card-body">
                                <h3>Environmental Awareness Week</h3>
                                <p>A week-long celebration promoting environmental consciousness.</p>
                                <div class="event-info">
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>December 15, 2025</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock"></i>
                                        <span>8:00 AM</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Campus Grounds</span>
                                    </div>
                                </div>
                                <div class="event-org">
                                    <div class="org-avatar small">GEI</div>
                                    <span>Green Energy Initiative</span>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <button class="btn-primary" onclick="joinEvent(3)">
                                    <i class="fas fa-plus"></i> Join Event
                                </button>
                                <button class="btn-secondary" onclick="viewEventDetails(3)">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>
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
                        <div class="announcement-card priority-high">
                            <div class="announcement-card-header">
                                <span class="priority-badge high"><i class="fas fa-exclamation-circle"></i> Important</span>
                                <span class="announcement-date">November 20, 2025</span>
                            </div>
                            <h3>Enrollment Period Extended</h3>
                            <p>The enrollment period for the 2nd semester has been extended until December 15, 2025. Please ensure all requirements are submitted before the deadline.</p>
                            <div class="announcement-footer">
                                <span class="announcement-author"><i class="fas fa-user-tie"></i> Registrar Office</span>
                                <button class="btn-comment" onclick="toggleComments(1)">
                                    <i class="fas fa-comment"></i> 5 Comments
                                </button>
                            </div>
                            <div class="comments-section" id="comments-1">
                                <div class="comment-input-wrapper">
                                    <input type="text" class="comment-input" placeholder="Write a comment...">
                                    <button class="btn-send" onclick="postComment(1, 'announcement')"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="announcement-card">
                            <div class="announcement-card-header">
                                <span class="priority-badge medium"><i class="fas fa-info-circle"></i> Info</span>
                                <span class="announcement-date">November 18, 2025</span>
                            </div>
                            <h3>New Library Hours</h3>
                            <p>Starting December 1, the library will be open 24/7 during examination week. Take advantage of extended hours for your study sessions.</p>
                            <div class="announcement-footer">
                                <span class="announcement-author"><i class="fas fa-user-tie"></i> Library Services</span>
                                <button class="btn-comment" onclick="toggleComments(2)">
                                    <i class="fas fa-comment"></i> 3 Comments
                                </button>
                            </div>
                            <div class="comments-section" id="comments-2">
                                <div class="comment-input-wrapper">
                                    <input type="text" class="comment-input" placeholder="Write a comment...">
                                    <button class="btn-send" onclick="postComment(2, 'announcement')"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="announcement-card">
                            <div class="announcement-card-header">
                                <span class="priority-badge medium"><i class="fas fa-info-circle"></i> Info</span>
                                <span class="announcement-date">November 15, 2025</span>
                            </div>
                            <h3>Holiday Schedule Announcement</h3>
                            <p>Classes will be suspended from December 23, 2025 to January 2, 2026 for the holiday break. Wishing everyone a safe and happy holiday season!</p>
                            <div class="announcement-footer">
                                <span class="announcement-author"><i class="fas fa-user-tie"></i> Academic Affairs</span>
                                <button class="btn-comment" onclick="toggleComments(3)">
                                    <i class="fas fa-comment"></i> 8 Comments
                                </button>
                            </div>
                            <div class="comments-section" id="comments-3">
                                <div class="comment-input-wrapper">
                                    <input type="text" class="comment-input" placeholder="Write a comment...">
                                    <button class="btn-send" onclick="postComment(3, 'announcement')"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
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
                        <div class="org-card">
                            <div class="org-card-header">
                                <div class="org-avatar large">CSS</div>
                                <div class="org-card-info">
                                    <h3>Computer Science Society</h3>
                                    <span class="org-type">Academic</span>
                                </div>
                                <span class="member-badge"><i class="fas fa-check"></i> Member</span>
                            </div>
                            <p class="org-description">A community of tech enthusiasts and future IT professionals dedicated to advancing technology skills.</p>
                            <div class="org-stats">
                                <div class="stat">
                                    <i class="fas fa-users"></i>
                                    <span>156 Members</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-calendar"></i>
                                    <span>12 Events</span>
                                </div>
                            </div>
                            <div class="org-card-footer">
                                <button class="btn-secondary" onclick="viewOrgDetails(1)">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                            </div>
                        </div>

                        <div class="org-card">
                            <div class="org-card-header">
                                <div class="org-avatar large">BAC</div>
                                <div class="org-card-info">
                                    <h3>Business Administration Club</h3>
                                    <span class="org-type">Academic</span>
                                </div>
                            </div>
                            <p class="org-description">Developing future business leaders and entrepreneurs through workshops and networking events.</p>
                            <div class="org-stats">
                                <div class="stat">
                                    <i class="fas fa-users"></i>
                                    <span>203 Members</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-calendar"></i>
                                    <span>8 Events</span>
                                </div>
                            </div>
                            <div class="org-card-footer">
                                <button class="btn-primary" onclick="joinOrg(2)">
                                    <i class="fas fa-plus"></i> Join Organization
                                </button>
                                <button class="btn-secondary" onclick="viewOrgDetails(2)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="org-card">
                            <div class="org-card-header">
                                <div class="org-avatar large">GEI</div>
                                <div class="org-card-info">
                                    <h3>Green Energy Initiative</h3>
                                    <span class="org-type">Environmental</span>
                                </div>
                                <span class="pending-badge"><i class="fas fa-clock"></i> Pending</span>
                            </div>
                            <p class="org-description">Promoting environmental awareness and sustainability through campus-wide initiatives.</p>
                            <div class="org-stats">
                                <div class="stat">
                                    <i class="fas fa-users"></i>
                                    <span>89 Members</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-calendar"></i>
                                    <span>6 Events</span>
                                </div>
                            </div>
                            <div class="org-card-footer">
                                <button class="btn-secondary" onclick="viewOrgDetails(3)">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                            </div>
                        </div>

                        <div class="org-card">
                            <div class="org-card-header">
                                <div class="org-avatar large">SC</div>
                                <div class="org-card-info">
                                    <h3>Student Council</h3>
                                    <span class="org-type">Student Government</span>
                                </div>
                            </div>
                            <p class="org-description">The official student government representing the voice of all students in campus affairs.</p>
                            <div class="org-stats">
                                <div class="stat">
                                    <i class="fas fa-users"></i>
                                    <span>45 Members</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-calendar"></i>
                                    <span>20 Events</span>
                                </div>
                            </div>
                            <div class="org-card-footer">
                                <button class="btn-primary" onclick="joinOrg(4)">
                                    <i class="fas fa-plus"></i> Join Organization
                                </button>
                                <button class="btn-secondary" onclick="viewOrgDetails(4)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
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
                        <div class="product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <span class="product-badge">Popular</span>
                            </div>
                            <div class="product-body">
                                <h3>CSS Official T-Shirt</h3>
                                <p class="product-org">Computer Science Society</p>
                                <p class="product-desc">Official CSS t-shirt with embroidered logo.</p>
                                <div class="product-footer">
                                    <span class="product-price">₱350.00</span>
                                    <button class="btn-add-cart" onclick="addToCart(1)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="product-card">
                            <div class="product-image hoodie">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="product-body">
                                <h3>BAC Hoodie</h3>
                                <p class="product-org">Business Administration Club</p>
                                <p class="product-desc">Premium quality hoodie with BAC branding.</p>
                                <div class="product-footer">
                                    <span class="product-price">₱650.00</span>
                                    <button class="btn-add-cart" onclick="addToCart(2)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="product-card">
                            <div class="product-image eco">
                                <i class="fas fa-shopping-bag"></i>
                                <span class="product-badge eco">Eco-Friendly</span>
                            </div>
                            <div class="product-body">
                                <h3>GEI Eco-Bag</h3>
                                <p class="product-org">Green Energy Initiative</p>
                                <p class="product-desc">Reusable eco-bag made from recycled materials.</p>
                                <div class="product-footer">
                                    <span class="product-price">₱150.00</span>
                                    <button class="btn-add-cart" onclick="addToCart(3)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="product-card">
                            <div class="product-image lanyard">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <div class="product-body">
                                <h3>CSPC Lanyard</h3>
                                <p class="product-org">Student Council</p>
                                <p class="product-desc">Official CSPC lanyard for ID holders.</p>
                                <div class="product-footer">
                                    <span class="product-price">₱75.00</span>
                                    <button class="btn-add-cart" onclick="addToCart(4)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
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
                                        <img src="<?= esc(session()->get('photo')) ?>" alt="Profile">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                    <button class="change-photo-btn"><i class="fas fa-camera"></i></button>
                                </div>
                                <div class="profile-header-info">
                                    <h2><?= esc(($profile['firstname'] ?? '') . ' ' . ($profile['lastname'] ?? '')) ?></h2>
                                    <p><?= esc($student['student_id'] ?? '') ?></p>
                                    <span class="profile-badge"><?= esc(strtoupper($student['department'] ?? '')) ?> - <?= esc($student['course'] ?? '') ?></span>
                                </div>
                            </div>
                            <form class="profile-form" id="profileForm">
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
                                        <label>Email</label>
                                        <input type="email" value="<?= esc($user['email'] ?? '') ?>" class="form-control" disabled>
                                        <small class="form-hint">Email cannot be changed</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="<?= esc($profile['phone'] ?? '') ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Student ID</label>
                                        <input type="text" value="<?= esc($student['student_id'] ?? '') ?>" class="form-control" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Year Level</label>
                                        <input type="text" value="<?= esc($student['year_level'] ?? '') ?>rd Year" class="form-control" disabled>
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

    <script>
        const baseUrl = '<?= base_url() ?>';
        
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

        // Event Functions
        function joinEvent(eventId) {
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
                showToast(data.message, data.success ? 'success' : 'error');
            });
        }

        function viewEventDetails(eventId) {
            showToast('Event details coming soon!', 'info');
        }

        // Organization Functions
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
                showToast(data.message, data.success ? 'success' : 'error');
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
        function toggleComments(id) {
            const commentsSection = document.getElementById(`comments-${id}`);
            commentsSection.classList.toggle('open');
        }

        function postComment(targetId, type) {
            const input = document.querySelector(`#comments-${targetId} .comment-input`);
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
                body: `content=${encodeURIComponent(content)}&type=${type}&target_id=${targetId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    input.value = '';
                }
            });
        }

        // Profile Form
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(baseUrl + 'student/profile/edit', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.success ? 'success' : 'error');
            });
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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
            updateCombinedBadge();
        });
    </script>
</body>
</html>
