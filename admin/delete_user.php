<?php
// Kết nối với cơ sở dữ liệu
require_once __DIR__ . '/../database/db_connect.php';

// Kiểm tra nếu có user_id trong URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Chuẩn bị câu lệnh xóa người dùng
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    // Thực thi câu lệnh xóa
    if ($stmt->execute()) {
        // Chuyển hướng về trang danh sách người dùng với thông báo thành công
        echo "<script>alert('Người dùng đã được xóa!'); window.location.href='manage_users.php';</script>";
    } else {
        // Nếu có lỗi, hiển thị lỗi
        echo "Lỗi khi xóa người dùng: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    // Nếu không có user_id trong URL, thông báo lỗi
    echo "Lỗi: Không có ID người dùng.";
}
?>
