<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Reservations - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/reservations.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Reservations</h2>
                    </div>
                    <div class="card-body" id="reservationsPage">
                        <section class="reservation-section">
                            <div class="section-head">
                                <h3><i class="fas fa-hourglass-half"></i> Pending Reservations</h3>
                                <span class="section-hint">Review and confirm incoming reservations.</span>
                            </div>
                            <div id="pendingContainer" class="payments-grid"></div>
                        </section>
                        <section class="reservation-section" style="margin-top:1.5rem;">
                            <div class="section-head">
                                <h3><i class="fas fa-clipboard-check"></i> Reserved List</h3>
                                <span class="section-hint">Confirmed reservations with user details.</span>
                            </div>
                            <div id="confirmedContainer" class="payments-grid"></div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
<style>
    .reservation-section .section-head {
        display:flex; flex-direction:column; gap:0.15rem; margin-bottom:0.75rem;
    }
    .section-head h3 { margin:0; color:#0f172a; font-weight:800; font-size:1.05rem; }
    .section-hint { color:#64748b; font-size:0.9rem; }
    .payments-grid {
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
        gap:1rem;
    }
    .payment-card { border:1px solid #e2e8f0; border-radius:12px; background:#fff; padding:0.9rem; display:flex; flex-direction:column; gap:0.65rem; box-shadow:0 4px 12px rgba(15,23,42,0.05); }
    .payment-card-header { display:flex; align-items:flex-start; justify-content:space-between; gap:0.75rem; }
    .payer-info { display:flex; gap:0.75rem; align-items:center; }
    .payer-avatar { width:44px; height:44px; border-radius:50%; background:#64116e; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; }
    .payment-card-body { display:flex; flex-direction:column; gap:0.35rem; }
    .payment-product { color:#334155; font-weight:600; }
    .payment-amount-large { font-size:1.2rem; font-weight:800; color:#0f172a; }
    .payment-card-footer { display:flex; gap:0.5rem; justify-content:flex-end; flex-wrap:wrap; }
    .status-badge.pending { background:#fff7ed; color:#c2410c; padding:0.3rem 0.6rem; border-radius:999px; font-weight:700; font-size:0.85rem; border:1px solid #fed7aa; }
    .status-badge.confirmed { background:#ecfdf3; color:#166534; padding:0.3rem 0.6rem; border-radius:999px; font-weight:700; font-size:0.85rem; border:1px solid #bbf7d0; }
    .btn { border:none; cursor:pointer; border-radius:10px; padding:0.55rem 0.9rem; font-weight:700; }
    .btn-outline { background:#fff; color:#0f172a; border:1px solid #e2e8f0; }
    .btn-outline:hover { border-color:#cbd5e1; }
    .btn-primary { background:#64116e; color:#fff; }
    .btn-primary:hover { background:#530e5b; }
    .btn-danger { background:#fee2e2; color:#b91c1c; border:1px solid #fecdd3; }
    .empty-state { color:#94a3b8; padding:0.5rem 0; }
</style>
<script>
    const baseUrl = "<?= rtrim(base_url('/'), '/') ?>/";

    const pendingContainer = document.getElementById('pendingContainer');
    const confirmedContainer = document.getElementById('confirmedContainer');

    const fmtPeso = (v) => '₱' + Number(v || 0).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    const fmtDate = (v) => v ? new Date(v).toLocaleDateString() : 'N/A';

    function renderEmpty(container, text) {
        container.innerHTML = `<div class="empty-state">${text}</div>`;
    }

    function renderCard(item, isPending) {
        const initial = (item.student_name || 'S').trim().charAt(0).toUpperCase() || 'S';
        const statusBadge = isPending ? '<span class="status-badge pending">Pending</span>' : '<span class="status-badge confirmed">Confirmed</span>';
        const actions = isPending
            ? `<button class="btn btn-danger" onclick="actReservation(${item.id}, 'reject')">Reject</button>
               <button class="btn btn-primary" onclick="actReservation(${item.id}, 'approve')">Confirm</button>`
            : '';
        const proof = item.proof_image ? `<div class="payment-proof"><img src="${item.proof_image}" alt="Proof"><span>Proof of payment</span></div>` : '';
        const amountLabel = 'Amount of product';
        return `
            <article class="payment-card">
                <div class="payment-card-header">
                    <div class="payer-info">
                        <div class="payer-avatar">${initial}</div>
                        <div>
                            <h4 style="margin:0; color:#0f172a;">${item.student_name || 'Student'}</h4>
                            <div style="color:#475569; font-size:0.9rem;">ID: ${item.student_id || 'N/A'}</div>
                            <div class="payment-time" style="color:#94a3b8; font-size:0.85rem;">${fmtDate(item.created_at || item.confirmed_at)}</div>
                        </div>
                    </div>
                    ${statusBadge}
                </div>
                <div class="payment-card-body">
                    <div class="payment-product"><i class="fas fa-box"></i> ${item.product_name || item.product || 'Product'}</div>
                    <div class="payment-product"><i class="fas fa-hashtag"></i> Qty: ${item.quantity ?? 1}</div>
                    <div class="payment-product"><i class="fas fa-money-bill"></i> ${amountLabel}: ${fmtPeso(item.total_amount || item.amount || 0)}</div>
                    ${proof}
                </div>
                <div class="payment-card-footer">
                    ${actions}
                </div>
            </article>
        `;
    }

    function loadPending() {
        pendingContainer.innerHTML = '<div class="empty-state">Loading pending reservations...</div>';
        fetch(baseUrl + 'organization/payments/pending')
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.data || res.data.length === 0) {
                    renderEmpty(pendingContainer, 'No reservations pending.');
                    return;
                }
                pendingContainer.innerHTML = res.data.map(item => renderCard(item, true)).join('');
            })
            .catch(() => renderEmpty(pendingContainer, 'Failed to load pending reservations.'));
    }

    function loadConfirmed() {
        confirmedContainer.innerHTML = '<div class="empty-state">Loading reserved list...</div>';
        fetch(baseUrl + 'organization/payments/history')
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.data || res.data.length === 0) {
                    renderEmpty(confirmedContainer, 'No confirmed reservations yet.');
                    return;
                }
                confirmedContainer.innerHTML = res.data.map(item => renderCard(item, false)).join('');
            })
            .catch(() => renderEmpty(confirmedContainer, 'Failed to load reserved list.'));
    }

    function actReservation(id, action) {
        const form = new URLSearchParams();
        form.append('payment_id', id);
        form.append('action', action);
        fetch(baseUrl + 'organization/payments/confirm', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: form.toString()
        })
        .then(r => r.json())
        .then(res => {
            alert(res.message || 'Action completed');
            loadPending();
            loadConfirmed();
        })
        .catch(() => alert('Failed to process reservation.'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadPending();
        loadConfirmed();
    });
</script>
</body>
</html>

