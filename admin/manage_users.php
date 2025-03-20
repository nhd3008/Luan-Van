<?php
session_start();
include __DIR__ . '/../database/db_connect.php';
include __DIR__ . '/../includes/middleware_admin.php';
include __DIR__ . '/../includes/header.php';

// Kiểm tra lỗi kết nối database
if (!$conn) {
    die("❌ Kết nối database thất bại!");
}

// Lấy danh sách user từ database
$query = "SELECT user_id, username, role FROM users";
$result = $conn->query($query);
if (!$result) {
    die("❌ Lỗi truy vấn database: " . $conn->error);
}
?>

<h2>Quản lý tài khoản</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['user_id'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['role'] ?></td>
        <td>
            <?php if ($row['role'] === 'user'): ?>
                <a href="update_role.php?id=<?= $row['user_id'] ?>&role=admin">Cấp quyền Admin</a>
            <?php else: ?>
                <a href="update_role.php?id=<?= $row['user_id'] ?>&role=user">Hạ quyền User</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
