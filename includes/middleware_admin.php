<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu user chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Chuyển hướng về trang chủ
    exit();
}
?>
