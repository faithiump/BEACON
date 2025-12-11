<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Announcements - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/announcements.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main announcements-page">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Announcements</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentAnnouncements)): ?>
                        <div class="announcements-grid">
                            <?php foreach ($recentAnnouncements as $a): ?>
                                <?php
                                    $annId = $a['announcement_id'] ?? $a['id'] ?? 0;
                                    $orgName = $a['org_name'] ?? 'Organization';
                                    $orgAcronym = $a['org_acronym'] ?? '';
                                    $orgLabel = $orgAcronym ? ($orgName . ' (' . $orgAcronym . ')') : $orgName;
                                    $orgPhoto = $a['org_photo'] ?? null;
                                    $orgInitial = strtoupper(substr($orgName, 0, 1));
                                    $created = !empty($a['created_at']) ? date('M d, Y', strtotime($a['created_at'])) : 'N/A';
                                    $title = trim($a['title'] ?? '') ?: 'Untitled Announcement';
                                    $content = trim($a['content'] ?? '');
                                    $excerpt = strlen($content) > 180 ? substr($content, 0, 180) . '…' : $content;
                                    $priority = strtolower($a['priority'] ?? 'normal');
                                    $priorityClass = in_array($priority, ['high','normal'], true) ? $priority : 'normal';
                                    $priorityLabel = $priority === 'high' ? 'High Priority' : 'Normal';
                                    $views = (int)($a['views'] ?? 0);
                                    $reactions = $a['reaction_counts'] ?? [];
                                    $totalReactions = is_array($reactions) ? array_sum($reactions) : 0;
                                    $commentCount = (int)($a['comment_count'] ?? 0);
                                ?>
                                <article class="announcement-card <?= esc($priorityClass) ?>" data-announcement-id="<?= (int)$annId ?>">
                                    <header class="announcement-card-header">
                                        <div class="announcement-author">
                                            <div class="announcement-avatar">
                                                <?php if (!empty($orgPhoto)): ?>
                                                    <img src="<?= esc($orgPhoto) ?>" alt="<?= esc($orgLabel) ?>">
                                                <?php else: ?>
                                                    <span><?= esc($orgInitial) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="announcement-meta">
                                                <div class="announcement-org"><?= esc($orgLabel) ?></div>
                                                <div class="announcement-date"><?= esc($created) ?></div>
                                            </div>
                                        </div>
                                        <span class="announcement-badge">Announcement</span>
                                    </header>
                                    <div class="announcement-card-body">
                                        <h3><?= esc($title) ?></h3>
                                        <?php if (!empty($excerpt)): ?>
                                            <p><?= esc($excerpt) ?></p>
                                        <?php endif; ?>
                                        <div class="announcement-tags">
                                            <span class="priority-badge <?= esc($priorityClass) ?>"><?= esc($priorityLabel) ?></span>
                                        </div>
                                    </div>
                                    <footer class="announcement-card-footer">
                                        <div class="announcement-stats">
                                            <span data-views><?= esc($views) ?> views</span>
                                            <span><?= esc($totalReactions) ?> reactions</span>
                                            <span><?= esc($commentCount) ?> comments</span>
                                        </div>
                                        <div class="announcement-actions">
                                            <?php $annId = $a['announcement_id'] ?? $a['id'] ?? 0; ?>
                                            <button class="btn btn-primary btn-sm" type="button" onclick="editAnnouncement(<?= (int)$annId ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-outline btn-sm" type="button" onclick="deleteAnnouncement(<?= (int)$annId ?>, this)">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </footer>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No announcements available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div class="ann-modal-overlay" id="editAnnouncementModal">
        <div class="ann-modal ann-modal-sm">
            <div class="ann-modal-header">
                <h3><i class="fas fa-edit"></i> Edit Announcement</h3>
                <button class="ann-modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editAnnouncementForm" class="ann-modal-body">
                <?= csrf_field() ?>
                <input type="hidden" id="editAnnId" name="announcement_id">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" id="editTitle" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea id="editContent" name="content" class="form-input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select id="editPriority" name="priority" class="form-input">
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </form>
            <div class="ann-modal-footer">
                <button class="btn btn-outline" type="button" onclick="closeEditModal()">Cancel</button>
                <button class="btn btn-primary" type="button" onclick="submitEditAnnouncement()">Save</button>
            </div>
        </div>
    </div>

    <script>
const baseUrl = "<?= base_url() ?>"; // already ends with "/"
let csrfName = "<?= csrf_token() ?>";
let csrfHash = "<?= csrf_hash() ?>";

const modalEl = document.getElementById("editAnnouncementModal");
const titleInput = document.getElementById("editTitle");
const contentInput = document.getElementById("editContent");
const prioritySelect = document.getElementById("editPriority");
const idInput = document.getElementById("editAnnId");

function openEditModal() {
    if (modalEl) modalEl.classList.add("active");
}

function closeEditModal() {
    if (modalEl) modalEl.classList.remove("active");
}

function normalizeAnnouncement(data) {
    const ann = data?.data ?? data ?? {};
    // Also accept nested announcement key if API returns {announcement: {...}}
    const inner = ann.announcement ?? ann;
    return {
        id: inner.announcement_id ?? inner.id ?? inner.announcementId ?? null,
        title: inner.title ?? inner.announcement_title ?? inner.name ?? "",
        content: inner.content ?? inner.body ?? inner.description ?? "",
        priority: (inner.priority ?? inner.status ?? "normal").toLowerCase() === "high" ? "high" : "normal"
    };
}

function editAnnouncement(id) {
    if (!id || id <= 0) {
        alert("Announcement ID is missing.");
        return;
    }

    fetch(`${baseUrl}organization/announcements/get/${id}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.success === false) {
                alert(data.message || "Failed to load announcement");
                return;
            }

            const ann = normalizeAnnouncement(data);
            if (!ann.id) ann.id = id; // ensure fallback

            if (idInput) idInput.value = ann.id;
            if (titleInput) titleInput.value = ann.title;
            if (contentInput) contentInput.value = ann.content;
            if (prioritySelect) prioritySelect.value = ann.priority;

            openEditModal();
        })
        .catch(() => alert("Failed to load announcement"));
}

function submitEditAnnouncement() {
    const id = idInput?.value;
    const title = titleInput?.value.trim();
    const content = contentInput?.value.trim();
    const priority = prioritySelect?.value;

    if (!id || !title || !content) {
        alert("Please fill in all required fields.");
        return;
    }

    const formData = new URLSearchParams();
    formData.append("title", title);
    formData.append("content", content);
    formData.append("priority", priority || "normal");
    formData.append(csrfName, csrfHash);

    fetch(`${baseUrl}organization/announcements/update/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.csrfHash) csrfHash = data.csrfHash;
        if (data.success) {
            closeEditModal();
            setTimeout(() => location.reload(), 400);
        } else {
            alert(data.message || "Failed to update announcement");
        }
    })
    .catch(() => alert("Error updating announcement"));
}

function deleteAnnouncement(id, btnRef) {
    if (!id || id <= 0) {
        alert("Announcement ID is missing.");
        return;
    }
    if (!confirm("Delete this announcement?")) return;

    const formData = new URLSearchParams();
    formData.append(csrfName, csrfHash);

    fetch(`${baseUrl}organization/announcements/delete/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.csrfHash) csrfHash = data.csrfHash;
        if (data.success) {
            // Remove card from DOM
            if (btnRef) {
                const card = btnRef.closest('.announcement-card');
                if (card) card.remove();
            }
            // Ensure UI stays in sync
            setTimeout(() => location.reload(), 300);
        } else {
            alert(data.message || "Failed to delete announcement");
        }
    })
    .catch(() => alert("Error deleting announcement"));
}

// Allow escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});

// Track views for announcements on this page
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.announcement-card[data-announcement-id]');
    cards.forEach(card => {
        const announcementId = card.getAttribute('data-announcement-id');
        if (!announcementId) return;
        fetch(`${baseUrl}organization/trackView`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `type=announcement&id=${announcementId}`
        }).then(res => res.json())
        .then(data => {
            if (data.success && data.views !== undefined) {
                // optionally update UI if a views element exists in the card
                const stats = card.querySelector('.announcement-stats');
                if (stats) {
                    const viewsSpan = stats.querySelector('[data-views]');
                    if (viewsSpan) {
                        viewsSpan.textContent = `${data.views} views`;
                    }
                }
            }
        }).catch(() => {
            // silent fail
        });
    });
});
</script>


</body>
</html>

