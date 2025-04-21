<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $created_at = $_POST['created_at']; // Lấy ngày hợp tác từ form
    
    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($phone) || empty($address) || empty($created_at)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin!");
    }

    // Thêm nhà cung cấp vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO suppliers (name, phone, address, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $address, $created_at); // Thêm ngày hợp tác vào câu lệnh

    if ($stmt->execute()) {
        echo "<script>alert('Thêm nhà cung cấp thành công!'); window.location.href='manage_supplier.php';</script>";
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
    <title>Thêm Nhà Cung Cấp</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <h3>📋 Thêm Nhà Cung Cấp</h3>
    
    <form method="POST">
        <label for="name">Tên Nhà Cung Cấp:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Số Điện Thoại:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="address">Địa Chỉ:</label>
        <input type="text" id="address" name="address" required>

        <label for="created_at">Ngày Hợp Tác:</label>
        <input type="date" id="created_at" name="created_at" required>

        <button type="submit" class="btn btn-success">Thêm Nhà Cung Cấp</button>
    </form>
</section>

</body>
</html>
