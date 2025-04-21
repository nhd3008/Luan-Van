<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Lấy ID nhà cung cấp từ URL
if (isset($_GET['id'])) {
    $supplier_id = $_GET['id'];

    // Lấy thông tin nhà cung cấp từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
    $stmt->bind_param("i", $supplier_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Nhà cung cấp không tồn tại.");
    }
} else {
    die("Lỗi: Không có ID nhà cung cấp.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin mới từ form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Cập nhật thông tin nhà cung cấp
    $stmt = $conn->prepare("UPDATE suppliers SET name = ?, phone = ?, address = ? WHERE supplier_id = ?");
    $stmt->bind_param("sssi", $name, $phone, $address, $supplier_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật nhà cung cấp thành công!'); window.location.href='suppliers_list.php';</script>";
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
    <title>Cập Nhật Nhà Cung Cấp</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <h3>📋 Cập Nhật Nhà Cung Cấp</h3>
    
    <form method="POST">
        <label for="name">Tên Nhà Cung Cấp:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

        <label for="phone">Số Điện Thoại:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required>

        <label for="address">Địa Chỉ:</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($row['address']) ?>" required>

        <button type="submit" class="btn btn-success">Cập Nhật Nhà Cung Cấp</button>
    </form>
</section>

</body>
</html>
