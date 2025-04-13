<?php
/*
require_once '../database/db_connect.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Danh sách sản phẩm</h2>
    <div>
    <a href="../admin/manage_products.php" class="btn btn-secondary me-2">🔙 Quản lý sản phẩm</a>
    <a href="add_product.php" class="btn btn-success">➕ Thêm sản phẩm</a>
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['selling_price'], 0, ',', '.') . ' VND'; ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td>
                <?php if (!empty($row['image_url'])): ?>
                    <img src="../<?php echo $row['image_url']; ?>" width="100">
                <?php else: ?>
                    <span class="text-muted">Không có ảnh</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
