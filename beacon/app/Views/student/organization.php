<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> - BEACON</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/student.css') ?>" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .org-page-wrapper {
            background: #f8fafc;
            min-height: 100vh;
        }
        
        .org-cover-section {
            position: relative;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            margin-top: 64px;
        }
        
        .org-cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.3));
        }
        
        .org-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            height: 100%;
            display: flex;
            align-items: flex-end;
        }
        
        .org-profile-section {
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            width: 100%;
            padding-bottom: 1rem;
        }
        
        .org-profile-avatar {
            position: relative;
            z-index: 10;
        }
        
        .org-profile-avatar img,
        .org-profile-avatar > div {
            width: 160px;
            height: 160px;
            border-radius: 20px;
            border: 5px solid white;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            object-fit: cover;
        }
        
        .org-profile-avatar > div {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: bold;
            color: white;
        }
        
        .org-profile-info {
            flex: 1;
            color: white;
            padding-bottom: 0.5rem;
        }
        
        .org-profile-info h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .org-profile-type {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        
        .org-profile-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .org-profile-stats span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            opacity: 0.95;
        }
        
        .org-profile-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-left: auto;
        }
        
        .org-content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }
        
        .org-main-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .org-posts-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .org-posts-container .feed-post {
            margin-bottom: 0;
        }
        
        .event-preview-card .btn-primary {
            margin-top: 1.25rem;
        }
        
        .org-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .org-info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .org-info-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1e293b;
        }
        
        .org-info-card p {
            color: #64748b;
            line-height: 1.6;
            font-size: 0.9375rem;
        }
        
        .org-mission-vision {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .org-mission-vision-item h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .org-mission-vision-item p {
            color: #64748b;
            font-size: 0.9375rem;
            line-height: 1.6;
        }
        
        @media (max-width: 968px) {
            .org-content-wrapper {
                grid-template-columns: 1fr;
            }
            
            .org-profile-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .org-profile-actions {
                margin-left: 0;
                width: 100%;
                flex-wrap: wrap;
            }
            
            .org-profile-info h1 {
                font-size: 2rem;
            }
        }
    </style>
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
                    <a href="<?= base_url('student/dashboard') ?>" class="nav-link">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="nav-actions">
                    <a href="<?= base_url('student/dashboard') ?>" class="nav-icon-btn" title="Back to Dashboard">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Organization Cover Section -->
        <div class="org-cover-section">
            <div class="org-cover-overlay"></div>
            <div class="org-header-container">
                <div class="org-profile-section">
                    <div class="org-profile-avatar">
                        <?php if(!empty($organization['photo'])): ?>
                            <img src="<?= esc($organization['photo']) ?>" alt="<?= esc($organization['name']) ?>">
                        <?php else: ?>
                            <div>
                                <?= strtoupper(substr($organization['acronym'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="org-profile-info">
                        <h1><?= esc($organization['name']) ?></h1>
                        <p class="org-profile-type"><?= esc($organization['type']) ?></p>
                        <div class="org-profile-stats">
                            <span><i class="fas fa-users"></i> <?= number_format($organization['members']) ?> Members</span>
                            <span><i class="fas fa-bullhorn"></i> <?= count($announcements) ?> Announcements</span>
                            <span><i class="fas fa-calendar"></i> <?= count($events) ?> Events</span>
                        </div>
                    </div>
                    <div class="org-profile-actions">
                        <?php if($organization['is_member']): ?>
                            <span class="member-badge" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500;"><i class="fas fa-check"></i> Member</span>
                        <?php elseif($organization['is_pending']): ?>
                            <span class="pending-badge" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500;"><i class="fas fa-clock"></i> Pending</span>
                        <?php else: ?>
                            <button class="btn-primary" onclick="joinOrg(<?= $organization['id'] ?>)" style="background: white; color: #667eea; border: none; padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-plus"></i> Join Organization
                            </button>
                        <?php endif; ?>
                        <?php if($organization['is_following']): ?>
                            <button class="btn-secondary" id="followBtn_<?= $organization['id'] ?>" onclick="unfollowOrg(<?= $organization['id'] ?>)" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-check"></i> Following
                            </button>
                        <?php else: ?>
                            <button class="btn-outline" id="followBtn_<?= $organization['id'] ?>" onclick="followOrg(<?= $organization['id'] ?>)" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 0.625rem 1.25rem; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-user-plus"></i> Follow
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="org-page-wrapper">
            <div class="org-content-wrapper">
                <!-- Organization Posts -->
                <div class="org-main-content">
                    <div class="org-posts-container">
                        <?php if(!empty($allPosts)): ?>
                            <?php foreach($allPosts as $post): ?>
                                <?php if($post['type'] === 'announcement'): ?>
                                    <?php $announcement = $post['data']; ?>
                                    <!-- Announcement Post -->
                                    <div class="feed-post announcement-post <?= $announcement['priority'] === 'high' ? 'priority-high' : '' ?>">
                                        <div class="post-header">
                                            <div class="post-author-avatar org">
                                                <?php if(!empty($announcement['org_photo'])): ?>
                                                    <img src="<?= esc($announcement['org_photo']) ?>" alt="<?= esc($announcement['org_name'] ?? 'Organization') ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                <?php else: ?>
                                                    <?= strtoupper(substr($announcement['org_acronym'] ?? 'ORG', 0, 2)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="post-author-info">
                                                <span class="post-author-name"><?= esc($announcement['org_name'] ?? 'Organization') ?></span>
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
                                            <button class="post-action"><i class="far fa-thumbs-up"></i> Like</button>
                                            <button class="post-action"><i class="far fa-comment"></i> Comment</button>
                                        </div>
                                    </div>
                                <?php elseif($post['type'] === 'event'): ?>
                                    <?php $event = $post['data']; ?>
                                    <!-- Event Post -->
                                    <div class="feed-post event-post-card">
                                        <div class="post-header">
                                            <div class="post-author-avatar org">
                                                <?php if(!empty($event['org_photo'])): ?>
                                                    <img src="<?= esc($event['org_photo']) ?>" alt="<?= esc($event['org_name'] ?? 'Organization') ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                <?php else: ?>
                                                    <?= strtoupper(substr($event['org_acronym'] ?? 'ORG', 0, 2)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="post-author-info">
                                                <span class="post-author-name"><?= esc($event['org_name'] ?? 'Organization') ?></span>
                                                <span class="post-time">
                                                    <i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($event['created_at'] ?? $event['date'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="post-content">
                                            <p class="post-text">🎉 New event from <?= esc($event['org_acronym'] ?? 'Organization') ?>!</p>
                                        </div>
                                        <div class="event-preview-card">
                                            <div class="event-preview-header">
                                                <h4><?= esc($event['title']) ?></h4>
                                                <span class="event-date-badge">
                                                    <span class="edb-day"><?= date('d', strtotime($event['date'])) ?></span>
                                                    <span class="edb-month"><?= strtoupper(date('M', strtotime($event['date']))) ?></span>
                                                </span>
                                            </div>
                                            <p class="event-preview-description"><?= esc($event['description']) ?></p>
                                            <div class="event-preview-details">
                                                <span><i class="fas fa-clock"></i> <?= esc($event['time']) ?></span>
                                                <span><i class="fas fa-map-marker-alt"></i> <?= esc($event['location']) ?></span>
                                                <span><i class="fas fa-users"></i> <?= esc($event['attendees']) ?>/<?= esc($event['max_attendees']) ?> attendees</span>
                                            </div>
                                            <button class="btn-primary" onclick="joinEvent(<?= $event['id'] ?>)">
                                                <i class="fas fa-plus"></i> Join Event
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="feed-post empty-state">
                                <div class="empty-state-content">
                                    <i class="fas fa-inbox"></i>
                                    <h3>No Posts Yet</h3>
                                    <p>This organization hasn't posted any announcements or events yet.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Organization Sidebar -->
                <div class="org-sidebar">
                    <?php if(!empty($organization['mission']) || !empty($organization['vision'])): ?>
                    <div class="org-info-card">
                        <h3>About</h3>
                        <div class="org-mission-vision">
                            <?php if(!empty($organization['mission'])): ?>
                            <div class="org-mission-vision-item">
                                <h4>Mission</h4>
                                <p><?= esc($organization['mission']) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($organization['vision'])): ?>
                            <div class="org-mission-vision-item">
                                <h4>Vision</h4>
                                <p><?= esc($organization['vision']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="org-info-card">
                        <h3>Organization Details</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Type</h4>
                                <p style="color: #64748b; font-size: 0.9375rem;"><?= esc($organization['type']) ?></p>
                            </div>
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Members</h4>
                                <p style="color: #64748b; font-size: 0.9375rem;"><?= number_format($organization['members']) ?> active members</p>
                            </div>
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem;">Activity</h4>
                                <p style="color: #64748b; font-size: 0.9375rem;"><?= count($announcements) ?> announcements • <?= count($events) ?> events</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';

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
                    alert(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    alert(data.message || 'Failed to join organization');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

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
                alert(data.message || 'Failed to follow organization');
                if (data.success) {
                    followBtn.innerHTML = '<i class="fas fa-check"></i> Following';
                    followBtn.className = 'btn-secondary';
                    followBtn.setAttribute('onclick', 'unfollowOrg(' + orgId + ')');
                } else {
                    followBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                alert('An error occurred while following the organization');
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
                alert(data.message || 'Failed to unfollow organization');
                if (data.success) {
                    followBtn.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
                    followBtn.className = 'btn-outline';
                    followBtn.setAttribute('onclick', 'followOrg(' + orgId + ')');
                } else {
                    followBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                alert('An error occurred while unfollowing the organization');
                followBtn.innerHTML = originalText;
            })
            .finally(() => {
                followBtn.disabled = false;
            });
        }

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
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(data.message || 'Failed to join event');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>

