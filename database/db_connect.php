<?php
// Thông tin kết nối
$host = "localhost";      // Máy chủ MySQL
$username = "root";       // Tên đăng nhập mặc định của XAMPP
$password = "";           // Mật khẩu mặc định (để trống)
$database = "fruit_store"; // Tên database bạn đã tạo

// Tạo kết nối
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập encoding tiếng Việt
$conn->set_charset("utf8mb4");

// Hoặc dùng PDO (nếu thích)
/*
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
*/
?>