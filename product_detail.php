<?php
session_start();
require_once __DIR__ . '/database/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='text-danger text-center mt-5'>Sản phẩm không hợp lệ.</p>";
    exit;
}

$product_id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p class='text-danger text-center mt-5'>Không tìm thấy sản phẩm.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container my-5 product-detail">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo !empty($product['image_url']) ? $product['image_url'] : 'uploads/default.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-6">
            <h2 class="text-success fw-bold"><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-muted">Danh mục: <?php echo htmlspecialchars($product['category']); ?></p>
            <h4 class="text-danger">Giá: <?php echo number_format($product['selling_price'], 0, ',', '.'); ?> VND</h4>
            <p class="mt-3">Mô tả sản phẩm: <br> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <!-- Nhãn tồn kho -->
            <div class="mt-3">
                <?php if ($product['stock_quantity'] > 0): ?>
                    <span class="badge bg-success">✔ Còn hàng</span>
                <?php else: ?>
                    <span class="badge bg-danger">✖ Hết hàng</span>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <button type="submit" class="btn btn-success" <?php if ($product['stock_quantity'] <= 0) echo 'disabled'; ?>>
                        🛒 Thêm vào giỏ hàng
                    </button>
                    <a href="products.php" class="btn btn-outline-secondary ms-2">⬅ Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
