<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Products - BEACON</title>
    <?php helper('url'); ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/beacon-logo-v4.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/topbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/organization.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/organization/products.css') ?>">
</head>
<body>
    <div class="dashboard-container">
        <?= view('organization/partials/sidebar') ?>
        <div class="dashboard-wrapper">
            <?= view('organization/partials/topbar') ?>
            <main class="dashboard-main">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Products & Merchandise</h2>
                        <p class="section-subtitle">Manage your organization's products and inventory</p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($products)): ?>
                        <div class="products-grid">
                            <?php foreach ($products as $p): ?>
                                <?php
                                    $name = $p['product_name'] ?? $p['name'] ?? 'Product';
                                    $desc = $p['description'] ?? '';
                                    $price = $p['price'] ?? 0;
                                    $stock = (int)($p['stock'] ?? 0);
                                    $sold = (int)($p['sold'] ?? 0);
                                    $image = $p['image'] ?? null;
                                    $status = $p['status'] ?? 'available';
                                    $statusClass = $status === 'out_of_stock' ? 'out' : ($status === 'low_stock' ? 'low' : 'available');
                                    $placeholder = strtoupper(substr($name, 0, 1));
                                ?>
                                <div class="product-card <?= $status === 'out_of_stock' ? 'out-of-stock' : '' ?>">
                                    <div class="product-image">
                                        <?php if (!empty($image)): ?>
                                            <img src="<?= base_url('uploads/products/' . $image) ?>" alt="<?= esc($name) ?>">
                                        <?php else: ?>
                                            <div class="product-placeholder"><?= esc($placeholder) ?></div>
                                        <?php endif; ?>
                                        <span class="stock-badge <?= esc($statusClass) ?>">
                                            <?= $status === 'out_of_stock' ? 'Out of Stock' : ($status === 'low_stock' ? 'Low Stock' : 'In Stock') ?>
                                        </span>
                                    </div>
                                    <div class="product-body">
                                        <h3><?= esc($name) ?></h3>
                                        <p class="product-desc"><?= esc($desc ?: 'No description') ?></p>
                                        <div class="product-meta">
                                            <span class="price">₱<?= number_format($price, 2) ?></span>
                                            <span class="stock <?= $stock == 0 ? 'danger' : ($stock <= 10 ? 'warning' : '') ?>">Stock: <?= $stock ?></span>
                                        </div>
                                        <div class="product-stats">
                                            <span><i class="fas fa-shopping-cart"></i> <?= $sold ?> sold</span>
                                        </div>
                                    </div>
                                    <div class="product-footer">
                                        <button class="btn btn-outline" onclick="updateStock(<?= $p['product_id'] ?? $p['id'] ?? 0 ?>)">
                                            <i class="fas fa-boxes"></i> <?= $stock == 0 ? 'Restock' : 'Update Stock' ?>
                                        </button>
                                        <button class="btn btn-primary" onclick="editProduct(<?= $p['product_id'] ?? $p['id'] ?? 0 ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p style="color:#64748b;">No products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

