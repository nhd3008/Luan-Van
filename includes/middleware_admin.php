<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hàm kiểm tra vai trò người dùng
function checkRole($requiredRole) {
    // Kiểm tra nếu người dùng chưa đăng nhập hoặc vai trò không đúng
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: ../login.php");  // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập hoặc không có quyền
        exit();
    }
}

// Middleware kiểm tra quyền truy cập cho các vai trò khác nhau
function checkPermissions($role) {
    // Kiểm tra nếu người dùng là admin (admin có quyền truy cập tất cả)
    if ($_SESSION['role'] === 'admin') {
        return;  // Nếu là admin thì không cần kiểm tra thêm, admin có quyền truy cập tất cả các trang
    }

    // Kiểm tra quyền truy cập cho các vai trò khác
    switch ($role) {
        case 'manager':
            checkRole('manager');
            break;
        case 'customer':
            checkRole('user');  // Customer trong hệ thống có thể là user
            break;
        default:
            header("Location: ../error.php");  // Nếu vai trò không hợp lệ, chuyển về trang lỗi
            exit();
    }
}
?>
