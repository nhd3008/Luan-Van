<?php
session_start();
require_once __DIR__ . '/../database/db_connect.php';

// Tạo CSRF Token nếu chưa có
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Tạo ID người dùng duy nhất
function generateUniqueUserID($conn) {
    do {
        $user_id = rand(100000, 999999);
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    $stmt->close();
    return $user_id;
}

// Xử lý thêm người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? ''; // Vai trò của người dùng
    $csrf_token = $_POST['csrf_token'];

    // Kiểm tra CSRF Token
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Lỗi bảo mật! Vui lòng thử lại.");
    }

    // Kiểm tra dữ liệu hợp lệ
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin!");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Lỗi: Email không hợp lệ.");
    }
    if (strlen($password) < 6) {
        die("Lỗi: Mật khẩu phải có ít nhất 6 ký tự.");
    }

    // Kiểm tra email đã tồn tại
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("Lỗi: Email đã được đăng ký.");
    }

    // Tạo ID người dùng duy nhất
    $user_id = generateUniqueUserID($conn);

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (user_id, username, password, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $username, $hashed_password, $email, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm người dùng thành công!'); window.location.href='manage_users.php';</script>";
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
    <title>Thêm người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head> 
<body class="container mt-5">

<div class="auth-container">
    <h2 class="text-center">Thêm người dùng</h2>
    <form method="POST" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Tên người dùng:</label>
        <input type="text" name="username" class="form-control" required>

        <label>Email đăng nhập:</label>
        <input type="email" name="email" class="form-control" required>

        <label>Mật khẩu:</label>
        <input type="password" name="password" class="form-control" required>

        <label>Vai trò:</label>
        <select name="role" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="customer">Customer</option>
        </select>

        <button type="submit" class="btn btn-primary mt-3 w-100">Thêm người dùng</button>
    </form>
    <p class="text-center mt-3">Trở về danh sách người dùng? <a href="manage_users.php">Xem danh sách</a></p>
</div>

</body>
</html>
