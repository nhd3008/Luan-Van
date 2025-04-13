<?php
session_start();
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $external_link = !empty($_POST['external_link']) ? trim($_POST['external_link']) : null;

    // ✅ Xử lý ảnh upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = __DIR__ . '/../uploads/';
        $target_path = $target_dir . $image_name;
        $image_url = 'uploads/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            if ($title && $content && $image_url) {
                $stmt = $conn->prepare("INSERT INTO posts (title, content, image, external_link, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssss", $title, $content, $image_url, $external_link);
                if ($stmt->execute()) {
                    $message = "✅ Thêm bài viết thành công!";
                } else {
                    $message = "❌ Lỗi khi thêm bài viết: " . $stmt->error;
                }
            } else {
                $message = "⚠️ Vui lòng điền đầy đủ thông tin.";
            }
        } else {
            $message = "❌ Lỗi khi tải ảnh lên.";
        }
    } else {
        $message = "⚠️ Vui lòng chọn hình ảnh.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Blog - Quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">📝 Thêm bài viết Blog</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_blog.php" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Chọn hình ảnh</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
        </div>

        <div class="mb-3">
            <label for="external_link" class="form-label">Liên kết ngoài (nếu có)</label>
            <input type="url" class="form-control" id="external_link" name="external_link" placeholder="https://...">
        </div>

        <button type="submit" class="btn btn-success">Thêm bài viết</button>
        <a href="index.php" class="btn btn-secondary">🔙 Quay lại</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
