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
<?php include_once __DIR__ . '/nav_admin.php'; ?>
    
<section>
    <h3>📋 Danh sách Người dùng</h3>
    
    <!-- Nút hiển thị form thêm người dùng và chuyển hướng đến trang add_user.php -->
    <a href="add_user.php" class="btn btn-success">Thêm Người Dùng</a>
    
    <h3>Danh sách Người Dùng</h3>
    
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
                        <!-- Nút Sửa -->
                        <a class="btn btn-primary" href="update_user.php?id=<?= $row['user_id'] ?>">Sửa</a>

                        <!-- Nút Xoá -->
                        <a class="btn btn-danger" href="delete_user.php?id=<?= $row['user_id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xoá</a>
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
