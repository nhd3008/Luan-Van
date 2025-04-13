<?php
require_once '../database/db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: ID sản phẩm không hợp lệ.");
}

$id = $_GET['id'];

// Danh sách danh mục tĩnh
$categories = [
    "Tăng cường miễn dịch",
    "Tốt cho tiêu hóa",
    "Hỗ trợ giảm cân",
    "Làm đẹp da"
];

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Lỗi: Không tìm thấy sản phẩm.");
}

$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $selling_price = floatval($_POST['selling_price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);
    $image_url = $product['image_url'];

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_types)) {
            die("Lỗi: Chỉ chấp nhận file JPG, JPEG, PNG, GIF.");
        }

        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            die("Lỗi: File quá lớn. Giới hạn 5MB.");
        }

        if (!empty($product['image_url']) && file_exists("../" . $product['image_url'])) {
            unlink("../" . $product['image_url']);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = "uploads/" . $image_name;
        } else {
            die("Lỗi khi tải ảnh lên.");
        }
    }

    $sql = "UPDATE products SET name = ?, selling_price = ?, description = ?, image_url = ?, category = ?, unit = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssssi", $name, $selling_price, $description, $image_url, $category, $unit, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='../admin/manage_products.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2 class="mb-4">Sửa Sản Phẩm</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product['product_id']; ?>">

    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required>
    </div>

    <div class="mb-3">
        <label for="selling_price" class="form-label">Giá:</label>
        <input type="number" id="selling_price" name="selling_price" class="form-control" value="<?= $product['selling_price']; ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả:</label>
        <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($product['description']); ?></textarea>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">Danh mục:</label>
        <select id="category" name="category" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= ($product['category'] == $cat) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="unit" class="form-label">Đơn vị bán:</label>
        <select id="unit" name="unit" class="form-select" required>
            <option value="kg" <?= ($product['unit'] == 'kg') ? 'selected' : '' ?>>Kg</option>
            <option value="trái" <?= ($product['unit'] == 'trái') ? 'selected' : '' ?>>Trái</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh hiện tại:</label><br>
        <?php if (!empty($product['image_url'])): ?>
            <img src="../<?= $product['image_url']; ?>" width="150"><br>
        <?php else: ?>
            <span class="text-muted">Không có ảnh</span><br>
        <?php endif; ?>
        <label for="image" class="form-label mt-2">Chọn ảnh mới (nếu có):</label>
        <input type="file" id="image" name="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Cập Nhật</button>
</form>

</body>
</html>
