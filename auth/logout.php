<?php
session_start();

// Xóa tất cả dữ liệu session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập với thông báo
echo "<script>alert('Đăng xuất thành công!'); window.location.href='login.php';</script>";
exit();
