<?php
session_start();
require_once 'database/db_connect.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("<p class='text-center text-danger'>Bạn cần <a href='auth/login.php'>đăng nhập</a> để thêm sản phẩm vào giỏ hàng.</p>");
}



$user_id = $_SESSION['user_id'];

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {

    // Kiểm tra nếu là admin thì không cho thêm
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        die("<p class='text-center text-danger'>Tài khoản admin không cần thêm vào giỏ hàng. Bạn không cần mua vào kho mà lấy. <a href='index.php'>Quay lại trang chủ</a></p>");
    }

    $product_id = $_POST['product_id'];


    // Kiểm tra xem sản phẩm đã có trong giỏ hàng của user chưa
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu sản phẩm đã có, cập nhật số lượng
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Nếu chưa có, thêm mới vào giỏ hàng
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
}

// Xử lý cập nhật số lượng sản phẩm trong giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $product_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
        }
    }
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $remove_id);
    $stmt->execute();
}

// Lấy giỏ hàng từ database
$stmt = $conn->prepare("SELECT p.product_id, p.name, p.price, p.image_url, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Tính tổng tiền
$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Fruit For Health</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Giỏ hàng của bạn</h2>

    <?php if (empty($cart_items)): ?>
        <p class="text-center">Giỏ hàng trống. <a href="index.php">Tiếp tục mua sắm</a></p>
    <?php else: ?>
        <form method="POST">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="60" class="rounded"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="text-danger fw-bold"><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control text-center" style="width: 80px; display: inline-block;">
                            </td>
                            <td class="fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4 class="text-end text-success fw-bold">Tổng tiền: <?php echo number_format($total, 0, ',', '.'); ?> VND</h4>
            <div class="text-end">
                <button type="submit" name="update_cart" class="btn btn-warning">Cập nhật giỏ hàng</button>
                <a href="checkout.php" class="btn btn-primary">Thanh toán</a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
