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
        $stmt = $conn->prepare("UPDATE orders SET order_status = ?, approved_by_email = ? WHERE order_id = ?");
        $stmt->bind_param("ssi", $new_status, $_SESSION['email'], $order_id);
        
        if ($stmt->execute()) {
            if ($new_status === 'shipped') {
// Lấy tổng tiền và payment_method từ orders
$stmt2 = $conn->prepare("SELECT total_price, user_id, payment_method FROM orders WHERE order_id = ?");
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
        $user_id = $order['user_id'];
        $payment_method = $order['payment_method'];

        // Lấy thông tin khách hàng
        $user_stmt = $conn->prepare("SELECT full_name, phone_number, address FROM users WHERE user_id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user = $user_result->fetch_assoc();

        // Lấy danh sách sản phẩm
        // Lấy danh sách tên sản phẩm từ bảng products qua bảng order_items
        $product_stmt = $conn->prepare("SELECT p.name 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id 
        WHERE oi.order_id = ?");
$product_stmt->bind_param("i", $order_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

$product_names = [];
while ($prod = $product_result->fetch_assoc()) {
$product_names[] = $prod['name'];  // Thêm tên sản phẩm vào mảng
}
$product_list = implode(', ', $product_names); // Nối tên các sản phẩm thành chuỗi

// Ghi vào bảng revenue với đầy đủ thông tin
$insert = $conn->prepare("INSERT INTO revenue 
(order_id, total_amount, customer_name, customer_phone, customer_address, product_names, payment_method)
VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param(
"idsssss",
$order_id,
$total,
$user['full_name'],
$user['phone_number'],
$user['address'],
$product_list,  // Ghi vào cột product_names
$payment_method
);
$insert->execute();
}
}


                $_SESSION['success'] = "✅ Đơn hàng #$order_id đã được giao và ghi nhận doanh thu.";
            } elseif ($new_status === 'canceled') {
                $_SESSION['error'] = "⚠️ Đơn hàng #$order_id đã bị huỷ và hoàn tiền cho khách hàng.";
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
    <h3>📋 Danh sách Đơn hàng đang chờ xử lý</h3>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }

    // Lấy đơn hàng đang chờ xử lý (pending)
    $query_pending = "SELECT order_id, user_id, total_price, order_status 
                      FROM orders 
                      WHERE order_status = 'pending' 
                      ORDER BY order_id DESC";
    $result_pending = $conn->query($query_pending);
    ?>

    <?php if ($result_pending && $result_pending->num_rows > 0): ?>
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
            <?php while ($row = $result_pending->fetch_assoc()): ?>
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

<section>
    <h3>📋 Danh sách Đơn hàng đã xử lý</h3>

    <?php
    // Lấy đơn hàng đã xử lý (shipped, canceled)
    $query_processed = "SELECT order_id, user_id, total_price, order_status, approved_by_email
                        FROM orders 
                        WHERE order_status IN ('shipped', 'canceled') 
                        ORDER BY order_id DESC";
    $result_processed = $conn->query($query_processed);
    ?>

    <?php if ($result_processed && $result_processed->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>ID Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Người duyệt</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result_processed->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= number_format($row['total_price'], 0, ',', '.') ?> VND</td>
                    <td>
                        <span class="order-status <?= strtolower($row['order_status']) ?>">
                            <?= htmlspecialchars($row['order_status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['approved_by_email'] ?? 'Chưa xác định') ?></td>
                    <td>
                        <!-- Chỉ hiển thị thông tin mà không cho phép thay đổi trạng thái nữa -->
                        <span class="text-muted">Không thể thay đổi</span>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error-message">❌ Không có đơn hàng nào đã xử lý!</p>
    <?php endif; ?>
</section>

</body>
</html>
