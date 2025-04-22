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

// Lấy danh sách đơn hàng của người dùng
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Lịch sử đơn hàng của bạn</h2>
    
    <?php if (empty($orders)): ?>
        <p class="text-center text-danger">Bạn chưa có đơn hàng nào. <a href="index.php">Tiếp tục mua sắm</a></p>
    <?php else: ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt hàng</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</td>
                        <td><?php echo ucfirst($order['payment_method']); ?></td>
                        <td><?php echo ucfirst($order['order_status']); ?></td>
                        <td><?php echo $order['created_at']; ?></td>
                        <td><a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary btn-sm">Xem</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-success">Tiếp tục mua sắm</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
