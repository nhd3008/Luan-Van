<?php
require_once __DIR__ . '/../database/db_connect.php';

// Kiểm tra nếu có supplier_id trong URL
if (isset($_GET['id'])) {
    $supplier_id = $_GET['id'];

    // Xoá nhà cung cấp
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
    $stmt->bind_param("i", $supplier_id);

    if ($stmt->execute()) {
        echo "<script>alert('Nhà cung cấp đã được xóa!'); window.location.href='suppliers_list.php';</script>";
    } else {
        echo "Lỗi khi xóa nhà cung cấp: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Lỗi: Không có ID nhà cung cấp.";
}
?>
