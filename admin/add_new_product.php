<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Lấy danh sách nhà cung cấp từ bảng suppliers
$supplier_options = [];
$supplier_result = $conn->query("SELECT DISTINCT name FROM suppliers ORDER BY name ASC");
if ($supplier_result) {
    while ($row = $supplier_result->fetch_assoc()) {
        $supplier_options[] = $row['name'];  // Lưu tên nhà cung cấp vào mảng
    }
}

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
    $supplier = $_POST['supplier'];
    $invoice_code = $_POST['invoice_code'];  // Lấy mã hoá đơn từ form
    $import_date = $_POST['import_date'];  // Lấy ngày nhập kho từ form


    if ($name !== '' && $quantity > 0 && $purchase_price > 0 && $supplier !== '' && $invoice_code !== '') {
        $product_id = generateUniqueProductID($conn);

        // Thêm sản phẩm vào bảng products
        $stmt = $conn->prepare("INSERT INTO products 
            (product_id, name, stock_quantity, unit, category, visibility, status)
            VALUES (?, ?, ?, ?, 'Chung', 'public', 'not_selling')");
        $stmt->bind_param("isis", $product_id, $name, $quantity, $unit);
        $stmt->execute();

        // Thêm thông tin nhập kho vào bảng inventory, bao gồm mã hoá đơn
        $inv = $conn->prepare("INSERT INTO inventory (product_id, quantity, purchase_price, supplier, unit_type, invoice_code, import_date, imported_by) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $inv->bind_param("iidssiss", $product_id, $quantity, $purchase_price, $supplier, $unit, $invoice_code, $import_date, $_SESSION['email']);
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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            padding: 1rem 2rem;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        nav a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        .form-container {
            background: #fff;
            padding: 2rem;
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        .form-container label {
            display: block;
            margin-top: 1.2rem;
            font-weight: 600;
            font-size: 14px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            color: #333;
        }

        .form-container button {
            margin-top: 2rem;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .error-message {
            background-color: #ffe6e6;
            color: #d8000c;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<header>
    <h2>➕ Nhập kho & Thêm sản phẩm mới</h2>
    <nav>
        <a href="manage_inventory.php">🔙 Quay lại Quản lý Kho</a>
    </nav>
</header>

<section class="form-container">
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
        <select name="supplier" id="supplier" required>
            <option value="">-- Chọn nhà cung cấp --</option>
            <?php foreach ($supplier_options as $supplier): ?>
                <option value="<?= htmlspecialchars($supplier) ?>"><?= htmlspecialchars($supplier) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="invoice_code">Mã hoá đơn:</label>
        <input type="text" name="invoice_code" id="invoice_code" class="form-control" required>
        <label for="import_date">Ngày nhập kho:</label>
        <input type="date" name="import_date" id="import_date" class="form-control" required>

 
        <button type="submit">✔ Thêm sản phẩm & Nhập kho</button>
    </form>
</section>

</body>
</html>
