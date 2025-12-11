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
                                    $reserved = (int)($p['reserved'] ?? $p['sold'] ?? 0);
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
                                            <span><i class="fas fa-clipboard-list"></i> <?= $reserved ?> reserved</span>
                                        </div>
                                    </div>
                                    <div class="product-footer">
                                        <button class="btn btn-outline" onclick="openStockModal(<?= $p['product_id'] ?? $p['id'] ?? 0 ?>)">
                                            <i class="fas fa-boxes"></i> <?= $stock == 0 ? 'Restock' : 'Update Stock' ?>
                                        </button>
                                        <button class="btn btn-primary" onclick="openEditModal(<?= $p['product_id'] ?? $p['id'] ?? 0 ?>)">
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
<!-- Modals -->
<div class="org-modal-backdrop" id="productModalBackdrop" style="display:none;"></div>

<div class="org-modal" id="stockModal" aria-hidden="true" style="display:none;">
    <div class="org-modal-content">
        <div class="org-modal-header">
            <h3><i class="fas fa-boxes"></i> Update Stock</h3>
            <button class="close" onclick="closeStockModal()">&times;</button>
        </div>
        <div class="org-modal-body">
            <form id="stockForm">
                <input type="hidden" id="stockProductId">
                <label for="stockInput">New Stock Quantity</label>
                <input type="number" id="stockInput" min="0" class="form-input" required>
            </form>
        </div>
        <div class="org-modal-footer">
            <button class="btn btn-secondary" onclick="closeStockModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitStockModal()">Save</button>
        </div>
    </div>
</div>

<div class="org-modal" id="editModal" aria-hidden="true" style="display:none;">
    <div class="org-modal-content">
        <div class="org-modal-header">
            <h3><i class="fas fa-edit"></i> Edit Product</h3>
            <button class="close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="org-modal-body">
            <form id="editForm">
                <input type="hidden" id="editProductId">
                <div class="form-group">
                    <label for="editName">Product Name</label>
                    <input type="text" id="editName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="editDesc">Description</label>
                    <textarea id="editDesc" class="form-input" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="editPrice">Price</label>
                    <input type="number" id="editPrice" class="form-input" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="editSizes">Sizes (comma separated)</label>
                    <input type="text" id="editSizes" class="form-input">
                </div>
            </form>
        </div>
        <div class="org-modal-footer">
            <button class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitEditModal()">Save</button>
        </div>
    </div>
</div>

<style>
    .org-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        z-index: 10000;
    }
    .org-modal {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10001;
        padding-top: 70px;
        padding-bottom: 30px;
        box-sizing: border-box;
    }
    .org-modal-content {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        width: 720px;
        max-width: 95vw;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18);
        border: 1px solid #e2e8f0;
        animation: fadeInScale 0.15s ease;
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }
    .org-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .org-modal-header h3 {
        margin: 0;
        font-size: 1.05rem;
        color: #0f172a;
        font-weight: 800;
    }
    .org-modal-body {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        overflow-y: auto;
        padding-right: 0.25rem;
        max-height: 60vh;
    }
    .org-modal-footer {
        margin-top: 1rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .org-modal .close {
        border: none;
        background: none;
        font-size: 1.15rem;
        cursor: pointer;
        color: #475569;
    }
    .org-modal .form-input, .org-modal textarea {
        width: 100%;
        padding: 0.65rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
    }
    .org-modal .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .btn.btn-secondary {
        background: #fff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }
    .btn.btn-secondary:hover {
        border-color: #cbd5e1;
    }
</style>

<script>
    const baseUrl = "<?= rtrim(base_url('/'), '/') ?>/";

    const backdrop = document.getElementById('productModalBackdrop');
    const stockModal = document.getElementById('stockModal');
    const editModal = document.getElementById('editModal');

    function openBackdrop() {
        backdrop.style.display = 'block';
    }
    function closeBackdrop() {
        backdrop.style.display = 'none';
    }

    function openStockModal(id) {
        document.getElementById('stockProductId').value = id;
        document.getElementById('stockInput').value = '';
        stockModal.style.display = 'flex';
        openBackdrop();
    }
    function closeStockModal() {
        stockModal.style.display = 'none';
        closeBackdrop();
    }
    function submitStockModal() {
        const productId = document.getElementById('stockProductId').value;
        const qty = parseInt(document.getElementById('stockInput').value, 10);
        if (isNaN(qty) || qty < 0) {
            alert('Please enter a valid non-negative number.');
            return;
        }
        fetch(baseUrl + 'organization/products/stock', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&stock=${qty}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Stock updated successfully.');
                location.reload();
            } else {
                alert(data.message || 'Failed to update stock.');
            }
        })
        .catch(() => alert('Error updating stock.'));
    }

    function openEditModal(id) {
        fetch(baseUrl + 'organization/products/get/' + id)
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.product) {
                    alert(res.message || 'Product not found.');
                    return;
                }
                const p = res.product;
                document.getElementById('editProductId').value = p.product_id;
                document.getElementById('editName').value = p.product_name || '';
                document.getElementById('editDesc').value = p.description || '';
                document.getElementById('editPrice').value = p.price || 0;
                document.getElementById('editSizes').value = p.sizes || '';
                editModal.style.display = 'flex';
                openBackdrop();
            })
            .catch(() => alert('Error loading product.'));
    }
    function closeEditModal() {
        editModal.style.display = 'none';
        closeBackdrop();
    }
    function submitEditModal() {
        const id = document.getElementById('editProductId').value;
        const name = document.getElementById('editName').value.trim();
        const desc = document.getElementById('editDesc').value;
        const price = parseFloat(document.getElementById('editPrice').value);
        const sizes = document.getElementById('editSizes').value;

        if (!name) { alert('Name is required'); return; }
        if (isNaN(price) || price < 0) { alert('Invalid price'); return; }

        const body = new URLSearchParams();
        body.append('name', name);
        body.append('description', desc);
        body.append('price', price);
        body.append('sizes', sizes);

        fetch(baseUrl + 'organization/products/update/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Product updated successfully.');
                location.reload();
            } else {
                alert(data.message || 'Failed to update product.');
            }
        })
        .catch(() => alert('Error updating product.'));
    }

    // Close modals on ESC/backdrop click
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeStockModal();
            closeEditModal();
        }
    });
    backdrop.addEventListener('click', () => {
        closeStockModal();
        closeEditModal();
    });
</script>
</html>

