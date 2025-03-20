<?php
include __DIR__ . '/../database/db_connect.php';
include __DIR__ . '/../includes/middleware_admin.php';
include __DIR__ . '/../includes/header.php';

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<h2>Quản lý sản phẩm</h2>
<a href="add_product.php">Thêm sản phẩm</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['user_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['price'] ?></td>
        <td>
            <a href="edit_product.php?id=<?= $row['user_id'] ?>">Sửa</a> | 
            <a href="delete_product.php?id=<?= $row['user_id'] ?>">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include __DIR__ . '/../includes/footer.php'; ?>
