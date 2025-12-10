<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Events - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/events.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Events</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentEvents)): ?>
                        <div class="events-grid">
                            <?php $currentOrgId = session()->get('organization_id'); ?>
                            <?php foreach ($recentEvents as $event): ?>
                                <?php
                                    $statusRaw = strtolower(trim($event['status'] ?? 'upcoming'));
                                    $statusClass = in_array($statusRaw, ['upcoming','ongoing','ended'], true) ? $statusRaw : 'upcoming';
                                    $displayStatus = $event['status'] ?? 'Upcoming';
                                    $displayDate = !empty($event['date']) ? date('M d, Y', strtotime($event['date'])) : 'TBD';
                                    $location = $event['location'] ?? 'TBD';
                                    $time = $event['time'] ?? '';
                                    $endDate = $event['end_date'] ?? null;
                                    $endTime = $event['end_time'] ?? null;
                                    $initial = strtoupper(substr($event['event_name'] ?? 'E', 0, 1));
                                    $rawTitle = $event['event_name'] ?? $event['title'] ?? '';
                                    $displayTitle = trim($rawTitle) !== '' ? $rawTitle : 'Untitled Event';
                                    $rawDesc = trim($event['description'] ?? '');
                                    $shortDesc = strlen($rawDesc) > 140 ? substr($rawDesc, 0, 140) . '…' : $rawDesc;
                                    $orgName = $event['org_name'] ?? 'Organization';
                                    $orgAcronym = $event['org_acronym'] ?? '';
                                    $fullLocation = $event['venue'] ?? $event['location'] ?? 'TBD';
                                    $eventId = $event['id'] ?? null;
                                    $isOwn = $currentOrgId && $eventId && isset($event['org_id']) && ((int)$event['org_id'] === (int)$currentOrgId);
                                ?>
                                <div class="event-card"
                                     data-title="<?= esc($displayTitle) ?>"
                                     data-org="<?= esc($orgName . ($orgAcronym ? ' (' . $orgAcronym . ')' : '')) ?>"
                                     data-date="<?= esc($displayDate) ?>"
                                     data-time="<?= esc($time) ?>"
                                     data-end-date="<?= esc($endDate ?: '') ?>"
                                     data-end-time="<?= esc($endTime ?: '') ?>"
                                     data-location="<?= esc($fullLocation) ?>"
                                     data-status="<?= esc($displayStatus) ?>"
                                     data-description="<?= esc($rawDesc) ?>"
                                     data-event-id="<?= esc($eventId) ?>"
                                     data-owned="<?= $isOwn ? '1' : '0' ?>"
                                     data-org-id="<?= esc($event['org_id'] ?? '') ?>">
                                    <div class="event-card-image">
                                        <div class="event-placeholder"><?= esc($initial) ?></div>
                                        <span class="event-status <?= esc($statusClass) ?>"><?= esc($displayStatus) ?></span>
                                    </div>
                                    <div class="event-card-body">
                                        <h3><?= esc($displayTitle) ?></h3>
                                        <p class="event-org">By <?= esc($orgName) ?></p>
                                        <div class="event-info">
                                            <span>Date: <?= esc($displayDate) ?></span>
                                            <span>Location: <?= esc($location) ?></span>
                                        </div>
                                        <?php if (!empty($shortDesc)): ?>
                                            <p class="event-description"><?= esc($shortDesc) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No events available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div class="event-modal-backdrop" id="eventModal" aria-hidden="true">
        <div class="event-modal">
            <div class="event-modal-header">
                <h3 id="modalTitle">Event</h3>
                <button type="button" class="modal-close" data-close>&times;</button>
            </div>
            <div class="event-modal-body">
                <p class="modal-org" id="modalOrg"></p>
                <div class="modal-info">
                    <span id="modalDate"></span>
                    <span id="modalTime"></span>
                    <span id="modalLocation"></span>
                    <span id="modalStatus" class="badge"></span>
                </div>
                <p id="modalDescription"></p>
            </div>
            <div class="event-modal-footer">
                <button type="button" class="btn btn-danger" id="modalDeleteBtn" style="display:none;">Delete / Cancel Event</button>
                <button type="button" class="btn btn-secondary" data-close>Close</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('eventModal');
        const titleEl = document.getElementById('modalTitle');
        const orgEl = document.getElementById('modalOrg');
        const dateEl = document.getElementById('modalDate');
        const timeEl = document.getElementById('modalTime');
        const locationEl = document.getElementById('modalLocation');
        const statusEl = document.getElementById('modalStatus');
        const descEl = document.getElementById('modalDescription');
        const deleteBtn = document.getElementById('modalDeleteBtn');
        const deleteUrlBase = "<?= base_url('organization/events/delete/') ?>";
        const currentOrgId = "<?= esc(session()->get('organization_id') ?? '') ?>";
        let currentEventId = null;

        const openModal = (card) => {
            // reset state every open
            deleteBtn.style.display = 'none';
            deleteBtn.disabled = false;
            deleteBtn.textContent = 'Delete / Cancel Event';
            currentEventId = null;

            const get = (key) => card.dataset[key] || '';
            titleEl.textContent = get('title');
            orgEl.textContent = get('org');
            const endDate = get('endDate');
            const endTime = get('endTime');
            const dateRange = endDate ? `${get('date')} – ${endDate}` : get('date');
            const timeRange = endTime ? `${get('time')} – ${endTime}` : (get('time') || 'Time: TBD');
            dateEl.textContent = `Date: ${dateRange}`;
            timeEl.textContent = `Time: ${timeRange}`;
            locationEl.textContent = `Location: ${get('location') || 'TBD'}`;
            statusEl.textContent = get('status') || 'Status';
            descEl.textContent = get('description') || 'No description provided.';
            currentEventId = get('eventId');
            const owned = get('owned') === '1';
            const eventOrgId = get('orgId');
            const canDelete = owned && currentEventId && currentOrgId && eventOrgId && (currentOrgId === eventOrgId);
            if (canDelete) {
                deleteBtn.style.display = 'inline-flex';
            }
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = () => {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        };

        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.dataset.close !== undefined) {
                closeModal();
            }
        });

        document.querySelectorAll('.event-card').forEach(card => {
            card.addEventListener('click', () => openModal(card));
        });

        deleteBtn.addEventListener('click', () => {
            if (!currentEventId) return;
            if (!confirm('Delete/Cancel this event?')) return;
            deleteBtn.disabled = true;
            deleteBtn.textContent = 'Deleting...';
            fetch(deleteUrlBase + currentEventId, { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to delete event.');
                    }
                })
                .catch(() => alert('Failed to delete event.'))
                .finally(() => {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = 'Delete / Cancel Event';
                });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    });
    </script>
</body>
</html>

