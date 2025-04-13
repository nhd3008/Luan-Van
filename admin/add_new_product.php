<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Hàm tạo product_id không trùng
function generateUniqueProductID($conn) {
    do {
        $product_id = rand(100000, 999999);
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    $stmt->close();
    return $product_id;
}

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $purchase_price = (float)$_POST['purchase_price'];
    $unit = $_POST['unit'];
    $supplier = trim($_POST['supplier']);

    if ($name !== '' && $quantity > 0 && $purchase_price > 0 && $supplier !== '') {
        $product_id = generateUniqueProductID($conn);

        // Thêm sản phẩm vào bảng products
        $stmt = $conn->prepare("INSERT INTO products 
            (product_id, name, stock_quantity, unit, category, discount, visibility, status)
            VALUES (?, ?, ?, ?, 'Chung', 0.00, 'public', 'not_selling')");
        $stmt->bind_param("isis", $product_id, $name, $quantity, $unit);
        $stmt->execute();

        // Thêm phiếu nhập kho
        $inv = $conn->prepare("INSERT INTO inventory (product_id, quantity, purchase_price, supplier) VALUES (?, ?, ?, ?)");
        $inv->bind_param("iids", $product_id, $quantity, $purchase_price, $supplier);
        $inv->execute();

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
    <title>➕ Thêm sản phẩm mới</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<header>
    <h2>➕ Nhập kho & Thêm sản phẩm mới</h2>
    <nav>
        <a href="manage_inventory.php">🔙 Quay lại Quản lý Kho</a>
    </nav>
</header>

<section>
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="post">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" required>

        <label for="quantity">Số lượng nhập:</label>
        <input type="number" name="quantity" id="quantity" required min="1">

        <label for="unit">Cách bán:</label>
        <select name="unit" id="unit" required>
            <option value="kg">Theo kg</option>
            <option value="trái">Theo trái</option>
        </select>

        <label for="purchase_price">Giá nhập (VND):</label>
        <input type="number" step="0.01" name="purchase_price" id="purchase_price" required>

        <label for="supplier">Nhà cung cấp:</label>
        <input type="text" name="supplier" id="supplier" required>

        <button type="submit" class="btn btn-success">✔ Thêm sản phẩm & Nhập kho</button>
    </form>
</section>

</body>
</html>
