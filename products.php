<?php
session_start();
require_once __DIR__ . '/database/db_connect.php';

$search = "";
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

if ($selected_category && in_array($selected_category, ["Tăng cường miễn dịch", "Tốt cho tiêu hóa", "Hỗ trợ giảm cân", "Làm đẹp da"])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$categories = ["Tăng cường miễn dịch", "Tốt cho tiêu hóa", "Hỗ trợ giảm cân", "Làm đẹp da"];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h1 class="text-center">Danh sách Sản phẩm</h1>
    <form method="GET" class="text-center mb-4">
        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
    </form>
    
    <div class="text-center mb-3">
        <strong>Lọc theo danh mục:</strong>
        <?php foreach ($categories as $category) : ?>
            <a href="?category=<?php echo urlencode($category); ?>" class="btn btn-outline-primary m-1"> <?php echo htmlspecialchars($category); ?> </a>
        <?php endforeach; ?>
    </div>
    
    <div class="row">
        <?php if (count($products) > 0) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo !empty($product['image_url']) ? $product['image_url'] : 'uploads/default.jpg'; ?>" class="card-img-top" alt="Hình ảnh sản phẩm">
                        <div class="card-body text-center">
                            <h5 class="card-title"> <?php echo htmlspecialchars($product['name']); ?> </h5>
                            <p class="card-text">Giá: <?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                            <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                            <a href="cart.php?action=add&id=<?php echo $product['product_id']; ?>" class="btn btn-success">Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center">Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
