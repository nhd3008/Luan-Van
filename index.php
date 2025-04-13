<?php
session_start();
require_once __DIR__ . '/database/db_connect.php';

$search = "";
$selected_category = $_GET['category'] ?? '';

if ($selected_category && in_array($selected_category, ["Tăng cường miễn dịch", "Tốt cho tiêu hóa", "Hỗ trợ giảm cân", "Làm đẹp da"])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND status = 'selling'");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? AND status = 'selling'");
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products WHERE status = 'selling'");
}

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
    <style>
        .banner {
            background: url('assets/images/banner-fruit.jpg') center/cover no-repeat;
            color: white;
            text-shadow: 1px 1px 4px #000;
            padding: 80px 0;
            text-align: center;
            position: relative;
        }
        .banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
        }
        .banner > * {
            position: relative;
            z-index: 1;
        }
        .category-card {
            display: block;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .category-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 15px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .search-box {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="banner mb-4">
    <h1 class="display-5 fw-bold">Trái Cây Tươi Ngon - Tốt Cho Sức Khỏe 🍓🍍🥭</h1>
    <p class="lead">Chào mừng bạn đến với Fruit For Health – nơi bạn tìm thấy những loại trái cây tốt nhất cho cơ thể.</p>
    <a href="products.php" class="btn btn-warning fw-bold mt-3">Khám phá ngay</a>
</div>

<div class="container mt-4">
    <form method="GET" action="index.php" class="d-flex search-box mb-4">
        <input class="form-control me-2" type="search" name="search" placeholder="Tìm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-success" type="submit">Tìm kiếm</button>
    </form>
</div>

<section class="container mb-5">
    <h2 class="text-center mb-4">🍎 Danh mục nổi bật</h2>
    <div class="row g-4 text-center">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3 col-sm-6">
                <a href="index.php?category=<?php echo urlencode($category); ?>" class="category-card">
                    <img src="assets/images/<?php echo strtolower(str_replace(' ', '_', $category)); ?>.jpg" alt="<?php echo $category; ?>">
                    <div class="p-3">
                        <h5 class="fw-semibold"><?php echo $category; ?></h5>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="container">
    <h2 class="text-center mb-4">
        <?php echo $selected_category ? "Danh mục: " . htmlspecialchars($selected_category) : "Tất cả sản phẩm"; ?>
    </h2>
    <div class="row g-4">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $row): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        <img src="<?php echo !empty($row['image_url']) ? $row['image_url'] : 'uploads/default.jpg'; ?>" class="card-img-top" alt="Hình ảnh sản phẩm">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="text-danger fw-bold"><?php echo number_format($row['selling_price'], 0, ',', '.'); ?> VND</p>
                            <?php if ($row['stock_quantity'] > 0): ?>
                                <span class="badge bg-success mb-2">✔ Còn hàng</span>
                            <?php else: ?>
                                <span class="badge bg-danger mb-2">✖ Hết hàng</span>
                            <?php endif; ?>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="btn btn-success w-100" <?php if ($row['stock_quantity'] <= 0) echo 'disabled'; ?>>
                                    🛒 Thêm vào giỏ hàng
                                </button>
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

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
