<?php
session_start();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/database/db_connect.php';

// Lấy bài viết + liên kết ngoài (nếu có)
$stmt = $conn->prepare("SELECT id, title, image, external_link, LEFT(content, 150) AS excerpt FROM posts ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$blogs = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Blog Dinh Dưỡng - Fruit For Health</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap + Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container my-5">
    <h1 class="text-center text-success fw-bold mb-4">📚 Blog Dinh Dưỡng</h1>

    <?php if (count($blogs) > 0): ?>
        <div class="row">
            <?php foreach ($blogs as $blog): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($blog['title']) ?>">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title text-success fw-bold"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($blog['excerpt']) ?>...</p>
                            <?php if (!empty($blog['external_link'])): ?>
                                <a href="<?= htmlspecialchars($blog['external_link']) ?>" class="btn btn-outline-primary btn-sm mt-auto" target="_blank" rel="noopener noreferrer">
                                    🔗 Đọc ngoài
                                </a>
                            <?php else: ?>
                                <a href="blog_detail.php?id=<?= $blog['id'] ?>" class="btn btn-outline-primary btn-sm mt-auto">Đọc thêm</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-danger">⚠️ Hiện chưa có bài viết blog nào.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
