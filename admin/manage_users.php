<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h2>👤 Quản lý Người dùng</h2>
        <nav>
            <a href="index.php">🏠 Trang chủ Admin</a>
            <a href="manage_products.php">📦 Quản lý Sản phẩm</a>
            <a href="manage_orders.php">📜 Quản lý Đơn hàng</a>
            <a href="manage_users.php">👤 Quản lý Người dùng</a>
            <a href="../auth/logout.php">🚪 Đăng xuất</a>
        </nav>
    </header>
    
    <section>
        <h3>📋 Danh sách Người dùng</h3>
        
        <?php
        // Truy vấn danh sách người dùng
        $query = "SELECT user_id, username, email, role FROM users";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <?php if ($row['role'] === 'user'): ?>
                                <a class="btn btn-primary" href="update_role.php?id=<?= $row['user_id'] ?>&role=admin">Cấp quyền Admin</a>
                            <?php else: ?>
                                <a class="btn btn-warning" href="update_role.php?id=<?= $row['user_id'] ?>&role=user">Hạ quyền User</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>❌ Không có người dùng nào!</p>
        <?php endif; ?>
    </section>
</body>
</html>
