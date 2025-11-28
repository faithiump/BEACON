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
            transform: scale(1.3) translateY(-5px);
            background: rgba(255, 255, 255, 0.8);
        }

        .reaction-option .reaction-label {
            display: none;
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
                                                    <span class="reaction-text">
                                                        <?php
                                                        if ($userReaction):
                                                            $labels = [
                                                                'like' => 'Like',
                                                                'love' => 'Love',
                                                                'care' => 'Care',
                                                                'haha' => 'Haha',
                                                                'wow' => 'Wow',
                                                                'sad' => 'Sad',
                                                                'angry' => 'Angry'
                                                            ];
                                                            echo $labels[$userReaction] ?? 'Like';
                                                        else:
                                                            echo 'Like';
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
                                                    <span class="reaction-text">
                                                        <?php
                                                        if ($userReaction):
                                                            $labels = [
                                                                'like' => 'Like',
                                                                'love' => 'Love',
                                                                'care' => 'Care',
                                                                'haha' => 'Haha',
                                                                'wow' => 'Wow',
                                                                'sad' => 'Sad',
                                                                'angry' => 'Angry'
                                                            ];
                                                            echo $labels[$userReaction] ?? 'Like';
                                                        else:
                                                            echo 'Like';
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

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
                max-width: 400px;
                font-size: 0.875rem;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Add CSS for toast animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

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
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Failed to join event');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function toggleInterested(eventId) {
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
                if (data.success) {
                    const btn = document.querySelector(`.interested-btn[data-event-id="${eventId}"]`);
                    if (btn) {
                        if (data.is_interested) {
                            btn.style.backgroundColor = '#fef3c7';
                            btn.style.borderColor = '#fbbf24';
                            btn.style.color = '#92400e';
                            btn.querySelector('i').classList.remove('far');
                            btn.querySelector('i').classList.add('fas');
                        } else {
                            btn.style.backgroundColor = '';
                            btn.style.borderColor = '';
                            btn.style.color = '';
                            btn.querySelector('i').classList.remove('fas');
                            btn.querySelector('i').classList.add('far');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
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
                        // Show all comments
                        data.comments.forEach(comment => {
                            const commentDiv = createCommentElement(comment, postType, postId);
                            commentsList.appendChild(commentDiv);
                        });
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
            if (isNaN(date.getTime())) {
                const mysqlDate = new Date(dateString.replace(' ', 'T'));
                if (isNaN(mysqlDate.getTime())) return 'Just now';
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

        // Reaction Functions
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
            if (currentReaction) {
                setReaction(postType, postId, 'like', button);
            } else {
                setReaction(postType, postId, 'like', button);
            }
        }

        function setReaction(postType, postId, reactionType, button) {
            const wrapper = button.closest('.reaction-wrapper');
            const reactionIcon = wrapper ? wrapper.querySelector('.reaction-icon') : null;
            const reactionText = wrapper ? wrapper.querySelector('.reaction-text') : null;
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
                        if (reactionText) {
                            const labels = {
                                'like': 'Like',
                                'love': 'Love',
                                'care': 'Care',
                                'haha': 'Haha',
                                'wow': 'Wow',
                                'sad': 'Sad',
                                'angry': 'Angry'
                            };
                            reactionText.textContent = labels[data.reaction_type] || 'Like';
                        }
                    } else {
                        // User removed reaction
                        button.classList.remove('reacted', 'reaction-like', 'reaction-love', 'reaction-care', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');
                        
                        if (reactionIcon) {
                            reactionIcon.textContent = '👍';
                        }
                        if (reactionText) {
                            reactionText.textContent = 'Like';
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

        // Add Enter key support for comment inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Find all comment inputs and add Enter key support
            const commentInputs = document.querySelectorAll('.comment-input');
            commentInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const inputId = this.id;
                        const matches = inputId.match(/comment-input-(announcement|event)-(\d+)/);
                        if (matches) {
                            const postType = matches[1];
                            const postId = matches[2];
                            postComment(postId, postType);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

