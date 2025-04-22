<?php
require_once '../database/db_connect.php';

// Danh sách danh mục
$categories = [
    "Tăng cường miễn dịch",
    "Tốt cho tiêu hóa",
    "Hỗ trợ giảm cân",
    "Làm đẹp da"
];

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Sản phẩm không tồn tại.");
}
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selling_price = floatval($_POST['selling_price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $image_url = $product['image_url'];

    // Xử lý upload ảnh nếu có
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Chỉ chấp nhận file JPG, JPEG, PNG, GIF.");
        }

        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            die("File quá lớn. Giới hạn 5MB.");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = "uploads/" . $image_name;
        } else {
            die("Tải ảnh thất bại.");
        }
    }

    // Cập nhật sản phẩm
    $stmt = $conn->prepare("UPDATE products SET selling_price = ?, description = ?, category = ?, image_url = ?, status = 'selling', visibility = 'public' WHERE product_id = ?");
    $stmt->bind_param("dsssi", $selling_price, $description, $category, $image_url, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Xuất bản thành công!'); window.location.href='manage_products.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xuất bản sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h2>🛒 Xuất bản sản phẩm: <?= htmlspecialchars($product['name']) ?></h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" disabled>

        <label for="selling_price">Giá bán:</label>
        <input type="number" step="0.01" name="selling_price" class="form-control" required>

        <label for="description">Mô tả:</label>
        <textarea name="description" class="form-control" required></textarea>

        <label for="category">Danh mục:</label>
        <select name="category" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>"><?= $cat ?></option>
            <?php endforeach; ?>
        </select>

        <label for="image">Hình ảnh (nếu muốn cập nhật):</label>
        <input type="file" name="image" class="form-control">

        <button type="submit" class="btn btn-success mt-3">✔ Xuất bản sản phẩm</button>
    </form>
</div>

</body>
</html>
