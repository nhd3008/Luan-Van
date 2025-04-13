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
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3>📄 Danh sách sản phẩm trong kho</h3>
        <a class="btn btn-primary" href="add_new_product.php">➕ Thêm sản phẩm mới</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">✅ Nhập kho thành công!</p>
    <?php endif; ?>

    <?php
    $query = "SELECT product_id, name, stock_quantity, status FROM products ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0): ?>
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
