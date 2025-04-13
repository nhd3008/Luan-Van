<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra sản phẩm tồn tại
$stmt = $conn->prepare("SELECT name FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Sản phẩm không tồn tại.");
}
$product = $result->fetch_assoc();

// Xử lý khi gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)$_POST['quantity'];
    $purchase_price = (float)$_POST['purchase_price'];
    $supplier = trim($_POST['supplier']);
    $unit_type = $_POST['unit_type'];

    if ($quantity > 0 && $purchase_price > 0 && $supplier !== '') {
        // Cập nhật bảng inventory
        $stmt = $conn->prepare("INSERT INTO inventory (product_id, quantity, purchase_price, supplier) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $product_id, $quantity, $purchase_price, $supplier);
        $stmt->execute();

        // Cập nhật lại stock_quantity, purchase_price và unit trong bảng products
        $update = $conn->prepare("UPDATE products SET 
            stock_quantity = stock_quantity + ?,  
            unit = ?
            WHERE product_id = ?");
        $update->bind_param("idi", $quantity, $unit_type, $product_id);
        $update->execute();

        header("Location: manage_inventory.php?success=1");
        exit;
    } else {
        $error = "Vui lòng nhập đầy đủ và hợp lệ!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập kho: <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<header>
    <h2>➕ Nhập kho cho: <em><?= htmlspecialchars($product['name']) ?></em></h2>
    <nav>
        <a href="manage_inventory.php">🔙 Quay lại Quản lý Kho</a>
    </nav>
</header>

<section>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <label for="quantity">Số lượng nhập:</label>
    <input type="number" name="quantity" id="quantity" required min="1">

    <label for="purchase_price">Giá nhập (VND):</label>
    <input type="number" step="0.01" name="purchase_price" id="purchase_price" required>

    <label for="unit_type">Cách bán:</label>
    <select name="unit_type" id="unit_type" required>
        <option value="kg">Theo kg</option>
        <option value="trái">Theo trái</option>
    </select>

    <label for="supplier">Nhà cung cấp:</label>
    <input type="text" name="supplier" id="supplier" required>

    <button type="submit" class="btn btn-success">✔ Nhập kho</button>
</form>
</section>

</body>
</html>
