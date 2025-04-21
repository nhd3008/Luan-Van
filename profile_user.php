<?php
session_start();
require_once __DIR__ . '/database/db_connect.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    exit();
}

$user_id = $_SESSION['user_id']; // Lấy user_id từ session

// Lấy thông tin người dùng từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Lấy thông tin người dùng

// Kiểm tra nếu không có dữ liệu trả về (lỗi trong việc truy vấn dữ liệu)
if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}

// Cập nhật thông tin người dùng nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';  // Dùng ?? để tránh lỗi nếu không có dữ liệu
    $email = $_POST['email'] ?? '';        
    $full_name = $_POST['full_name'] ?? ''; 
    $phone_number = $_POST['phone_number'] ?? ''; 
    $address = $_POST['address'] ?? '';    
    $password = $_POST['password'] ?? '';  // Mật khẩu mới

    // Kiểm tra nếu mật khẩu mới được nhập, thì mã hóa và cập nhật
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ?, address = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $username, $email, $full_name, $phone_number, $address, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $username, $email, $full_name, $phone_number, $address, $user_id);
    }

    if ($stmt->execute()) {
        $message = "Cập nhật thông tin thành công!";
    } else {
        $message = "Có lỗi xảy ra. Vui lòng thử lại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Người Dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4 text-success fw-bold">👤 Hồ Sơ Người Dùng</h1>

        <?php if (isset($message)) echo "<p class='alert alert-success'>$message</p>"; ?>

        <form method="POST" class="form-container">
            <div class="mb-3">
                <label for="username" class="form-label">Tên người dùng:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="full_name" class="form-label">Họ tên:</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo isset($user['full_name']) ? htmlspecialchars($user['full_name']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Số điện thoại:</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo isset($user['phone_number']) ? htmlspecialchars($user['phone_number']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ:</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới (nếu thay đổi):</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-success w-100">Cập nhật thông tin</button>
        </form>

        <div class="text-center mt-4">
            <a href="change_password.php" class="btn btn-link">Thay đổi mật khẩu</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
