<?php
session_start();
require_once 'database/db_connect.php';
include 'includes/header.php';

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không có order_id
if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Kiểm tra xem đơn hàng có thuộc về người dùng không
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    echo "<p class='text-center text-danger'>Đơn hàng không tồn tại hoặc bạn không có quyền truy cập.</p>";
    include 'includes/footer.php';
    exit();
}
$order = $order_result->fetch_assoc();

// Lấy chi tiết đơn hàng từ bảng order_items + thông tin sản phẩm
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image_url, p.unit 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.product_id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);

// Xử lý đánh giá sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
    $stmt->execute();

    echo "<script>alert('Đánh giá của bạn đã được ghi nhận!'); window.location.href = 'order_history.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Chi tiết đơn hàng #<?php echo $order_id; ?></h2>
    <p class="text-center">Tổng giá trị đơn hàng: 
        <strong class="text-danger"><?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND</strong>
    </p>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá bán</th>
                <th>Tổng</th>
                <th>Đánh giá</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($order_items as $item): ?>
            <?php
                $unit = $item['unit'] ?? 'trái';
                $quantity = $item['quantity'];
                $price = $item['price'];
                $display_quantity = $unit === 'kg' ? ($quantity * 0.5) . ' kg' : $quantity . ' trái';
                $subtotal = $unit === 'kg' ? $price * ($quantity * 0.5) : $price * $quantity;
            ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="60"></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $display_quantity; ?></td>
                <td><?php echo number_format($price, 0, ',', '.'); ?> VND/<?php echo $unit; ?></td>
                <td><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <select name="rating" class="form-select mb-2">
                            <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                            <option value="4">⭐️⭐️⭐️⭐️</option>
                            <option value="3">⭐️⭐️⭐️</option>
                            <option value="2">⭐️⭐️</option>
                            <option value="1">⭐️</option>
                        </select>
                        <textarea name="comment" class="form-control mb-2" placeholder="Nhập đánh giá của bạn"></textarea>
                        <button type="submit" name="review" class="btn btn-primary btn-sm">Gửi đánh giá</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="order_history.php" class="btn btn-primary">Quay lại lịch sử đơn hàng</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
