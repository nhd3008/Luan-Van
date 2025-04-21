<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Truy vấn danh sách nhà cung cấp và số lượng hàng nhập từ bảng inventory
$query = "SELECT suppliers.supplier_id, suppliers.name, suppliers.phone, suppliers.address, suppliers.created_at,
            COUNT(inventory.id) AS total_orders,
            SUM(inventory.purchase_price * inventory.quantity) AS total_value
          FROM suppliers
          LEFT JOIN inventory ON inventory.supplier = suppliers.name
          GROUP BY suppliers.name";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà Cung Cấp</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include_once __DIR__ . '/nav_admin.php'; ?>

<section>
    <h3>📋 Danh sách Nhà Cung Cấp</h3>
    
    <a href="add_supplier.php" class="btn btn-success">Thêm Nhà Cung Cấp</a>
    
    <h3>Danh sách Nhà Cung Cấp</h3>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Nhà Cung Cấp</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th>Số Đơn Hàng</th>
                    <th>Tổng Giá Trị Đơn Hàng</th>
                    <th>Ngày Hợp Tác</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['supplier_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['total_orders']) ?></td>
                    <td><?= number_format($row['total_value'], 0, ',', '.') ?> VND</td>
                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    <td>
                        <a class="btn btn-primary" href="update_supplier.php?id=<?= $row['supplier_id'] ?>">Sửa</a>
                        <a class="btn btn-danger" href="delete_supplier.php?id=<?= $row['supplier_id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?');">Xoá</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>❌ Không có nhà cung cấp nào!</p>
    <?php endif; ?>
</section>

</body>
</html>
