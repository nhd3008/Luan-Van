<?php
// Kết nối CSDL (đường dẫn tương đối từ api/ đến database/)
require_once '../database/db_connect.php';

// Truy vấn lấy sản phẩm
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Xử lý kết quả
if ($result->num_rows > 0) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    // Trả về JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    echo "Không có sản phẩm!";
}

// Đóng kết nối
$conn->close();
?>