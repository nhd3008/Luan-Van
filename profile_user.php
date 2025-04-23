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

// Kiểm tra sự tồn tại của các chỉ mục trước khi hiển thị
$email = isset($user['email']) ? htmlspecialchars($user['email']) : 'Không có dữ liệu';
$full_name = isset($user['full_name']) ? htmlspecialchars($user['full_name']) : 'Không có dữ liệu';
$phone_number = isset($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'Không có dữ liệu';
$address = isset($user['address']) ? htmlspecialchars($user['address']) : 'Không có dữ liệu';
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

        <!-- Hiển thị thông báo nếu có -->
        <?php if (isset($message)) echo "<p class='alert alert-success'>$message</p>"; ?>

        <div class="mb-3">
            <label for="username" class="form-label">Tên người dùng:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" value="<?php echo $email; ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Họ tên:</label>
            <input type="text" class="form-control" value="<?php echo $full_name; ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại:</label>
            <input type="text" class="form-control" value="<?php echo $phone_number; ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ:</label>
            <input type="text" class="form-control" value="<?php echo $address; ?>" disabled>
        </div>

        <!-- Chỉ hiển thị thông tin, không cho phép nhập mật khẩu mới -->
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control" value="******" disabled>
        </div>

        <!-- Nút chuyển tới trang cập nhật thông tin -->
        <div class="text-center mt-4">
            <a href="update_profile.php" class="btn btn-primary">Cập nhật thông tin</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
