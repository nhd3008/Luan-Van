<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
    <h2>📜 Quản lý Đơn hàng</h2>
    <nav>
        <a href="index.php">🏠 Trang chủ Admin</a>
        <a href="manage_products.php">📦 Quản lý Sản phẩm</a>
        <a href="manage_orders.php">📜 Quản lý Đơn hàng</a>
        <a href="manage_users.php">👤 Quản lý Người dùng</a>
        <a href="../auth/logout.php">🚪 Đăng xuất</a>
    </nav>
</header>

<section>
    <h3>📋 Danh sách Đơn hàng</h3>

    <?php
    // Truy vấn đơn hàng với user_id và order_status
    $query = "SELECT order_id, user_id, total_price, order_status FROM orders"; 
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= number_format($row['total_price'], 0, ',', '.') ?> VND</td>
                    <td>
                        <span class="order-status <?= strtolower($row['order_status']) ?>">
                            <?= htmlspecialchars($row['order_status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['order_status'] !== 'shipped'): ?>
                            <a class="btn btn-primary" href="update_order.php?id=<?= $row['order_id'] ?>&status=shipped">Giao hàng</a>
                        <?php endif; ?>
                        <?php if ($row['order_status'] !== 'canceled'): ?>
                            <a class="btn btn-warning" href="update_order.php?id=<?= $row['order_id'] ?>&status=canceled">Hủy</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error-message">❌ Không có đơn hàng nào!</p>
    <?php endif; ?>
</section>

</body>
</html>
