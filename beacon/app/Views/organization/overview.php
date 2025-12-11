<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Overview - BEACON</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/overview.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="feed-two-col">
                    <div class="feed-main">
                        <!-- Create post card -->
                        <section class="content-card create-card">
                            <div class="create-header">
                                <div class="create-avatar">
                                    <?php
                                        $orgInitial = strtoupper(substr($organization['acronym'] ?? $organization['name'] ?? 'O', 0, 1));
                                    ?>
                                    <?php if (!empty($organization['photo'])): ?>
                                        <img src="<?= esc($organization['photo']) ?>" alt="<?= esc($organization['name'] ?? 'Organization') ?>">
                                    <?php else: ?>
                                        <span><?= esc($orgInitial) ?></span>
                                    <?php endif; ?>
                                </div>
                                <button class="create-input" type="button" data-modal-target="announcementModal" aria-label="Create post placeholder">What's on your mind?</button>
                            </div>
                            <div class="create-actions">
                                <button class="create-btn event" type="button" data-modal-target="eventModal">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>Event</span>
                                </button>
                                <button class="create-btn announcement" type="button" data-modal-target="announcementModal">
                                    <i class="fas fa-bullhorn"></i>
                                    <span>Announcement</span>
                                </button>
                                <button class="create-btn product" type="button" data-modal-target="productModal">
                                    <i class="fas fa-box-open"></i>
                                    <span>Product</span>
                                </button>
                            </div>
                        </section>

                        <!-- Combined feed -->
                        <section class="content-card feed-section">
                            <?php if (!empty($allPosts)): ?>
                                <div class="feed-list">
                                    <?php foreach ($allPosts as $idx => $post): ?>
                                            <?php
                                                $data = $post['data'];
                                                $orgName = esc($data['org_name'] ?? 'Organization');
                                                $orgAcronym = strtoupper($data['org_acronym'] ?? 'ORG');
                                                $orgPhoto = $data['org_photo'] ?? null;
                                                $orgInitial = strtoupper(substr($orgAcronym ?: $orgName, 0, 1) ?: 'O');
                                                $title = esc($data['title'] ?? $data['event_name'] ?? 'Untitled');
                                                $desc = esc($data['description'] ?? $data['content'] ?? '');
                                                $dateStr = date('M d, Y', $post['date']);
                                                $createdAt = $data['created_at'] ?? null;
                                                $createdLabel = $createdAt ? date('M d, Y g:i A', strtotime($createdAt)) : $dateStr;
                                                $eventDate = $data['date'] ?? $data['event_date'] ?? null;
                                                $eventEndDate = $data['end_date'] ?? null;
                                                $eventTime = $data['time'] ?? null;
                                                $eventEndTime = $data['end_time'] ?? null;
                                                $eventLocation = $data['location'] ?? $data['venue'] ?? null;
                                                $reactionCounts = $data['reaction_counts'] ?? [];
                                                $reactionsTotal = is_array($reactionCounts) ? array_sum($reactionCounts) : 0;
                                                $commentsCount = (int)($data['comment_count'] ?? 0);
                                                $viewsCount = isset($data['views']) ? (int)$data['views'] : null;
                                                $interestCount = isset($data['interest_count']) ? (int)$data['interest_count'] : null;
                                                $imagePath = $data['image'] ?? null;
                                                $imageUrl = null;
                                                if (!empty($imagePath)) {
                                                    $imageUrl = (stripos($imagePath, 'http') === 0)
                                                        ? $imagePath
                                                        : base_url($imagePath);
                                                }
                                                $postId = $post['type'] === 'event'
                                                    ? ($data['event_id'] ?? $data['id'] ?? null)
                                                    : ($data['announcement_id'] ?? $data['id'] ?? null);
                                                $postType = $post['type'];
                                                $commentKey = $postId ? ($postType . '-' . $postId) : ($postType . '-idx-' . $idx);

                                                // Build top reaction icons (up to 3)
                                                $reactionOrder = ['like','love','care','haha','wow','sad','angry'];
                                                $reactionEmojis = [
                                                    'like' => '👍', 'love' => '❤️', 'care' => '🤗',
                                                    'haha' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😡'
                                                ];
                                                $reactionIcons = [];
                                                foreach ($reactionOrder as $key) {
                                                    if (!empty($reactionCounts[$key])) {
                                                        $reactionIcons[$key] = $reactionCounts[$key];
                                                    }
                                                }
                                                arsort($reactionIcons);
                                                $topReactions = array_slice(array_keys($reactionIcons), 0, 3);
                                                $topReactionsList = [];
                                                foreach ($topReactions as $rKey) {
                                                    $topReactionsList[] = [
                                                        'key' => $rKey,
                                                        'count' => $reactionCounts[$rKey] ?? 0,
                                                        'emoji' => $reactionEmojis[$rKey] ?? '👍'
                                                    ];
                                                }
                                            ?>
                                            <article class="feed-card <?= $post['type'] ?>" data-post-type="<?= esc($postType) ?>" data-post-id="<?= esc($postId) ?>">
                                                <header class="post-header">
                                                    <div class="post-avatar">
                                                        <?php if (!empty($orgPhoto)): ?>
                                                            <img src="<?= esc($orgPhoto) ?>" alt="<?= $orgName ?>">
                                                        <?php else: ?>
                                                            <span><?= esc($orgInitial) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="post-author">
                                                        <div class="post-author-name"><?= $orgName ?></div>
                                                        <div class="post-meta"><?= $dateStr ?> • <?= ucfirst(esc($post['type'])) ?></div>
                                                        <div class="post-date">Posted on <?= esc($createdLabel) ?></div>
                                                    </div>
                                                    <span class="post-badge <?= $post['type'] === 'event' ? 'event' : 'announcement' ?>">
                                                        <?= $post['type'] === 'event' ? 'Event' : 'Announcement' ?>
                                                    </span>
                                                </header>
                                                <div class="post-body">
                                                    <h4><?= $title ?></h4>
                                                    <?php if (!empty($desc)): ?>
                                                        <p><?= $desc ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($post['type'] === 'event'): ?>
                                                    <div class="event-meta">
                                                        <?php if ($eventDate): ?>
                                                            <div><i class="fas fa-calendar-alt"></i> Date: <?= esc(date('M d, Y', strtotime($eventDate))) ?></div>
                                                        <?php endif; ?>
                                                        <?php if ($eventEndDate): ?>
                                                            <div><i class="fas fa-calendar-check"></i> End Date: <?= esc(date('M d, Y', strtotime($eventEndDate))) ?></div>
                                                        <?php endif; ?>
                                                        <?php if ($eventTime): ?>
                                                            <div><i class="fas fa-clock"></i> Time: <?= esc($eventTime) ?></div>
                                                        <?php endif; ?>
                                                        <?php if ($eventEndTime): ?>
                                                            <div><i class="fas fa-hourglass-end"></i> End Time: <?= esc($eventEndTime) ?></div>
                                                        <?php endif; ?>
                                                        <?php if ($eventLocation): ?>
                                                            <div><i class="fas fa-map-marker-alt"></i> Location: <?= esc($eventLocation) ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php endif; ?>
                                                <?php if (!empty($imageUrl)): ?>
                                                    <div class="post-media">
                                                        <button type="button" class="post-image-button" data-image="<?= esc($imageUrl) ?>" aria-label="View image">
                                                            <img src="<?= esc($imageUrl) ?>" alt="<?= $title ?>">
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                                </div>
                                                <div class="post-summary">
                                                    <div class="reaction-stack">
                                                        <?php foreach ($topReactionsList as $idx => $r): ?>
                                                            <span class="reaction-emoji reaction-<?= esc($r['key']) ?>">
                                                                <?= esc($r['emoji']) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                        <span class="reaction-total"><?= $reactionsTotal ?></span>
                                                    </div>
                                                    <div class="summary-stats">
                                                        <span class="stats-item" data-comment-count="count-<?= $commentKey ?>">
                                                            <i class="fas fa-comment-alt"></i>
                                                            <span class="stats-count"><?= $commentsCount ?></span>
                                                        </span>
                                                        <?php if ($viewsCount !== null): ?>
                                                            <span class="stats-item">
                                                                <i class="fas fa-eye"></i>
                                                                <span class="stats-count"><?= $viewsCount ?></span>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($post['type'] === 'event'): ?>
                                                            <span class="stats-item">
                                                                <i class="fas fa-star"></i>
                                                                <span class="stats-count"><?= $interestCount ?? 0 ?></span>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <footer class="post-actions">
                                                    <div class="action like-action">
                                                        <button type="button"><i class="far fa-thumbs-up"></i> React</button>
                                                        <div class="reaction-popover">
                                                            <button type="button" title="Like">👍</button>
                                                            <button type="button" title="Love">❤️</button>
                                                            <button type="button" title="Care">🤗</button>
                                                            <button type="button" title="Haha">😂</button>
                                                            <button type="button" title="Wow">😮</button>
                                                            <button type="button" title="Sad">😢</button>
                                                            <button type="button" title="Angry">😡</button>
                                                        </div>
                                                    </div>
                                                    <?php if ($commentKey): ?>
                                                    <button type="button" class="comment-btn" data-toggle-comments="<?= $commentKey ?>"><i class="far fa-comment-alt"></i> Comment</button>
                                                    <?php endif; ?>
                                                </footer>
                                                <?php if ($commentKey): ?>
                                                <div class="comments-panel" id="comments-<?= $commentKey ?>">
                                                    <div class="comments-list">
                                                        <?php if (!empty($data['comments'])): ?>
                                                            <?php foreach ($data['comments'] as $comment): ?>
                                                                <div class="comment-item">
                                                                    <div class="comment-author"><?= esc($comment['user_name'] ?? 'User') ?></div>
                                                                    <div class="comment-text"><?= esc($comment['content'] ?? '') ?></div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <div class="comment-empty">No comments yet.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <form class="comment-form" data-post-type="<?= $postType ?>" data-post-id="<?= $postId ?>">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="post_type" value="<?= $postType ?>">
                                                        <input type="hidden" name="post_id" value="<?= $postId ?>">
                                                        <input type="text" name="content" placeholder="Write a comment..." required>
                                                        <button type="submit">Post</button>
                                                    </form>
                                                </div>
                                                <?php endif; ?>
                                            </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="color:#64748b;">No recent activity.</p>
                            <?php endif; ?>
                        </section>
                    </div>

                    <aside class="feed-right">
                        <section class="content-card">
                            <div class="card-header" style="justify-content: space-between; align-items: center;">
                                <h4 style="margin:0;font-size:20px;color:black">Our Products</h4>
                                <button class="btn primary" type="button" data-modal-target="productModal" style="padding:0.6rem 0.9rem; font-size:0.8rem;">
                                    + Add
                                </button>
                            </div>
                            <div class="card-body" style="display:flex; flex-direction:column; gap:1rem;">
                                <?php if (!empty($products)): ?>
                                    <div class="product-card-mini">
                                        <div class="product-thumb">
                                            <?php
                                            $productImage = $products[0]['image'] ?? null;
                                            if (!empty($productImage) && trim($productImage) !== ''): ?>
                                                <?php
                                                $imageUrl = (stripos($productImage, 'http') === 0 || stripos($productImage, '//') === 0)
                                                    ? $productImage
                                                    : base_url('uploads/products/' . $productImage);
                                                ?>
                                                <img src="<?= esc($imageUrl) ?>" alt="<?= esc($products[0]['name'] ?? 'Product') ?>" class="product-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="product-placeholder" style="display: none;">
                                                    <i class="fas fa-box-open"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="product-placeholder">
                                                    <i class="fas fa-box-open"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name"><?= esc($products[0]['name'] ?? '') ?></div>
                                            <?php if (!empty($products[0]['price'])): ?>
                                                <div class="product-price">₱<?= esc($products[0]['price']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a class="text-link" href="<?= base_url('organization/products') ?>">View all products <i class="fas fa-arrow-right"></i></a>
                                <?php else: ?>
                                    <p style="color:#64748b;">No products yet.</p>
                                <?php endif; ?>
                            </div>
                        </section>

                        <section class="content-card">
                            <div class="card-header">
                            <h4 style="margin:0;font-size:20px;color:black">Member Requests</h4>
                            </div>
                            <div class="card-body">
                                <p style="color:#64748b; margin:0;">No pending requests</p>
                                <a class="text-link" href="<?= base_url('organization/members') ?>">Manage members <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </section>
                    </aside>
                </div>
            </main>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal-backdrop" id="eventModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Create Event</h3>
                <button type="button" class="modal-close" data-modal-close>&times;</button>
            </div>
            <form class="modal-body" id="eventForm" action="<?= base_url('organization/createEvent') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>
                        <span>Title</span>
                        <input type="text" name="title" required>
                    </label>
                    <label>
                        <span>Date</span>
                        <input type="date" name="date" required>
                    </label>
                    <label>
                        <span>Time</span>
                        <input type="time" name="time" required>
                    </label>
                    <label>
                        <span>End Date</span>
                        <input type="date" name="end_date">
                    </label>
                    <label>
                        <span>End Time</span>
                        <input type="time" name="end_time">
                    </label>
                    <label>
                        <span>Location</span>
                        <input type="text" name="location" required>
                    </label>
                    <label>
                        <span>Audience</span>
                        <select name="audience_type">
                            <option value="all">All</option>
                            <option value="department">Department</option>
                            <option value="specific_students">Specific Students</option>
                        </select>
                    </label>
                    <label>
                        <span>Department Access (optional)</span>
                        <input type="text" name="department_access" placeholder="e.g. CICT">
                    </label>
                    <label>
                        <span>Max Attendees</span>
                        <input type="number" name="max_attendees" min="0" placeholder="Optional">
                    </label>
                    <label class="full">
                        <span>Description</span>
                        <textarea name="description" rows="3" required></textarea>
                    </label>
                    <label class="full">
                        <span>Image</span>
                        <input type="file" name="image" accept="image/*">
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn ghost" data-modal-close>Cancel</button>
                    <button type="submit" class="btn primary">Create Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div class="modal-backdrop" id="announcementModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Create Announcement</h3>
                <button type="button" class="modal-close" data-modal-close>&times;</button>
            </div>
            <form class="modal-body" id="announcementForm" action="<?= base_url('organization/createAnnouncement') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label class="full">
                        <span>Title</span>
                        <input type="text" name="title" required>
                    </label>
                    <label class="full">
                        <span>Content</span>
                        <textarea name="content" rows="3" required></textarea>
                    </label>
                    <label>
                        <span>Priority</span>
                        <select name="priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                        </select>
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn ghost" data-modal-close>Cancel</button>
                    <button type="submit" class="btn primary">Post Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal-backdrop" id="productModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Create Product</h3>
                <button type="button" class="modal-close" data-modal-close>&times;</button>
            </div>
            <form class="modal-body" id="productForm" action="<?= base_url('organization/createProduct') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label>
                        <span>Name</span>
                        <input type="text" name="name" required>
                    </label>
                    <label>
                        <span>Price</span>
                        <input type="number" name="price" step="0.01" min="0" required>
                    </label>
                    <label>
                        <span>Stock</span>
                        <input type="number" name="stock" min="0" required>
                    </label>
                    <label>
                        <span>Sizes (comma-separated)</span>
                        <input type="text" name="sizes" placeholder="S,M,L">
                    </label>
                    <label class="full">
                        <span>Description</span>
                        <textarea name="description" rows="3"></textarea>
                    </label>
                    <label class="full">
                        <span>Image</span>
                        <div class="image-upload-container">
                            <input type="file" name="image" accept="image/*" id="productImage">
                            <div class="image-preview" id="productImagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Product preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 10px; border: 2px solid #e5e7eb;">
                            </div>
                        </div>
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn ghost" data-modal-close>Cancel</button>
                    <button type="submit" class="btn primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const openButtons = document.querySelectorAll('[data-modal-target]');
        const closeButtons = document.querySelectorAll('[data-modal-close]');
        const backdrops = document.querySelectorAll('.modal-backdrop');

        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('open');
        }
        function closeModal(modal) {
            modal.classList.remove('open');
        }

        openButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-modal-target');
                if (target) openModal(target);
            });
        });

        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal-backdrop');
                if (modal) closeModal(modal);
            });
        });

        backdrops.forEach(bd => {
            bd.addEventListener('click', (e) => {
                if (e.target === bd) closeModal(bd);
            });
        });

        // Image preview functionality
        const productImageInput = document.getElementById('productImage');
        const productImagePreview = document.getElementById('productImagePreview');
        const previewImg = document.getElementById('previewImg');

        if (productImageInput) {
            productImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Please select a valid image file.');
                        this.value = '';
                        return;
                    }

                    // Validate file size (5MB limit)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size must be less than 5MB.');
                        this.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        productImagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Hide preview if no file selected
                    productImagePreview.style.display = 'none';
                    previewImg.src = '';
                }
            });
        }

        // Reset image preview when modal closes
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal-backdrop');
                if (modal && modal.id === 'productModal') {
                    setTimeout(() => {
                        if (productImageInput) productImageInput.value = '';
                        if (productImagePreview) productImagePreview.style.display = 'none';
                        if (previewImg) previewImg.src = '';
                    }, 300);
                }
            });
        });

        // AJAX submit to keep page context
        function wireForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const modal = form.closest('.modal-backdrop');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.success) {
                        alert(data.message || 'Failed to submit. Please try again.');
                    } else {
                        // refresh to show new entry
                        window.location.reload();
                    }
                } catch (err) {
                    alert('Request failed. Please try again.');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                    if (modal) closeModal(modal);
                }
            });
        }

        wireForm('eventForm');
        wireForm('announcementForm');
        wireForm('productForm');

        // Image lightbox
        const lightboxBackdrop = document.createElement('div');
        lightboxBackdrop.className = 'modal-backdrop image-lightbox';
        lightboxBackdrop.id = 'imageLightbox';
        lightboxBackdrop.innerHTML = `
            <div class="image-lightbox-frame">
                <button type="button" class="image-lightbox-close" data-lightbox-close aria-label="Close">&times;</button>
                <img id="lightboxImg" src="" alt="Post image">
            </div>
        `;
        document.body.appendChild(lightboxBackdrop);

        const closeLightbox = () => {
            lightboxBackdrop.classList.remove('open');
            const imgEl = document.getElementById('lightboxImg');
            if (imgEl) imgEl.src = '';
        };

        lightboxBackdrop.addEventListener('click', (e) => {
            if (e.target === lightboxBackdrop || e.target.dataset.lightboxClose !== undefined) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightboxBackdrop.classList.contains('open')) {
                closeLightbox();
            }
        });

        document.querySelectorAll('.post-image-button').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.dataset.image;
                const imgEl = document.getElementById('lightboxImg');
                if (src && imgEl) {
                    imgEl.src = src;
                    lightboxBackdrop.classList.add('open');
                }
            });
        });

        // Comment toggles + submit
        document.querySelectorAll('[data-toggle-comments]').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-toggle-comments');
                const panel = document.getElementById('comments-' + targetId);
                if (panel) {
                    const wasOpen = panel.classList.contains('open');
                    panel.classList.toggle('open');
                    if (!wasOpen) {
                        // Lazy-load comments from backend
                        const form = panel.querySelector('.comment-form');
                        const postType = form?.dataset.postType;
                        const postId = form?.dataset.postId;
                        if (postType && postId && !panel.dataset.loaded) {
                            fetch(`<?= base_url('organization/getComments') ?>?post_type=${encodeURIComponent(postType)}&post_id=${encodeURIComponent(postId)}`)
                                .then(res => res.json().catch(() => ({})))
                                .then(data => {
                                    if (data?.success && Array.isArray(data.comments)) {
                                        const list = panel.querySelector('.comments-list');
                                        if (list) {
                                            list.innerHTML = '';
                                            if (data.comments.length === 0) {
                                                list.innerHTML = '<div class="comment-empty">No comments yet.</div>';
                                            } else {
                                                data.comments.forEach(c => {
                                                    const item = document.createElement('div');
                                                    item.className = 'comment-item';
                                                    const name = (c.user_name || 'User').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                                                    const body = (c.content || '').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                                                    item.innerHTML = `<div class="comment-author">${name}</div><div class="comment-text">${body}</div>`;
                                                    list.appendChild(item);
                                                });
                                                const countTarget = document.querySelector(`[data-comment-count="count-${targetId}"]`);
                                                if (countTarget) {
                                                    countTarget.innerHTML = `<i class="fas fa-comment-alt"></i> ${data.comments.length}`;
                                                }
                                            }
                                        }
                                        panel.dataset.loaded = 'true';
                                    }
                                })
                                .catch(() => {});
                        }
                        const input = panel.querySelector('input[name="content"]');
                        if (input) input.focus();
                    }
                }
            });
        });

        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;
                try {
                    const res = await fetch('<?= base_url('organization/comment') ?>', {
                        method: 'POST',
                        body: new FormData(form),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.success) {
                        alert(data.message || 'Failed to post comment.');
                    } else {
                        const list = form.closest('.comments-panel')?.querySelector('.comments-list');
                        if (list) {
                            const empty = list.querySelector('.comment-empty');
                            if (empty) empty.remove();
                            const item = document.createElement('div');
                            item.className = 'comment-item';
                            item.innerHTML = `
                                <div class="comment-author">${(data.comment?.user_name || 'You')}</div>
                                <div class="comment-text">${(data.comment?.content || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
                            `;
                            list.appendChild(item);
                        }
                        const countTarget = form.closest('.feed-card')?.querySelector('[data-comment-count]');
                        if (countTarget) {
                            const parts = countTarget.textContent.trim().split(/\s+/);
                            const num = parseInt(parts.pop(), 10);
                            const newNum = isNaN(num) ? 1 : num + 1;
                            countTarget.innerHTML = `<i class="fas fa-comment-alt"></i> ${newNum}`;
                            countTarget.dataset.loaded = 'true';
                        }
                        form.reset();
                    }
                } catch (err) {
                    alert('Request failed. Please try again.');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        });

        // CSRF for posts
        const csrfName = '<?= csrf_token() ?>';
        const csrfValue = '<?= csrf_hash() ?>';

        // Reactions: open on click, keep open while choosing, post reaction
        const likeActions = document.querySelectorAll('.like-action');
        const hideTimers = new WeakMap();
        const scheduleClose = (el, delay = 800) => {
            const existing = hideTimers.get(el);
            if (existing) clearTimeout(existing);
            hideTimers.set(el, setTimeout(() => {
                el.classList.remove('open');
            }, delay));
        };
        const cancelClose = (el) => {
            const existing = hideTimers.get(el);
            if (existing) {
                clearTimeout(existing);
                hideTimers.delete(el);
            }
        };

        likeActions.forEach(action => {
            const reactBtn = action.querySelector('button');
            const popover = action.querySelector('.reaction-popover');
            if (reactBtn && popover) {
                reactBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    likeActions.forEach(a => { if (a !== action) a.classList.remove('open'); });
                    action.classList.toggle('open');
                    if (action.classList.contains('open')) {
                        action.dataset.opened = 'true';
                    }
                });
                // Keep popover open briefly after hover out
                [action, popover].forEach(el => {
                    el.addEventListener('mouseenter', () => cancelClose(action));
                    el.addEventListener('mouseleave', () => scheduleClose(action));
                });

                popover.querySelectorAll('button').forEach(rbtn => {
                    rbtn.addEventListener('click', async (e) => {
                        e.stopPropagation();
                        const reaction = rbtn.title?.toLowerCase() || 'like';
                        const card = action.closest('.feed-card');
                        const postType = card?.dataset.postType;
                        const postId = card?.dataset.postId;
                        if (!postType || !postId) return;
                        try {
                            const body = new URLSearchParams({
                                post_type: postType,
                                post_id: postId,
                                reaction: reaction
                            });
                            if (csrfName && csrfValue) body.append(csrfName, csrfValue);
                            const res = await fetch('<?= base_url('organization/likePost') ?>', {
                                method: 'POST',
                                body
                            });
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok || !data.success) {
                                alert(data.message || 'Reaction failed.');
                            }
                        } catch (_) {
                            alert('Reaction failed.');
                        }
                        action.classList.remove('open');
                    });
                });
            }
        });

        document.addEventListener('click', () => {
            likeActions.forEach(a => a.classList.remove('open'));
        });
    });
    </script>
</body>
</html>
