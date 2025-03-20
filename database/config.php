<?php
$servername = "localhost"; // XAMPP chạy trên localhost
$username = "root"; // Mặc định XAMPP không có mật khẩu
$password = ""; // Để trống nếu bạn chưa đặt mật khẩu
$database = "fruit_store"; // Tên database bạn đã tạo

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
echo "Kết nối thành công!";
?>
