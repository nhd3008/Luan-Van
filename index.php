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

// Lưu dữ liệu sản phẩm vào mảng
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$categories = [
    "Tăng cường miễn dịch",
    "Tốt cho tiêu hóa",
    "Hỗ trợ giảm cân",
    "Làm đẹp da"
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruit For Health - Cửa hàng trái cây</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="banner mb-4">
    <h1>Trái Cây Tươi Ngon - Tốt Cho Sức Khỏe 🍓🍍🥭</h1>
    <p>Chào mừng bạn đến với Fruit For Health – nơi bạn tìm thấy những loại trái cây tốt nhất cho cơ thể.</p>
    <a href="products.php" class="btn btn-light">Khám phá ngay</a>
</div>

<div class="container mt-4 container-product-list">

    <form method="GET" action="index.php" class="d-flex search-box">
        <input class="form-control me-2" type="search" name="search" placeholder="Tìm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-success" type="submit">Tìm kiếm</button>
    </form>
</div>

<h1 class="text-center mb-4">Fruit For Health 🍏🍊🍇</h1>

<section class="container mt-5">
    <h2 class="text-center">Danh mục nổi bật</h2>
    <div class="row text-center">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-3">
            <a href="index.php?category=<?php echo urlencode($category); ?>" class="category-card">
                <img src="assets/images/<?php echo strtolower(str_replace(' ', '_', $category)); ?>.jpg" alt="<?php echo $category; ?>">
                <h5><?php echo $category; ?></h5>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="container mt-4">
    <h2 class="text-center">
        <?php echo $selected_category ? "Danh mục: " . htmlspecialchars($selected_category) : "Tất cả sản phẩm"; ?>
    </h2>
    <div class="row">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $row): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="<?php echo !empty($row['image_url']) ? $row['image_url'] : 'uploads/default.jpg'; ?>" class="card-img-top" alt="Hình ảnh sản phẩm">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="text-danger fw-bold"><?php echo number_format($row['price'], 0, ',', '.'); ?> VND</p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="btn btn-success">Thêm vào giỏ hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-danger">⚠️ Không có sản phẩm nào hiển thị!</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
