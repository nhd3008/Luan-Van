<!--<?php
/*
require_once  '../database/db_connect.php';
// Danh sách danh mục sản phẩm
$categories = [
    "Tăng cường miễn dịch",
    "Tốt cho tiêu hóa",
    "Hỗ trợ giảm cân",
    "Làm đẹp da"
];
// Hàm sinh mã sản phẩm 6 chữ số không trùng
function generateUniqueProductID($conn) {
    do {
        $product_id = rand(100000, 999999);
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    $stmt->close();
    return $product_id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']); // Lấy giá trị danh mục từ form
    $product_id = generateUniqueProductID($conn);


    // Xử lý upload ảnh (nếu có)
    $image_url = "uploads/default.jpg"; // Giá trị mặc định nếu không có ảnh
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/"; // Đường dẫn thư mục uploads
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa có
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file (chỉ cho phép JPG, PNG, JPEG, GIF)
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Lỗi: Chỉ chấp nhận file JPG, JPEG, PNG, GIF.");
        }

        // Kiểm tra kích thước file (giới hạn 5MB)
        if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
            die("Lỗi: File quá lớn. Giới hạn 5MB.");
        }

        // Di chuyển file ảnh vào thư mục uploads
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = "uploads/" . $image_name; // Lưu đường dẫn vào DB
        } else {
            die("Lỗi khi tải ảnh lên. Kiểm tra quyền thư mục uploads.");
        }
    }

    // Thêm sản phẩm vào database
    $stmt = $conn->prepare("INSERT INTO products (product_id, name, price, description, category, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsss", $product_id, $name, $price, $description, $category, $image_url);    

    if ($stmt->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='list_product.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Thêm Sản Phẩm</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="form">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>

        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" required>

        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>

        <label for="category">Danh mục:</label>
        <select id="category" name="category" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Thêm sản phẩm</button>
    </form>
</div>

</body>
</html> -->
