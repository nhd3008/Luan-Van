<?php
session_start();
require_once 'database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("<p class='text-center text-danger'>Bạn cần <a href='auth/login.php'>đăng nhập</a> để thêm sản phẩm vào giỏ hàng.</p>");
}

$user_id = $_SESSION['user_id'];

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        die("<p class='text-center text-danger'>Tài khoản admin không cần thêm vào giỏ hàng. Bạn không cần mua vào kho mà lấy. <a href='index.php'>Quay lại trang chủ</a></p>");
    }

    $product_id = $_POST['product_id'];

    // Lấy số lượng tồn kho
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stock_result = $stmt->get_result();
    $stock_data = $stock_result->fetch_assoc();
    $stock_quantity = $stock_data['stock_quantity'] ?? 0;

    // Lấy số lượng hiện tại trong giỏ
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_quantity = $row['quantity'];
        if ($current_quantity + 1 > $stock_quantity) {
            die("<p class='text-center text-danger'>Số lượng bạn muốn thêm đã vượt quá tồn kho. <a href='index.php'>Quay lại</a></p>");
        }
        $new_quantity = $current_quantity + 1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        if ($stock_quantity < 1) {
            die("<p class='text-center text-danger'>Sản phẩm này hiện đã hết hàng. <a href='index.php'>Quay lại</a></p>");
        }
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
}

// Cập nhật giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['update_cart']) || isset($_POST['checkout_now']))) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        // Lấy số lượng tồn kho
        $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stock_result = $stmt->get_result();
        $stock_data = $stock_result->fetch_assoc();
        $stock_quantity = $stock_data['stock_quantity'] ?? 0;

        if ($quantity > $stock_quantity) {
            echo "<div class='alert alert-danger text-center'>Số lượng cập nhật vượt quá số lượng tồn kho cho sản phẩm ID $product_id.</div>";
            continue;
        }

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

    // Nếu người dùng bấm "Thanh toán" thì chuyển hướng
    if (isset($_POST['checkout_now'])) {
        header("Location: checkout.php");
        exit();
    }
}


// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);

    // Xóa khỏi giỏ hàng
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $remove_id);
    $stmt->execute();

    // Sau khi xóa, reload lại trang để cập nhật giao diện
    header("Location: cart.php");
    exit();
}


// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("SELECT p.product_id, p.name, p.selling_price, p.image_url, p.unit, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

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
                            <td class="text-danger fw-bold"><?php echo number_format($item['selling_price'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control text-center" style="width: 80px; display: inline-block;">
                            </td>
                            <td class="fw-bold">
                                <?php
                                    $unit = $item['unit'];
                                    $quantity = $item['quantity'];

                                    if ($unit == 'kg') {
                                        $weight = $quantity * 0.5; // mỗi đơn vị là 0.5kg
                                        $subtotal = $item['selling_price'] * $weight;
                                        echo number_format($subtotal, 0, ',', '.') . " VND" . "<br><small>({$weight} kg)</small>";
                                    } else {
                                        $subtotal = $item['selling_price'] * $quantity;
                                        echo number_format($subtotal, 0, ',', '.') . " VND" . "<br><small>({$quantity} trái)</small>";
                                    }

                                    $total += $subtotal;
                                ?>
                            </td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
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
