<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
    <h2>📦 Quản lý Sản phẩm</h2>
    <nav>
        <a href="index.php">🏠 Trang chủ Admin</a>
        <a href="manage_products.php">📦 Quản lý Sản phẩm</a>
        <a href="manage_orders.php">📜 Quản lý Đơn hàng</a>
        <a href="manage_users.php">👤 Quản lý Người dùng</a>
        <a href="../auth/logout.php">🚪 Đăng xuất</a>
    </nav>
</header>

<section>
    <h3>📋 Danh sách Sản phẩm</h3>

    <a class="btn btn-success" href="../api/add_product.php">➕ Thêm sản phẩm</a>

    <?php
    // Truy vấn danh sách sản phẩm
    $query = "SELECT product_id, name, price FROM products";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= number_format($row['price'], 0, ',', '.') ?> VND</td>
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
