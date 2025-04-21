<?php
require_once __DIR__ . '/../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_GET['id'];

    // Lấy thông tin người dùng hiện tại
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $password = $_POST['password']; // Mật khẩu mới

    // Kiểm tra nếu người dùng có nhập mật khẩu mới
    if (!empty($password)) {
        // Mã hóa mật khẩu mới
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        // Cập nhật thông tin người dùng và mật khẩu
        $query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ?, address = ?, role = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $username, $email, $full_name, $phone_number, $address, $role, $password_hashed, $user_id);
    } else {
        // Nếu không thay đổi mật khẩu, chỉ cập nhật các thông tin khác
        $query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ?, address = ?, role = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $username, $email, $full_name, $phone_number, $address, $role, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: manage_users.php"); // Chuyển hướng về trang quản lý người dùng
    } else {
        echo "Lỗi khi cập nhật thông tin người dùng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Người dùng</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        /* Tạo kiểu cho phần form chỉnh sửa người dùng */
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

form h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

label {
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

input[type="text"], input[type="email"], input[type="password"], select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
    border-color: #5cb85c;
    outline: none;
}

button {
    background-color: #5cb85c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #4cae4c;
}

/* Cải thiện giao diện phần thông báo lỗi */
.error-message {
    color: red;
    font-size: 16px;
    margin-top: 20px;
    text-align: center;
}

/* Tạo kiểu cho form khi hiển thị */
input[type="text"], input[type="email"], input[type="password"], select {
    display: block;
    width: 100%;
    height: 40px;
    margin: 8px 0;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

select {
    background-color: #fff;
}

/* Tạo kiểu cho các nút chỉnh sửa */
a.btn.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 5px 15px;
    text-decoration: none;
    border-radius: 4px;
    margin: 5px;
}

a.btn.btn-warning {
    background-color: #ffc107;
    color: white;
    padding: 5px 15px;
    text-decoration: none;
    border-radius: 4px;
    margin: 5px;
}

a.btn.btn-primary:hover, a.btn.btn-warning:hover {
    opacity: 0.8;
}
</style>
</head>
<body>
<?php include_once __DIR__ . '/nav_admin.php'; ?>
<h3>Chỉnh sửa Người dùng</h3>

<form action="update_user.php" method="POST">
    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
    
    <label for="username">Tên đăng nhập:</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="full_name">Họ và tên:</label>
    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

    <label for="phone_number">Số điện thoại:</label>
    <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>

    <label for="address">Địa chỉ:</label>
    <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

    <label for="role">Vai trò:</label>
    <select id="role" name="role" required>
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="inventory_manager" <?= $user['role'] === 'inventory_manager' ? 'selected' : '' ?>>Quản lý kho</option>
        <option value="order_manager" <?= $user['role'] === 'order_manager' ? 'selected' : '' ?>>Quản lý đơn hàng</option>
        <option value="user_manager" <?= $user['role'] === 'user_manager' ? 'selected' : '' ?>>Quản lý người dùng</option>
    </select>

    <label for="password">Mật khẩu mới (nếu muốn thay đổi):</label>
    <input type="password" id="password" name="password">

    <button type="submit">Cập nhật</button>
</form>

</body>
</html>
