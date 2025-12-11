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
            <main class="dashboard-main forum-page">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h2>Community Forum</h2>
                            <p class="section-subtitle">Discuss with fellow students and organizations</p>
                        </div>
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
                                    <h4 class="forum-sidebar-title"><i class="fas fa-folder"></i> Categories</h4>
                                    <ul class="forum-category-list">
                                        <li class="forum-category-item active" data-category="all">
                                            <i class="fas fa-globe"></i>
                                            <span>All Posts</span>
                                            <span class="category-count" id="category-count-all"><?= $forumCategoryCounts['all'] ?? 0 ?></span>
                                        </li>
                                        <li class="forum-category-item" data-category="general">
                                            <i class="fas fa-comment-dots"></i>
                                            <span>General Discussion</span>
                                            <span class="category-count" id="category-count-general"><?= $forumCategoryCounts['general'] ?? 0 ?></span>
                                        </li>
                                        <li class="forum-category-item" data-category="events">
                                            <i class="fas fa-calendar-star"></i>
                                            <span>Events & Activities</span>
                                            <span class="category-count" id="category-count-events"><?= $forumCategoryCounts['events'] ?? 0 ?></span>
                                        </li>
                                        <li class="forum-category-item" data-category="academics">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span>Academics</span>
                                            <span class="category-count" id="category-count-academics"><?= $forumCategoryCounts['academics'] ?? 0 ?></span>
                                        </li>
                                        <li class="forum-category-item" data-category="marketplace">
                                            <i class="fas fa-store"></i>
                                            <span>Buy & Sell</span>
                                            <span class="category-count" id="category-count-marketplace"><?= $forumCategoryCounts['marketplace'] ?? 0 ?></span>
                                        </li>
                                        <li class="forum-category-item" data-category="help">
                                            <i class="fas fa-question-circle"></i>
                                            <span>Help & Support</span>
                                            <span class="category-count" id="category-count-help"><?= $forumCategoryCounts['help'] ?? 0 ?></span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="forum-trending-card">
                                    <h4 class="forum-sidebar-title"><i class="fas fa-fire"></i> Trending Topics</h4>
                                    <ul class="trending-list">
                                        <li class="trending-item">
                                            <span class="trending-topic" style="text-align: center; width: 100%; color: #6b7280; font-style: italic;">Loading trending topics...</span>
                                        </li>
                                    </ul>
                                </div>
                            </aside>

                            <section class="forum-main">
                                <!-- Create Post Box -->
                                <div class="forum-create-box">
                                    <div class="create-box-avatar">
                                        <?php
                                        $orgPhoto = null;
                                        if (!empty($organization['photo'])) {
                                            $orgPhoto = base_url($organization['photo']);
                                        }
                                        ?>
                                        <?php if($orgPhoto): ?>
                                            <img src="<?= esc($orgPhoto) ?>" alt="Organization" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-placeholder-sm" style="display: none;"><?= strtoupper(substr($organization['name'] ?? $organization['organization_name'] ?? 'O', 0, 1)) ?></div>
                                        <?php else: ?>
                                            <div class="avatar-placeholder-sm"><?= strtoupper(substr($organization['name'] ?? $organization['organization_name'] ?? 'O', 0, 1)) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <input type="text" class="create-box-input" placeholder="Share an update with your organization..." onclick="openCreatePostModal()">
                                    <button class="create-box-btn" onclick="openCreatePostModal()" title="Create New Post">
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
                                        <button class="forum-tab" data-filter="announcements">
                                            <i class="fas fa-bullhorn"></i> Announcements
                                        </button>
                                    </div>
                                    <div class="forum-search">
                                        <i class="fas fa-search"></i>
                                        <input type="text" id="forumSearchInput" placeholder="Search posts...">
                                    </div>
                                </div>

                                <!-- Forum Posts -->
                                <div class="forum-posts-list" id="forumPostsList">
                                    <!-- Posts will be loaded dynamically here -->
                                </div>

                                <!-- Load More -->
                                <div class="forum-load-more">
                                    <button class="btn-load-more">
                                        <i class="fas fa-sync-alt"></i> Load More Posts
                                    </button>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <!-- Create Post Modal -->
                <div class="modal-overlay" id="createPostModal" style="display: none;">
                    <div class="modal" style="max-width: 600px;">
                        <div class="modal-header">
                            <h2>Create New Post</h2>
                            <button class="modal-close" onclick="closeCreatePostModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createPostForm">
                                <div class="form-group">
                                    <label for="postTitle">Title <span class="required">*</span></label>
                                    <input type="text" id="postTitle" name="title" class="form-control" placeholder="Enter post title..." required minlength="3" maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label for="postContent">Content <span class="required">*</span></label>
                                    <textarea id="postContent" name="content" class="form-control" rows="6" placeholder="What's on your mind?" required minlength="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="postCategory">Category <span class="required">*</span></label>
                                    <select id="postCategory" name="category" class="form-control" required>
                                        <option value="general">General Discussion</option>
                                        <option value="events">Events & Activities</option>
                                        <option value="academics">Academics</option>
                                        <option value="marketplace">Buy & Sell</option>
                                        <option value="help">Help & Support</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="postTags">Tags (comma-separated)</label>
                                    <input type="text" id="postTags" name="tags" class="form-control" placeholder="e.g., study, campus, tips">
                                </div>
                                <div class="form-group">
                                    <label for="postImage">Image (optional)</label>
                                    <input type="file" id="postImage" name="image" class="form-control" accept="image/*">
                                    <small class="form-text">Max size: 5MB. Supported formats: JPG, PNG, GIF, WebP</small>
                                    <div id="postImagePreview" style="margin-top: 1rem; display: none;">
                                        <img id="postImagePreviewImg" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn-secondary" onclick="closeCreatePostModal()">Cancel</button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-paper-plane"></i> Post
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Base URL for AJAX requests
        const baseUrl = '<?= base_url() ?>';

        // Current context information
        const currentContext = {
            isOrganizationForum: true,
            userType: 'organization' // Since this is organization forum, assume organization user
        };

        // Initialize forum when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial forum posts (which will also calculate trending topics)
            loadForumPosts();

            // Set up forum tab click handlers
            setupForumTabs();

            // Set up search functionality
            setupForumSearch();

            // Set up create post form
            setupCreatePostForm();
        });

        // Set up forum tabs
        function setupForumTabs() {
            const forumTabs = document.querySelectorAll('.forum-tab[data-filter]');
            forumTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    forumTabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Get current category and filter
                    const activeCategoryTab = document.querySelector('.forum-category-item.active');
                    const category = activeCategoryTab ? activeCategoryTab.getAttribute('data-category') : 'all';
                    const filter = this.getAttribute('data-filter');

                    // Load posts with new filter (will recalculate trending topics)
                    loadForumPosts(category, filter);
                });
            });
        }

        // Set up category filtering
        function setupCategoryFilters() {
            const categoryItems = document.querySelectorAll('.forum-category-item[data-category]');
            categoryItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all categories
                    categoryItems.forEach(c => c.classList.remove('active'));
                    // Add active class to clicked category
                    this.classList.add('active');

                    // Get current filter and category
                    const activeFilterTab = document.querySelector('.forum-tab.active');
                    const filter = activeFilterTab ? activeFilterTab.getAttribute('data-filter') : 'latest';
                    const category = this.getAttribute('data-category');

                    // Load posts with new category (will recalculate trending topics)
                    loadForumPosts(category, filter);
                });
            });
        }

        // Set up search functionality
        function setupForumSearch() {
            const searchInput = document.getElementById('forumSearchInput');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const query = this.value.trim();
                        if (query.length >= 2 || query.length === 0) {
                            loadForumPosts(null, null, query);
                        }
                    }, 500);
                });
            }
        }

        // Set up create post form
        function setupCreatePostForm() {
            const form = document.getElementById('createPostForm');
            const imageInput = document.getElementById('postImage');
            const imagePreview = document.getElementById('postImagePreview');
            const imagePreviewImg = document.getElementById('postImagePreviewImg');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitCreatePostForm();
                });
            }

            // Image preview functionality
            if (imageInput && imagePreview && imagePreviewImg) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreviewImg.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }
        }

        // Load forum posts via AJAX
        function loadForumPosts(category = 'all', filter = 'latest', search = '') {
            const postsList = document.getElementById('forumPostsList');
            if (!postsList) {
                console.error('forumPostsList element not found');
                return;
            }

            console.log('Loading forum posts for category:', category, 'with filter:', filter, 'search:', search);
            postsList.innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading posts...</div>';

            let url = baseUrl + 'organization/getPosts?category=' + encodeURIComponent(category) + '&filter=' + encodeURIComponent(filter);
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Posts response:', data);
                    console.log('Number of posts:', data.posts ? data.posts.length : 0);
                    if (data.success && data.posts && data.posts.length > 0) {
                        console.log('Displaying posts...');
                        displayForumPosts(data.posts);

                        // Calculate trending topics from loaded posts
                        calculateTrendingTopics(data.posts);
                    } else {
                        console.log('No posts found');
                        postsList.innerHTML = '<div style="text-align: center; padding: 2rem; color: #64116e;"><i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i><p>No posts found. Be the first to post!</p><button class="btn-primary" onclick="openCreatePostModal()" style="margin-top: 1rem;"><i class="fas fa-plus"></i> Create Post</button></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                    postsList.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;"><i class="fas fa-exclamation-circle"></i><p>Error loading posts: ' + error.message + '</p><button onclick="loadForumPosts(\'' + category + '\', \'' + filter + '\')" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer;">Retry</button></div>';
                });
        }

        // Calculate trending topics from posts data
        function calculateTrendingTopics(posts) {
            console.log('Calculating trending topics from', posts.length, 'posts');

            const tagData = {};

            posts.forEach(post => {
                // Get tags from post
                const tags = post.tags_array || post.tags || [];

                // If no tags array, try to extract from content
                let processedTags = tags;
                if (processedTags.length === 0 && post.content) {
                    const hashtagRegex = /#(\w+)/g;
                    const matches = post.content.match(hashtagRegex);
                    if (matches) {
                        processedTags = matches.map(tag => tag.substring(1)); // Remove # prefix
                    }
                }

                // Get reaction count
                let reactionCount = 0;
                if (post.reaction_counts && typeof post.reaction_counts === 'object') {
                    reactionCount = Object.values(post.reaction_counts).reduce((sum, count) => sum + (parseInt(count) || 0), 0);
                } else if (post.reaction_count) {
                    reactionCount = parseInt(post.reaction_count) || 0;
                } else if (post.reactions) {
                    reactionCount = parseInt(post.reactions) || 0;
                }

                console.log('Post:', post.title, 'Tags:', processedTags, 'Reactions:', reactionCount);

                // Count tags
                processedTags.forEach(tag => {
                    const tagName = tag.toString().trim().toLowerCase();
                    if (tagName) {
                        if (!tagData[tagName]) {
                            tagData[tagName] = { reactions: 0, frequency: 0 };
                        }
                        tagData[tagName].reactions += reactionCount;
                        tagData[tagName].frequency += 1;
                    }
                });
            });

            console.log('Tag data collected:', tagData);

            // Sort tags by reactions, then frequency
            const sortedTags = Object.entries(tagData)
                .sort(([,a], [,b]) => {
                    if (a.reactions !== b.reactions) {
                        return b.reactions - a.reactions;
                    }
                    return b.frequency - a.frequency;
                })
                .slice(0, 4); // Top 4

            console.log('Sorted trending topics:', sortedTags);

            // Update trending topics display
            updateTrendingTopicsDisplay(sortedTags);
        }

        // Update trending topics in the sidebar
        function updateTrendingTopicsDisplay(trendingTopics) {
            const trendingList = document.querySelector('.trending-list');
            if (!trendingList) {
                console.error('Trending list not found');
                return;
            }

            if (trendingTopics.length === 0) {
                trendingList.innerHTML = '<li class="trending-item"><span class="trending-topic" style="text-align: center; width: 100%; color: #6b7280; font-style: italic;">No trending topics yet</span></li>';
                return;
            }

            let html = '';
            trendingTopics.forEach(([topic, data], index) => {
                const displayTopic = topic.replace(/[_-]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                html += `
                    <li class="trending-item">
                        <span class="trending-rank">#${index + 1}</span>
                        <div class="trending-content">
                            <span class="trending-topic">${displayTopic}</span>
                            <span class="trending-reactions">${data.reactions} <i class="fas fa-heart"></i></span>
                        </div>
                    </li>
                `;
            });

            trendingList.innerHTML = html;
        }

        // Display forum posts
        function displayForumPosts(posts) {
            console.log('displayForumPosts called with', posts.length, 'posts');
            const postsList = document.getElementById('forumPostsList');
            if (!postsList) {
                console.error('forumPostsList not found');
                return;
            }
            if (!posts || !posts.length) {
                console.log('No posts to display');
                postsList.innerHTML = '<div style="text-align: center; padding: 2rem; color: #64748b;"><i class="fas fa-inbox"></i><p>No posts found.</p></div>';
                return;
            }

            const categoryLabels = {
                'general': 'General Discussion',
                'events': 'Events & Activities',
                'academics': 'Academics',
                'marketplace': 'Buy & Sell',
                'help': 'Help & Support'
            };

            postsList.innerHTML = posts.map(post => {
                // Get author info
                let authorName = '';
                let authorPhoto = '';
                let authorBadge = 'student';

                if (post.author_type === 'organization') {
                    authorName = post.org_name || post.author_name || 'Organization';
                    authorPhoto = post.org_photo || post.author_photo || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=8b5cf6&color=fff`;
                    authorBadge = 'organization';
                } else {
                    authorName = post.author_name || ((post.firstname || '') + ' ' + (post.lastname || '')).trim();
                    authorPhoto = post.author_photo || (post.photo_path ? baseUrl + post.photo_path.replace(/^\//, '') : null) || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=6366f1&color=fff`;
                    authorBadge = 'student';
                }

                const timeAgo = getTimeAgo(post.created_at);
                const categoryLabel = categoryLabels[post.category] || post.category;
                const tags = post.tags_array || [];
                const reactionCounts = post.reaction_counts || { total: 0 };
                const commentCount = post.comment_count || 0;
                const userReaction = post.user_reaction || null;

                const reactionIcons = {
                    'like': '👍', 'love': '❤️', 'care': '🥰', 'haha': '😂',
                    'wow': '😮', 'sad': '😢', 'angry': '😠'
                };
                const reactionIcon = userReaction ? (reactionIcons[userReaction] || '👍') : '👍';

                return `
                    <article class="forum-post">
                        <div class="post-content">
                            <div class="post-header">
                                <img src="${authorPhoto}" alt="${authorName}" class="post-author-img" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=${authorBadge === 'organization' ? '8b5cf6' : '6366f1'}&color=fff'">
                                <div class="post-meta">
                                    <div class="post-author">
                                        <span class="author-name">${escapeHtml(authorName)}</span>
                                        <span class="author-badge ${authorBadge}">${authorBadge === 'organization' ? 'Organization' : 'Student'}</span>
                                    </div>
                                    <span class="post-time">${timeAgo} • <span class="post-category">${categoryLabel}</span></span>
                                </div>
                            </div>
                            <h3 class="post-title">${escapeHtml(post.title)}</h3>
                            <p class="post-body">${escapeHtml(post.content)}</p>
                            ${post.image_url ? `<div class="post-image"><img src="${post.image_url}" alt="Post image" style="width: 100%; border-radius: 8px; margin-top: 1rem;"></div>` : ''}
                            ${tags.length > 0 ? `<div class="post-tags">${tags.map(tag => `<span class="post-tag">${escapeHtml(tag.trim())}</span>`).join('')}</div>` : ''}
                            <div class="post-footer">
                                <div class="reaction-wrapper" data-post-type="forum_post" data-post-id="${post.id}">
                                    <button class="post-action-btn reaction-btn ${userReaction ? 'reacted reaction-' + userReaction : ''}"
                                            onmouseenter="showReactionPicker(this)"
                                            onmouseleave="hideReactionPicker(this)"
                                            onclick="quickReact('forum_post', ${post.id}, this, '${userReaction || ''}')">
                                        <span class="reaction-icon">${reactionIcon}</span>
                                        ${reactionCounts.total > 0 ? `<span class="reaction-count">${reactionCounts.total}</span>` : ''}
                                    </button>
                                    <div class="reaction-picker" style="display: none;">
                                        <div class="reaction-option" data-reaction="like" onclick="setReaction('forum_post', ${post.id}, 'like', this)">👍</div>
                                        <div class="reaction-option" data-reaction="love" onclick="setReaction('forum_post', ${post.id}, 'love', this)">❤️</div>
                                        <div class="reaction-option" data-reaction="care" onclick="setReaction('forum_post', ${post.id}, 'care', this)">🥰</div>
                                        <div class="reaction-option" data-reaction="haha" onclick="setReaction('forum_post', ${post.id}, 'haha', this)">😂</div>
                                        <div class="reaction-option" data-reaction="wow" onclick="setReaction('forum_post', ${post.id}, 'wow', this)">😮</div>
                                        <div class="reaction-option" data-reaction="sad" onclick="setReaction('forum_post', ${post.id}, 'sad', this)">😢</div>
                                        <div class="reaction-option" data-reaction="angry" onclick="setReaction('forum_post', ${post.id}, 'angry', this)">😠</div>
                                    </div>
                                </div>
                                <button class="post-action-btn" onclick="toggleComments(${post.id}, 'forum_post')">
                                    <i class="fas fa-comment"></i>
                                    <span>${commentCount} ${commentCount === 1 ? 'Comment' : 'Comments'}</span>
                                </button>
                            </div>
                            <div class="comments-section" id="comments-forum_post-${post.id}" style="display: none;">
                                <div class="comments-list" id="comments-list-forum_post-${post.id}"></div>
                                <div class="comment-input-wrapper">
                                    <input type="text" class="comment-input" id="comment-input-forum_post-${post.id}" placeholder="Write a comment..." onkeypress="if(event.key==='Enter') { const btn = this.closest('.comment-input-wrapper').querySelector('.btn-send'); postComment(${post.id}, 'forum_post', btn); }">
                                    <button class="btn-send" onclick="postComment(${post.id}, 'forum_post', this)"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </article>
                `;
            }).join('');

            // Set up category filters after posts are loaded
            setupCategoryFilters();

            // Add event listeners to reaction pickers
            setTimeout(() => {
                document.querySelectorAll('.reaction-picker').forEach(picker => {
                    // Remove existing listeners to avoid duplicates
                    picker.removeEventListener('mouseenter', picker.enterHandler);
                    picker.removeEventListener('mouseleave', picker.leaveHandler);

                    // Add new listeners
                    picker.enterHandler = function() {
                        if (this.hideTimeout) {
                            clearTimeout(this.hideTimeout);
                            this.hideTimeout = null;
                        }
                    };

                    picker.leaveHandler = function() {
                        this.hideTimeout = setTimeout(() => {
                            this.style.display = 'none';
                        }, 300);
                    };

                    picker.addEventListener('mouseenter', picker.enterHandler);
                    picker.addEventListener('mouseleave', picker.leaveHandler);
                });
            }, 100);
        }

        // Open Create Post Modal
        function openCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                setTimeout(() => modal.classList.add('active'), 10);
            }
        }

        // Close Create Post Modal
        function closeCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
                setTimeout(() => {
                    modal.style.display = 'none';
                    const form = document.getElementById('createPostForm');
                    if (form) form.reset();
                    const preview = document.getElementById('postImagePreview');
                    if (preview) preview.style.display = 'none';
                }, 300);
            }
        }

        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('createPostModal');
            if (e.target === modal) closeCreatePostModal();
        });

        // Submit create post form
        function submitCreatePostForm() {
            const form = document.getElementById('createPostForm');
            if (!form) return;

            const formData = new FormData(form);
            formData.append('author_type', 'organization');

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
            submitBtn.disabled = true;

            fetch(baseUrl + 'organization/createPost', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCreatePostModal();
                    loadForumPosts(); // Reload posts
                    showToast('Post created successfully!', 'success');
                } else {
                    showToast(data.message || 'Failed to create post', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while creating the post', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Utility functions
        function getTimeAgo(dateString) {
            const now = new Date();
            const past = new Date(dateString);
            const diffInSeconds = Math.floor((now - past) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
            if (diffInSeconds < 604800) return Math.floor(diffInSeconds / 86400) + 'd ago';

            return past.toLocaleDateString();
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            `;

            // Add to container
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container';
                document.body.appendChild(container);
            }
            container.appendChild(toast);

            // Show toast
            setTimeout(() => toast.classList.add('show'), 100);

            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Reaction functions (placeholders - need to implement based on your reaction system)
        function showReactionPicker(btn) {
            const wrapper = btn.closest('.reaction-wrapper');
            const picker = wrapper ? wrapper.querySelector('.reaction-picker') : null;
            if (picker) {
                // Clear any existing hide timeout
                if (picker.hideTimeout) {
                    clearTimeout(picker.hideTimeout);
                    picker.hideTimeout = null;
                }
                picker.style.display = 'flex';
            }
        }

        function hideReactionPicker(btn) {
            const wrapper = btn.closest('.reaction-wrapper');
            const picker = wrapper ? wrapper.querySelector('.reaction-picker') : null;
            if (picker) {
                // Delay hiding to allow time to move mouse to picker
                picker.hideTimeout = setTimeout(() => {
                    picker.style.display = 'none';
                }, 300); // 300ms delay
            }
        }

        // Add event listeners to reaction pickers for better hover behavior
        document.addEventListener('DOMContentLoaded', function() {
            // This will run after posts are loaded
            setTimeout(() => {
                document.querySelectorAll('.reaction-picker').forEach(picker => {
                    picker.addEventListener('mouseenter', function() {
                        // Clear hide timeout when hovering over picker
                        if (this.hideTimeout) {
                            clearTimeout(this.hideTimeout);
                            this.hideTimeout = null;
                        }
                    });

                    picker.addEventListener('mouseleave', function() {
                        // Hide picker when leaving picker area
                        this.hideTimeout = setTimeout(() => {
                            this.style.display = 'none';
                        }, 300);
                    });
                });
            }, 1000); // Delay to ensure posts are loaded
        });

        function quickReact(postType, postId, btn, currentReaction) {
            // If already reacted, remove reaction. Otherwise, add like reaction
            if (currentReaction) {
                setReaction(postType, postId, 'like', btn); // Toggle off
            } else {
                setReaction(postType, postId, 'like', btn);
            }
        }

        function setReaction(postType, postId, reaction, element) {
            fetch(baseUrl + 'organization/likePost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `type=${postType}&post_id=${postId}&reaction_type=${reaction}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update reaction display immediately
                    const wrapper = element.closest('.reaction-wrapper');
                    const reactionBtn = wrapper ? wrapper.querySelector('.reaction-btn') : null;
                    const reactionIcon = wrapper ? wrapper.querySelector('.reaction-icon') : null;
                    const reactionCount = wrapper ? wrapper.querySelector('.reaction-count') : null;

                    if (reactionBtn && reactionIcon) {
                        // Remove all reaction classes
                        reactionBtn.classList.remove('reacted', 'reaction-like', 'reaction-love', 'reaction-care', 'reaction-haha', 'reaction-wow', 'reaction-sad', 'reaction-angry');

                        if (data.reacted && data.reaction_type) {
                            // User reacted
                            reactionBtn.classList.add('reacted', `reaction-${data.reaction_type}`);
                            reactionIcon.textContent = getReactionEmoji(data.reaction_type);
                        } else {
                            // User unreacted
                            reactionIcon.textContent = '👍';
                        }
                    }

                    // Update count if provided
                    if (reactionCount && data.total_reactions !== undefined) {
                        if (data.total_reactions > 0) {
                            reactionCount.textContent = data.total_reactions;
                            reactionCount.style.display = '';
                        } else {
                            reactionCount.style.display = 'none';
                        }
                    }

                    // Reload posts to ensure all data is up to date
                    loadForumPosts();
                } else {
                    showToast(data.message || 'Failed to update reaction', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating reaction', 'error');
            });
        }

        function getReactionEmoji(reactionType) {
            const reactionIcons = {
                'like': '👍', 'love': '❤️', 'care': '🥰', 'haha': '😂',
                'wow': '😮', 'sad': '😢', 'angry': '😠'
            };
            return reactionIcons[reactionType] || '👍';
        }

        function toggleComments(postId, postType) {
            const commentsSection = document.getElementById(`comments-${postType}-${postId}`);
            if (!commentsSection) {
                console.error(`Comments section not found for ${postType}-${postId}`);
                return;
            }

            const isVisible = commentsSection.style.display !== 'none';
            commentsSection.style.display = isVisible ? 'none' : 'block';

            if (!isVisible) {
                // Load comments if showing
                console.log(`Showing comments for ${postType}-${postId}`);
                loadComments(postId, postType);
            } else {
                console.log(`Hiding comments for ${postType}-${postId}`);
            }
        }

        function loadComments(postId, postType) {
            const commentsList = document.getElementById(`comments-list-${postType}-${postId}`);
            if (!commentsList) {
                console.error(`Comments list not found for ${postType}-${postId}`);
                return;
            }

            // Show loading state
            commentsList.innerHTML = '<div style="text-align: center; padding: 1rem; color: #6b7280;"><i class="fas fa-spinner fa-spin"></i> Loading comments...</div>';

            const url = baseUrl + 'organization/getComments?post_type=' + postType + '&post_id=' + postId;
            console.log('Loading comments from:', url);

            fetch(url)
                .then(response => {
                    console.log('Comments response status:', response.status);
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Comments data:', data);
                    if (data.success && Array.isArray(data.comments)) {
                        console.log('Processing', data.comments.length, 'comments');

                        // Debug: Log the structure of the first comment
                        if (data.comments.length > 0) {
                            console.log('First comment structure:', data.comments[0]);
                            console.log('Available fields in first comment:', Object.keys(data.comments[0]));
                        }

                        if (data.comments.length > 0) {
                            try {
                                commentsList.innerHTML = data.comments.map(comment => {
                                    // Try multiple field names for author name (matching student dashboard logic)
                                    const authorName = comment.user_name ||
                                                     comment.author_name ||
                                                     (comment.firstname && comment.lastname ? (comment.firstname + ' ' + comment.lastname) : null) ||
                                                     comment.name ||
                                                     'User';

                                    // In organization forum context, default to organization
                                    let authorType = 'organization'; // Default for organization forum

                                    // Override if we have explicit indicators
                                    if (comment.author_type) {
                                        authorType = comment.author_type;
                                    } else if (comment.user_type) {
                                        authorType = comment.user_type;
                                    } else if (comment.type) {
                                        authorType = comment.type;
                                    }

                                    // Check for student-specific indicators (strong evidence)
                                    if (comment.student_id || (comment.firstname && comment.lastname && !comment.org_name)) {
                                        authorType = 'student';
                                    }

                                    // Check for organization-specific indicators (strong evidence)
                                    if (comment.org_name || comment.org_photo || comment.organization_name || comment.org_id) {
                                        authorType = 'organization';
                                    }

                                    console.log('Comment author type detection:', {
                                        author_type: comment.author_type,
                                        user_type: comment.user_type,
                                        type: comment.type,
                                        has_student_indicators: !!(comment.student_id || (comment.firstname && comment.lastname && !comment.org_name)),
                                        has_org_indicators: !!(comment.org_name || comment.org_photo || comment.organization_name || comment.org_id),
                                        context_is_org_forum: currentContext.isOrganizationForum,
                                        final_authorType: authorType
                                    });

                                    // Try multiple field names for author photo
                                    const authorPhoto = comment.author_photo ||
                                                      comment.user_photo ||
                                                      comment.photo ||
                                                      comment.photo_path ? baseUrl + (comment.photo_path || '').replace(/^\//, '') : null;

                                    const content = comment.content || '';
                                    const createdAt = comment.created_at || new Date().toISOString();

                                    // Determine badge text and styling
                                    const badgeText = authorType === 'organization' ? 'Organization' : 'Student';
                                    const badgeClass = authorType === 'organization' ? 'organization' : 'student';

                                    console.log('Processing comment:', { authorName, authorType, content, createdAt, availableFields: Object.keys(comment) });

                                    return `
                                        <div class="comment-item">
                                            <div style="display: flex; gap: 0.75rem;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: ${authorPhoto ? 'transparent' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem; flex-shrink: 0; overflow: hidden;">
                                                    ${authorPhoto ?
                                                        `<img src="${authorPhoto}" alt="${authorName}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.style.display='none'; this.parentElement.innerHTML='${authorName.charAt(0).toUpperCase()}'">` :
                                                        authorName.charAt(0).toUpperCase()
                                                    }
                                                </div>
                                                <div style="flex: 1;">
                                                    <div class="comment-author">
                                                        <span class="author-name">${escapeHtml(String(authorName))}</span>
                                                        <span class="author-badge ${badgeClass}">${badgeText}</span>
                                                    </div>
                                                    <div class="comment-text">${escapeHtml(String(content))}</div>
                                                    <div class="comment-time">${getTimeAgo(createdAt)}</div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('');
                            } catch (error) {
                                console.error('Error processing comments:', error);
                                commentsList.innerHTML = '<div style="text-align: center; padding: 1rem; color: #ef4444;">Error processing comments: ' + error.message + '</div>';
                                showToast('Error processing comments', 'error');
                            }
                        } else {
                            commentsList.innerHTML = '<div style="text-align: center; padding: 1rem; color: #6b7280; font-style: italic;">No comments yet. Be the first to comment!</div>';
                        }
                    } else {
                        console.error('Failed to load comments:', data);
                        const errorMsg = data && data.message ? data.message : 'Invalid response format';
                        commentsList.innerHTML = '<div style="text-align: center; padding: 1rem; color: #ef4444;">Failed to load comments: ' + errorMsg + '</div>';
                        showToast('Failed to load comments', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    commentsList.innerHTML = '<div style="text-align: center; padding: 1rem; color: #ef4444;">Error loading comments: ' + error.message + '</div>';
                    showToast('Error loading comments', 'error');
                });
        }

        function postComment(postId, postType, buttonElement) {
            // Try to find comment input - check both standard and feed patterns
            let input = null;

            // If buttonElement is provided, find the input relative to it
            if (buttonElement) {
                const commentsSection = buttonElement.closest('.comments-section');
                if (commentsSection) {
                    input = commentsSection.querySelector('.comment-input');
                }
            }

            // Fallback: try to find by ID
            if (!input) {
                input = document.getElementById(`comment-input-${postType}-${postId}`) ||
                       document.getElementById(`comment-input-feed-${postType}-${postId}`);
            }

            if (!input) {
                console.error('Comment input not found for', postType, postId);
                showToast('Comment input not found', 'error');
                return;
            }

            const content = (input.value || '').trim();

            if (!content) {
                showToast('Please enter a comment', 'error');
                input.focus();
                return;
            }

            input.value = '';
            input.placeholder = 'Posting...';

            console.log('Posting comment:', { content, type: postType, target_id: postId });

            fetch(baseUrl + 'organization/comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `content=${encodeURIComponent(content)}&post_type=${postType}&post_id=${postId}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Comment post response:', data);
                if (data.success) {
                    showToast(data.message || 'Comment posted successfully', 'success');
                    input.value = '';
                    input.placeholder = 'Write a comment...';

                    // Find the comments list element relative to the button/input
                    let commentsList = null;
                    if (buttonElement) {
                        const commentsSection = buttonElement.closest('.comments-section');
                        if (commentsSection) {
                            commentsList = commentsSection.querySelector('.comments-list');
                        }
                    }

                    // Fallback: try to find by ID
                    if (!commentsList) {
                        commentsList = document.getElementById(`comments-list-${postType}-${postId}`) ||
                                     document.getElementById(`comments-list-feed-${postType}-${postId}`);
                    }

                    // Reload comments if we found the list
                    if (commentsList) {
                        loadComments(postId, postType);
                    }
                } else {
                    input.value = content;
                    input.placeholder = 'Write a comment...';
                    showToast(data.message || 'Failed to post comment', 'error');
                }
            })
            .catch(error => {
                console.error('Error posting comment:', error);
                input.value = content;
                input.placeholder = 'Write a comment...';
                showToast('Error posting comment', 'error');
            });
        }
    </script>
</body>
</html>

