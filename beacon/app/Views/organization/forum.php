<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Forum - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/forum.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h2>Forum</h2>
                            <p class="section-subtitle">Share updates, ask questions, and engage with your members</p>
                        </div>
                        <button class="btn btn-primary"><i class="fas fa-plus"></i> New Post</button>
                    </div>
                    <div class="card-body">
                        <?php
                            $categories = $forumCategoryCounts ?? [];
                            $allPosts = $allPosts ?? [];
                            // trending: top by reactions + comments
                            $trending = $allPosts;
                            usort($trending, function($a, $b) {
                                $aData = $a['data'] ?? [];
                                $bData = $b['data'] ?? [];
                                $aReacts = is_array($aData['reaction_counts'] ?? null) ? array_sum($aData['reaction_counts']) : 0;
                                $bReacts = is_array($bData['reaction_counts'] ?? null) ? array_sum($bData['reaction_counts']) : 0;
                                $aScore = $aReacts + (int)($aData['comment_count'] ?? 0);
                                $bScore = $bReacts + (int)($bData['comment_count'] ?? 0);
                                return $bScore <=> $aScore;
                            });
                            $trending = array_slice($trending, 0, 4);
                            $posts = array_slice($allPosts, 0, 8);
                        ?>
                        <div class="forum-layout">
                            <aside class="forum-sidebar">
                                <div class="forum-categories-card">
                                    <div class="forum-sidebar-title"><i class="fas fa-layer-group"></i> Categories</div>
                                    <ul class="forum-category-list">
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $cat => $count): ?>
                                                <li class="forum-category-item">
                                                    <i class="fas fa-tag"></i>
                                                    <span><?= esc(ucfirst($cat)) ?></span>
                                                    <span class="category-count"><?= esc($count) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="forum-category-item">
                                                <i class="fas fa-tag"></i>
                                                <span>General</span>
                                                <span class="category-count">0</span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="forum-trending-card">
                                    <div class="forum-sidebar-title"><i class="fas fa-fire-alt"></i> Trending</div>
                                    <ul class="trending-list">
                                        <?php if (!empty($trending)): ?>
                                            <?php foreach ($trending as $idx => $post): ?>
                                                <?php
                                                    $type = $post['type'] ?? 'post';
                                                    $data = $post['data'] ?? [];
                                                    $title = $data['title'] ?? $data['event_name'] ?? 'Untitled';
                                                ?>
                                                <li class="trending-item">
                                                    <span class="trending-rank"><?= $idx + 1 ?></span>
                                                    <div class="trending-topic"><?= esc($title) ?></div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="trending-item">
                                                <span class="trending-rank">1</span>
                                                <div class="trending-topic">No trending posts yet</div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </aside>

                            <section class="forum-main">
                                <div class="forum-filter-bar">
                                    <div class="forum-tabs">
                                        <button class="forum-tab active"><i class="fas fa-stream"></i> All</button>
                                        <button class="forum-tab"><i class="fas fa-bullhorn"></i> Announcements</button>
                                        <button class="forum-tab"><i class="fas fa-calendar-alt"></i> Events</button>
                                    </div>
                                    <div class="forum-search">
                                        <i class="fas fa-search"></i>
                                        <input type="text" placeholder="Search posts..." />
                                    </div>
                                </div>

                                <div class="forum-posts-list">
                                    <?php if (!empty($posts)): ?>
                                        <?php foreach ($posts as $post): ?>
                                            <?php
                                                $type = $post['type'] ?? 'post';
                                                $data = $post['data'] ?? [];
                                                $title = $data['title'] ?? $data['event_name'] ?? 'Untitled';
                                                $desc = $data['content'] ?? $data['description'] ?? '';
                                                $descShort = strlen($desc) > 180 ? substr($desc, 0, 180) . '…' : $desc;
                                                $created = $data['created_at'] ?? $data['date'] ?? null;
                                                $createdLabel = $created ? date('M d, Y', strtotime($created)) : 'N/A';
                                                $reactions = is_array($data['reaction_counts'] ?? null) ? array_sum($data['reaction_counts']) : 0;
                                                $comments = (int)($data['comment_count'] ?? 0);
                                                $badge = $type === 'announcement' ? 'Announcement' : ($type === 'event' ? 'Event' : 'Post');
                                            ?>
                                            <article class="forum-post">
                                                <div class="forum-post-main">
                                                    <div class="forum-post-top">
                                                        <span class="forum-badge"><?= esc($badge) ?></span>
                                                        <span class="forum-date"><?= esc($createdLabel) ?></span>
                                                    </div>
                                                    <h3 class="forum-post-title"><?= esc($title) ?></h3>
                                                    <?php if (!empty($descShort)): ?>
                                                        <p class="forum-post-desc"><?= esc($descShort) ?></p>
                                                    <?php endif; ?>
                                                    <div class="forum-post-meta">
                                                        <span><i class="fas fa-thumbs-up"></i> <?= esc($reactions) ?> reactions</span>
                                                        <span><i class="fas fa-comment"></i> <?= esc($comments) ?> comments</span>
                                                    </div>
                                                </div>
                                                <div class="forum-post-actions">
                                                    <button class="btn btn-outline btn-sm">View</button>
                                                    <button class="btn btn-primary btn-sm">Comment</button>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-state-large" style="margin-top: 1rem;">
                                            <i class="fas fa-comments"></i>
                                            <h3>No posts yet</h3>
                                            <p>Create your first post to start the conversation.</p>
                                            <button class="btn btn-primary"><i class="fas fa-plus"></i> New Post</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

