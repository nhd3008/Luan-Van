<?php
include 'includes/middleware_admin.php';
include '../database/db_connect.php';
include 'includes/header.php';

$query = "SELECT * FROM orders";
$result = $conn->query($query);
?>

<h2>Quản lý đơn hàng</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Khách hàng</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['customer_name'] ?></td>
        <td><?= $row['total_price'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <a href="update_order.php?id=<?= $row['id'] ?>&status=shipped">Giao hàng</a> | 
            <a href="update_order.php?id=<?= $row['id'] ?>&status=canceled">Hủy</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
