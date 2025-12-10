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
                    <div class="card-body">
                        <?php if (!empty($pendingPayments)): ?>
                        <div class="payments-grid">
                            <?php foreach ($pendingPayments as $r): ?>
                                <?php
                                    $student = $r['student_name'] ?? 'Student';
                                    $studentId = $r['student_id'] ?? 'N/A';
                                    $product = $r['product'] ?? $r['product_name'] ?? 'Product';
                                    $qty = $r['quantity'] ?? 1;
                                    $amount = $r['amount'] ?? ($r['total_amount'] ?? 0);
                                    $created = !empty($r['submitted_at'] ?? $r['created_at']) ? date('M d, Y', strtotime($r['submitted_at'] ?? $r['created_at'])) : 'N/A';
                                    $payMethod = $r['payment_method'] ?? 'N/A';
                                    $proof = $r['proof_image'] ?? null;
                                    $initial = strtoupper(substr($student, 0, 1));
                                ?>
                                <article class="payment-card">
                                    <div class="payment-card-header">
        <div class="payer-info">
            <div class="payer-avatar">
                <span><?= esc($initial) ?></span>
            </div>
            <div>
                <h4><?= esc($student) ?></h4>
                <span>ID: <?= esc($studentId) ?></span>
                <div class="payment-time"><?= esc($created) ?></div>
            </div>
        </div>
                                        <span class="status-badge pending">Pending</span>
                                    </div>
                                    <div class="payment-card-body">
                                        <div class="payment-product"><i class="fas fa-box"></i> <?= esc($product) ?></div>
                                        <div class="payment-product"><i class="fas fa-hashtag"></i> Qty: <?= esc($qty) ?></div>
                                        <div class="payment-product"><i class="fas fa-credit-card"></i> Method: <?= esc($payMethod) ?></div>
                                        <div class="payment-amount-large">₱<?= number_format($amount, 2) ?></div>
                                        <?php if (!empty($proof)): ?>
                                        <div class="payment-proof">
                                            <img src="<?= esc($proof) ?>" alt="Proof of payment">
                                            <span>Proof of payment</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="payment-card-footer">
                                        <button type="button" class="btn btn-outline">View Details</button>
                                        <button type="button" class="btn btn-primary">Confirm</button>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No reservations pending.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

