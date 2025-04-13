<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <h3>📋 Danh sách Sản phẩm</h3>
    <?php
    $query = "SELECT product_id, name, selling_price, unit, stock_quantity FROM products";
    $result = $conn->query($query);

    $unit_labels = [
        'kg' => 'Theo kg (0.5kg)',
        'trái' => 'Theo trái'
    ];

    if ($result && $result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Đơn vị</th>
                    <th>Tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= number_format($row['selling_price'], 0, ',', '.') ?> VND</td>
                    <td><?= $unit_labels[$row['unit']] ?? 'Không xác định' ?></td>
                    <td><?= (int)$row['stock_quantity'] ?></td>
                    <td>
                        <a class="btn btn-primary" href="../api/edit_product.php?id=<?= $row['product_id'] ?>">✏️ Sửa</a>
                        <a class="btn btn-danger" href="../api/delete_product.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">🗑️ Xóa</a>
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
