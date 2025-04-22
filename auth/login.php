<?php
session_start();
require_once __DIR__ . '/../database/db_connect.php';

// Tạo CSRF Token nếu chưa có
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $csrf_token = $_POST['csrf_token'];

    // Kiểm tra CSRF Token
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Lỗi bảo mật! Vui lòng thử lại.");
    }

    // Chuẩn bị và thực hiện truy vấn để lấy thông tin người dùng
    $stmt = $conn->prepare("SELECT user_id, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID để bảo mật
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role']; // Lưu vai trò người dùng vào session
            $_SESSION['email'] = $user['email'];

            // Chuyển hướng theo quyền
            switch ($user['role']) {
                case 'admin':
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href='../admin/index.php';</script>";
                    break;
                case 'manager':
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href='../admin/manage_inventory.php';</script>";
                    break;
                case 'user':
                    echo "<script>alert('Đăng nhập thành công!'); window.location.href='../index.php';</script>";
                    break;
                default:
                    // Vai trò không xác định
                    echo "<script>alert('Vai trò không hợp lệ!'); window.location.href='../index.php';</script>";
                    break;
            }
        } else {
            echo "<script>alert('Mật khẩu không đúng!');</script>";
        }
    } else {
        echo "<script>alert('Email không tồn tại!');</script>";
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
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="container mt-5">

<div class="auth-container">
    <h2 class="text-center">Đăng nhập</h2>
    <form method="POST" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
        <label>Mật khẩu:</label>
        <input type="password" name="password" class="form-control" required>
        <button type="submit" class="btn btn-success mt-3 w-100">Đăng nhập</button>
    </form>
    <p class="text-center mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>

</body>
</html>
