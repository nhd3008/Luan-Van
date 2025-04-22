<?php
session_start();
require_once 'database/db_connect.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy giỏ hàng từ DB
$stmt = $conn->prepare("SELECT p.product_id, p.name, p.selling_price, p.image_url, p.unit, c.quantity FROM cart c 
                        JOIN products p ON c.product_id = p.product_id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    echo "<p class='text-center text-danger'>Giỏ hàng của bạn đang trống. <a href='index.php'>Quay lại mua sắm</a></p>";
    include 'includes/footer.php';
    exit();
}

// Tính tổng tiền
$total_price = 0;

foreach ($cart_items as $item) {
    $unit = $item['unit'];
    $quantity = $item['quantity'];
    $subtotal = 0;

    echo "<div class='text-center'>";
    echo "<strong>{$item['name']}</strong><br>";

    if ($unit == 'kg') {
        $weight = $quantity * 0.5; // mỗi đơn vị là 0.5kg
        $subtotal = $item['selling_price'] * $weight;
        echo number_format($subtotal, 0, ',', '.') . " VND<br><small>({$weight} kg)</small><br><hr>";
    } else {
        $subtotal = $item['selling_price'] * $quantity;
        echo number_format($subtotal, 0, ',', '.') . " VND<br><small>({$quantity} trái)</small><br><hr>";
    }

    $total_price += $subtotal;
}

// Kiểm tra tồn kho
$errors = [];
foreach ($cart_items as $item) {
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $item['product_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['stock_quantity'] < $item['quantity']) {
        $errors[] = "Sản phẩm '{$item['name']}' không đủ hàng trong kho (còn lại: {$product['stock_quantity']}).";
    }
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='alert alert-danger text-center'>❌ $error</div>";
    }
    include 'includes/footer.php';
    exit;
}

// Xử lý thanh toán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

      // Tạo đơn hàng
      $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method, order_status, created_at) 
      VALUES (?, ?, ?, 'pending', NOW())");
$stmt->bind_param("ids", $user_id, $total_price, $payment_method);
$stmt->execute();
$order_id = $stmt->insert_id;

// Lưu sản phẩm trong đơn
foreach ($cart_items as $item) {
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
          VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['selling_price']);
$stmt->execute();
}

// Lưu thông tin giao hàng
$stmt = $conn->prepare("INSERT INTO order_shipping (order_id, full_name, phone, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $order_id, $full_name, $phone, $address);
$stmt->execute();

// Xoá giỏ hàng
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Cập nhật tồn kho
// Cập nhật tồn kho
foreach ($cart_items as $item) {
    // Lấy đơn vị từ bảng `products` theo `product_id`
    $stmt_unit = $conn->prepare("SELECT unit FROM products WHERE product_id = ?");
    $stmt_unit->bind_param("i", $item['product_id']);
    $stmt_unit->execute();
    $result_unit = $stmt_unit->get_result();
    $unit = $result_unit->fetch_assoc()['unit'];

    // Kiểm tra đơn vị và cập nhật tồn kho
    if ($unit == 'trái') {
        // Trừ đúng số lượng khi unit là trái
        $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    } elseif ($unit == 'kg') {
        // Trừ một nửa số lượng khi unit là kg
        $half_quantity = $item['quantity'] / 2;
        $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $stmt->bind_param("di", $half_quantity, $item['product_id']);
    }
    
    // Thực thi câu lệnh cập nhật tồn kho
    $stmt->execute();
}


unset($_SESSION['cart']);

 // Nếu chọn PayPal thì redirect sang trang tạo thanh toán PayPal
 if ($payment_method === 'paypal') {
    header("Location: create_paypal_payment.php?order_id=$order_id&amount=$total_price");
    exit();
}

if ($payment_method === 'vnpay') {
    // Chuyển hướng sang trang tạo request VNPAY
    header("Location: create_vnpay_payment.php?order_id=$order_id&amount=$total_price");
    exit();
}

// Ngược lại: xử lý thông thường (COD, bank, momo,...)
echo "<script>
alert('🎉 Đơn hàng của bạn đã được đặt thành công!');
window.location.href = 'order_history.php';
</script>";
exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5 checkout-container">
    <h2 class="text-center mb-4">Xác nhận thanh toán</h2>
    <p class="text-center">Tổng đơn hàng: <strong class="text-danger"><?php echo number_format($total_price, 0, ',', '.'); ?> VND</strong></p>

    <form method="POST" class="text-center">
        <div class="mb-3 w-50 mx-auto">
            <label class="form-label">Họ tên người nhận</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3 w-50 mx-auto">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3 w-50 mx-auto">
            <label class="form-label">Địa chỉ nhận hàng</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Phương thức thanh toán:</label>
            <select name="payment_method" class="form-select w-50 mx-auto">
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="bank">Chuyển khoản ngân hàng</option>
                <option value="momo">Ví Momo</option>
                <option value="vnpay">Thanh toán VNPay</option>
                <option value="paypal">Thanh Toán paypal</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-50">Xác nhận thanh toán</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
