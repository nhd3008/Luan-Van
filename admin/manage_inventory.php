<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📦 Quản lý Kho</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h3>📄 Danh sách sản phẩm trong kho</h3>
    <div style="display: flex; gap: 10px; align-items: center;">
        <form method="get" style="display: flex; align-items: center;">
            <label for="filter" style="margin-right: 8px; font-weight: 500;">Lọc:</label>
            <select name="filter" id="filter" class="filter-select" onchange="this.form.submit()">
                <option value="">📦 Tất cả</option>
                <option value="low" <?= isset($_GET['filter']) && $_GET['filter'] === 'low' ? 'selected' : '' ?>>⚠ Sắp hết (&lt; 5)</option>
                <option value="in_stock" <?= isset($_GET['filter']) && $_GET['filter'] === 'in_stock' ? 'selected' : '' ?>>✅ Còn hàng (&ge; 5)</option>
            </select>
        </form>
        <a class="btn btn-primary" href="add_new_product.php">➕ Thêm sản phẩm mới</a>
    </div>
</div>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">✅ Nhập kho thành công!</p>
    <?php endif; ?>

    <?php
    // Xử lý lọc theo lượng tồn kho
    $filter = $_GET['filter'] ?? '';
    $condition = '';

    if ($filter === 'low') {
        $condition = "WHERE stock_quantity < 5";
    } elseif ($filter === 'in_stock') {
        $condition = "WHERE stock_quantity >= 5";
    }

    $query = "SELECT product_id, name, stock_quantity, status 
              FROM products 
              $condition
              ORDER BY 
                CASE WHEN status = 'not_selling' THEN 0 ELSE 1 END,
                created_at DESC";
    $result = $conn->query($query);
    ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>SL tồn kho</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= (int)$row['stock_quantity'] ?></td>
                    <td>
                        <?php if ($row['status'] === 'selling'): ?>
                            <span class="badge-success">✔ Đã bán</span>
                        <?php else: ?>
                            <span class="badge-warning">🕒 Chưa xuất bản</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn btn-success" href="import_product.php?id=<?= $row['product_id'] ?>">➕ Nhập kho</a>

                        <?php if ($row['status'] === 'not_selling'): ?>
                            <a class="btn btn-info" href="publish_product.php?id=<?= $row['product_id'] ?>">📢 Xuất bản</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>✔ Đã bán</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error-message">❌ Không có sản phẩm nào!</p>
    <?php endif; ?>
</section>

</body>
</html>
