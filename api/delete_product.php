<?php
require_once '../database/db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: ID sản phẩm không hợp lệ.");
}

$id = $_GET['id'];

// Lấy đường dẫn ảnh của sản phẩm trước khi xóa
$sql_check = "SELECT image_url FROM products WHERE product_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    die("Lỗi: Sản phẩm không tồn tại hoặc đã bị xóa.");
}

$product = $result_check->fetch_assoc();

// Xóa ảnh nếu có
if (!empty($product['image_url']) && file_exists("../" . $product['image_url'])) {
    unlink("../" . $product['image_url']);
}

// Xóa sản phẩm khỏi database
$sql = "DELETE FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Xóa sản phẩm thành công!'); window.location.href='list_product.php';</script>";
} else {
    echo "Lỗi: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
