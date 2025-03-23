<?php
require_once __DIR__ . '/../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']); // Nhận danh mục từ form
    $image_url = ""; // Biến để lưu đường dẫn ảnh

    // Danh mục hợp lệ
    $allowed_categories = ["Tăng cường miễn dịch", "Tốt cho tiêu hóa", "Hỗ trợ giảm cân", "Làm đẹp da"];
    if (!in_array($category, $allowed_categories)) {
        die("Lỗi: Danh mục không hợp lệ.");
    }

    // Kiểm tra nếu thư mục uploads chưa tồn tại thì tạo mới
    $target_dir = __DIR__ . '/../uploads/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Kiểm tra xem người dùng có tải ảnh lên không
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
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
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            die("❌ Lỗi khi tải ảnh!<br>
                 🔹 Chi tiết lỗi: " . $_FILES["image"]["error"] . "<br>
                 🔹 Đường dẫn tạm thời: " . $_FILES["image"]["tmp_name"] . "<br>
                 🔹 Đường dẫn lưu: " . $target_file . "<br>
                 🔹 Kiểm tra quyền ghi thư mục `uploads/`.");
        } else {
            $image_url = "uploads/" . $image_name;
            echo "✅ Ảnh đã được tải lên thành công: " . $image_url;
        }
        
    }

    // Lưu sản phẩm vào database (thêm cột `category`)
    $sql = "INSERT INTO products (name, price, description, category, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsss", $name, $price, $description, $category, $image_url);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='../list_product.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>