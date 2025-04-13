<?php
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';

// Xử lý lọc ngày và lọc sản phẩm
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$selected_product_id = $_GET['product_id'] ?? '';

$where = "DATE(inv.created_at) BETWEEN ? AND ?";
$params = [$start_date, $end_date];
$types = "ss";

if (!empty($selected_product_id)) {
    $where .= " AND inv.product_id = ?";
    $params[] = $selected_product_id;
    $types .= "i";
}

$sql = "SELECT inv.id, p.name, inv.quantity, inv.purchase_price, inv.supplier, inv.created_at
        FROM inventory inv
        JOIN products p ON inv.product_id = p.product_id
        WHERE $where
        ORDER BY inv.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$inventory_data = [];
$total_spent = 0;
$summary_by_supplier = [];

while ($row = $result->fetch_assoc()) {
    $inventory_data[] = $row;
    $total = $row['quantity'] * $row['purchase_price'];
    $total_spent += $total;

    $supplier = $row['supplier'];
    if (!isset($summary_by_supplier[$supplier])) {
        $summary_by_supplier[$supplier] = [
            'total_quantity' => 0,
            'total_spent' => 0
        ];
    }
    $summary_by_supplier[$supplier]['total_quantity'] += $row['quantity'];
    $summary_by_supplier[$supplier]['total_spent'] += $total;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📊 Báo cáo Nhập kho</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<header>
    <h2>📊 Báo cáo Nhập kho</h2>
    <nav>
        <a href="index.php">🏠 Trang chủ Admin</a>
        <a href="manage_inventory.php">📦 Quản lý Kho</a>
    </nav>
</header>

<section>
    <form method="get" class="filter-form">
        <label for="start_date">Từ ngày:</label>
        <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($start_date) ?>">

        <label for="end_date">Đến ngày:</label>
        <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($end_date) ?>">

        <label for="product_id">Sản phẩm:</label>
        <select name="product_id" id="product_id">
            <option value="">-- Tất cả --</option>
            <?php
            $productResult = $conn->query("SELECT product_id, name FROM products ORDER BY name ASC");
            while ($prod = $productResult->fetch_assoc()):
                $selected = ($selected_product_id == $prod['product_id']) ? 'selected' : '';
            ?>
                <option value="<?= $prod['product_id'] ?>" <?= $selected ?>>
                    <?= htmlspecialchars($prod['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="btn btn-primary">📅 Lọc</button>
    </form>

    <h3>📄 Danh sách phiếu nhập</h3>
    <?php if (!empty($inventory_data)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá nhập</th>
                    <th>Tổng</th>
                    <th>Nhà cung cấp</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory_data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= (int)$row['quantity'] ?></td>
                    <td><?= number_format($row['purchase_price'], 0, ',', '.') ?> VND</td>
                    <td><?= number_format($row['quantity'] * $row['purchase_price'], 0, ',', '.') ?> VND</td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>💰 Tổng chi phí: <span style="color: green;">
            <?= number_format($total_spent, 0, ',', '.') ?> VND</span>
        </h3>

        <h3>📦 Tổng hợp theo Nhà cung cấp</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nhà cung cấp</th>
                    <th>Tổng số lượng</th>
                    <th>Tổng chi phí</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($summary_by_supplier as $supplier => $summary): ?>
                <tr>
                    <td><?= htmlspecialchars($supplier) ?></td>
                    <td><?= (int)$summary['total_quantity'] ?></td>
                    <td><?= number_format($summary['total_spent'], 0, ',', '.') ?> VND</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p class="error-message">❌ Không có dữ liệu nhập kho trong khoảng thời gian đã chọn.</p>
    <?php endif; ?>
</section>
</body>
</html>