<?php
require_once '../db.php';

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$purchase_price = (float)$_POST['purchase_price'];
$selling_price = (float)$_POST['selling_price'];
$unit_type = trim($_POST['unit_type']);
$supplier = trim($_POST['supplier']);

// Kiểm tra dữ liệu hợp lệ
if ($quantity <= 0 || $purchase_price <= 0 || $selling_price <= 0 || empty($supplier) || empty($unit_type)) {
    die("❌ Vui lòng điền đầy đủ và hợp lệ thông tin!");
}

if ($selling_price <= $purchase_price) {
    die("❌ Giá bán phải lớn hơn giá nhập!");
}

// Ghi vào bảng inventory
$stmt = $conn->prepare("INSERT INTO inventory (product_id, quantity, purchase_price, selling_price, unit_type, supplier) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiddss", $product_id, $quantity, $purchase_price, $selling_price, $unit_type, $supplier);
$stmt->execute();

// Cập nhật lại tồn kho & giá bán
$update = $conn->prepare("UPDATE products 
                          SET stock_quantity = stock_quantity + ?, selling_price = ?, unit_type = ?
                          WHERE product_id = ?");
$update->bind_param("idsi", $quantity, $selling_price, $unit_type, $product_id);
$update->execute();

header("Location: manage_inventory.php?success=1");
exit;
?>
