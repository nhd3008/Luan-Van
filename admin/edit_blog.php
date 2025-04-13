<?php
require_once '../database/db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: ID bài viết không hợp lệ.");
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy bài viết.");
}

$blog = $result->fetch_assoc();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $external_link = !empty($_POST['external_link']) ? trim($_POST['external_link']) : null;
    $image_url = $blog['image']; // giữ ảnh cũ nếu không chọn ảnh mới

    // Nếu có upload ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_types)) {
            $message = "❌ File ảnh không hợp lệ (chỉ chấp nhận jpg, jpeg, png, gif).";
        } elseif ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            $message = "❌ File ảnh quá lớn (tối đa 5MB).";
        } else {
            // Xóa ảnh cũ nếu tồn tại
            if (!empty($blog['image']) && file_exists("../" . $blog['image'])) {
                unlink("../" . $blog['image']);
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = "uploads/" . $image_name;
            } else {
                $message = "❌ Lỗi khi tải ảnh lên.";
            }
        }
    }

    // Nếu không có lỗi về ảnh
    if (empty($message)) {
        $sql = "UPDATE posts SET title = ?, content = ?, image = ?, external_link = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $content, $image_url, $external_link, $id);

        if ($stmt->execute()) {
            $message = "✅ Cập nhật bài viết thành công!";
            // Load lại dữ liệu để hiển thị cập nhật
            $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $blog = $result->fetch_assoc();
        } else {
            $message = "❌ Lỗi: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Bài Viết</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-primary mb-4">🛠️ Sửa Bài Viết</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề:</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung:</label>
            <textarea id="content" name="content" class="form-control" rows="6" required><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại:</label><br>
            <?php if (!empty($blog['image'])): ?>
                <img src="../<?= $blog['image'] ?>" width="150" alt="Ảnh bài viết"><br>
            <?php else: ?>
                <span class="text-muted">Không có ảnh</span><br>
            <?php endif; ?>
            <label for="image" class="form-label mt-2">Chọn ảnh mới (nếu muốn thay):</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label for="external_link" class="form-label">Liên kết ngoài (nếu có):</label>
            <input type="url" class="form-control" id="external_link" name="external_link"
                   value="<?= htmlspecialchars($blog['external_link']) ?>" placeholder="https://...">
        </div>

        <button type="submit" class="btn btn-primary">💾 Cập Nhật</button>
        <a href="manage_blogs.php" class="btn btn-secondary">🔙 Quay lại</a>
    </form>
</div>
</body>
</html>
