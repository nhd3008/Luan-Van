<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xử lý xóa blog nếu có yêu cầu
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id = $id");
    $_SESSION['success'] = "🗑️ Đã xóa bài viết thành công.";
    header("Location: manage_blogs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <a href="add_blog.php" class="btn btn-success">➕ Thêm bài viết mới</a>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>

    <?php
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
    if ($result->num_rows > 0):
    ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu đề</th>
                    <th>Hình ảnh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><img src="<?= $row['image'] ?>" alt="Ảnh blog" style="max-height:60px;"></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_blog.php?id=<?= $row['id'] ?>" class="btn btn-warning">✏️ Sửa</a>
                            <a href="manage_blogs.php?delete=<?= $row['id'] ?>" class="btn btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                               🗑️ Xóa
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error-message">❌ Chưa có bài viết nào.</p>
    <?php endif; ?>
</section>

</body>
</html>
<?php $conn->close(); ?>
