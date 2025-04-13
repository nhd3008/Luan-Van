<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = intval($_GET['id']);
    $new_status = $_GET['status'];

    if (in_array($new_status, ['shipped', 'canceled'])) {
        // Cập nhật trạng thái đơn hàng
        $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        
        if ($stmt->execute()) {
            if ($new_status === 'shipped') {
                // Lấy tổng tiền để ghi doanh thu
                $stmt2 = $conn->prepare("SELECT total_price FROM orders WHERE order_id = ?");
                $stmt2->bind_param("i", $order_id);
                $stmt2->execute();
                $result = $stmt2->get_result();
                $order = $result->fetch_assoc();

                if ($order) {
                    // Kiểm tra đã ghi doanh thu chưa
                    $check = $conn->prepare("SELECT * FROM revenue WHERE order_id = ?");
                    $check->bind_param("i", $order_id);
                    $check->execute();
                    $rev_result = $check->get_result();

                    if ($rev_result->num_rows === 0) {
                        $total = $order['total_price'];

                        // Ghi nhận doanh thu
                        $insert = $conn->prepare("INSERT INTO revenue (order_id, total_amount) VALUES (?, ?)");
                        $insert->bind_param("id", $order_id, $total);
                        $insert->execute();
                    }
                }

                $_SESSION['success'] = "✅ Đơn hàng #$order_id đã được giao và ghi nhận doanh thu.";
            } elseif ($new_status === 'canceled') {
                $_SESSION['error'] = "⚠️ Đơn hàng #$order_id đã bị huỷ.";
            }
        } else {
            $_SESSION['error'] = "❌ Lỗi khi cập nhật đơn hàng: " . $stmt->error;
        }

        header("Location: manage_orders.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <h3>📋 Danh sách Đơn hàng</h3>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }

    // Lấy đơn hàng chưa giao và chưa huỷ
    $query = "SELECT order_id, user_id, total_price, order_status 
              FROM orders 
              WHERE order_status NOT IN ('shipped', 'canceled') 
              ORDER BY order_id DESC";
    $result = $conn->query($query);
    ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>ID Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= number_format($row['total_price'], 0, ',', '.') ?> VND</td>
                    <td>
                        <span class="order-status <?= strtolower($row['order_status']) ?>">
                            <?= htmlspecialchars($row['order_status']) ?>
                        </span>
                    </td>
                    <td>
                        <a class="btn btn-primary"
                           href="?id=<?= $row['order_id'] ?>&status=shipped"
                           onclick="return confirm('Xác nhận giao đơn hàng #<?= $row['order_id'] ?>?')">
                            Giao hàng
                        </a>
                        <a class="btn btn-warning"
                           href="?id=<?= $row['order_id'] ?>&status=canceled"
                           onclick="return confirm('Bạn chắc chắn muốn huỷ đơn hàng #<?= $row['order_id'] ?>?')">
                            Huỷ
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error-message">❌ Không có đơn hàng nào cần xử lý!</p>
    <?php endif; ?>
</section>

</body>
</html>
