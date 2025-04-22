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

// Lấy thông tin nhà cung cấp hiện tại
$stmt_supplier = $conn->prepare("SELECT supplier FROM inventory WHERE product_id = ? LIMIT 1");
$stmt_supplier->bind_param("i", $product_id);  // Gắn product_id vào truy vấn
$stmt_supplier->execute();
$result_supplier = $stmt_supplier->get_result();

if ($result_supplier->num_rows === 0) {
    die("❌ Không tìm thấy nhà cung cấp cho sản phẩm này.");
}
$supplier = $result_supplier->fetch_assoc();

// Xử lý khi gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)$_POST['quantity'];
    $purchase_price = (float)$_POST['purchase_price'];
    $invoice_code = trim($_POST['invoice_code']);
    $import_date = $_POST['import_date']; // Ngày nhập kho

    if ($quantity > 0 && $purchase_price > 0 && $invoice_code !== '' && $import_date !== '') {
        // Lưu thông tin nhà cung cấp và các dữ liệu vào bảng inventory
        $stmt = $conn->prepare("INSERT INTO inventory (product_id, quantity, purchase_price, import_date, invoice_code, supplier, imported_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iidssss", $product_id, $quantity, $purchase_price, $import_date, $invoice_code, $supplier['supplier'], $_SESSION['email']);
        $stmt->execute();

        // Cập nhật lại stock_quantity trong bảng products (không thay đổi purchase_price)
        $update = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE product_id = ?");
        $update->bind_param("ii", $quantity, $product_id);
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

    <label for="import_date">Ngày nhập kho:</label>
    <input type="date" name="import_date" id="import_date" required>

    <label for="invoice_code">Số hóa đơn:</label>
    <input type="text" name="invoice_code" id="invoice_code" required>

    <button type="submit" class="btn btn-success">✔ Nhập kho</button>
</form>
</section>

</body>
</html>
