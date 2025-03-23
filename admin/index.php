<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h2>📊 Bảng điều khiển Admin</h2>
        <nav>
            <a href="index.php">🏠 Trang chủ Admin</a>
            <a href="manage_products.php">📦 Quản lý Sản phẩm</a>
            <a href="manage_orders.php">📜 Quản lý Đơn hàng</a>
            <a href="manage_users.php">👤 Quản lý Người dùng</a>
            <a href="../auth/logout.php">🚪 Đăng xuất</a>
        </nav>
    </header>
    
    <section>
        <h3>📊 Thống kê hệ thống</h3>
        <p>🔹 Số sản phẩm: <?php echo $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0]; ?></p>
        <p>🔹 Số đơn hàng: <?php echo $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0]; ?></p>
        <p>🔹 Số người dùng: <?php echo $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0]; ?></p>
    </section>
</body>
</html>
