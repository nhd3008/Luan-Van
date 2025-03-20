<?php
session_start();
require_once 'database/db_connect.php';
include 'includes/header.php';

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy giỏ hàng từ database
$stmt = $conn->prepare("SELECT p.product_id, p.name, p.price, p.image_url, c.quantity FROM cart c 
                        JOIN products p ON c.product_id = p.product_id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Nếu giỏ hàng trống
if (empty($cart_items)) {
    echo "<p class='text-center text-danger'>Giỏ hàng của bạn đang trống. <a href='index.php'>Quay lại mua sắm</a></p>";
    include 'includes/footer.php';
    exit();
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Xử lý thanh toán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment_method'] ?? 'cod';
    
    // Thêm vào bảng orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method, order_status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("ids", $user_id, $total_price, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Thêm chi tiết đơn hàng vào bảng order_items
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    // Xóa giỏ hàng sau khi thanh toán
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    echo "<script>
        alert('Cảm ơn bạn đã mua hàng! Đơn hàng của bạn đã được đặt thành công.');
        window.location.href = 'order_history.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5 checkout-container">
    <h2 class="text-center mb-4">Xác nhận thanh toán</h2>
    <p class="text-center">Tổng giá trị đơn hàng: <strong class="text-danger"><?php echo number_format($total_price, 0, ',', '.'); ?> VND</strong></p>
    <form method="POST" class="text-center">
        <div class="mb-3">
            <label class="form-label">Chọn phương thức thanh toán:</label>
            <select name="payment_method" class="form-select w-50 mx-auto">
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="bank">Chuyển khoản ngân hàng</option>
                <option value="momo">Ví Momo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-50">Xác nhận thanh toán</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
