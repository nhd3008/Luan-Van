<?php
session_start();
require_once __DIR__ . '/../includes/middleware_admin.php';
checkPermissions('admin');
require_once __DIR__ . '/../database/db_connect.php';

// Lấy dữ liệu thống kê tổng quát
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_amount) AS total FROM revenue")->fetch_assoc()['total'] ?? 0;

// Xử lý bộ lọc ngày
$filter = $_GET['filter'] ?? '7days';
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Thống kê thêm
$available_products = $conn->query("SELECT COUNT(*) AS total FROM products WHERE stock_quantity > 0")->fetch_assoc()['total'];
$low_stock_products = $conn->query("SELECT COUNT(*) AS total FROM products WHERE stock_quantity <= 5")->fetch_assoc()['total'];

// Thống kê sản phẩm bán chạy nhất
$top_products_query = "
    SELECT p.name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_status = 'shipped'
    GROUP BY oi.product_id
    ORDER BY total_sold DESC
    LIMIT 5
";

$top_products_result = $conn->query($top_products_query);
$top_products = [];
while ($row = $top_products_result->fetch_assoc()) {
    $top_products[] = $row;
}

// Câu truy vấn doanh thu theo bộ lọc
$revenue_query = "SELECT DATE(created_at) as date, SUM(total_amount) as total FROM revenue";

switch ($filter) {
    case 'today':
        $revenue_query .= " WHERE DATE(created_at) = CURDATE()";
        break;
    case 'month':
        $revenue_query .= " WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
        break;
    case 'custom':
        if ($start_date && $end_date) {
            $revenue_query .= " WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
        }
        break;
    default: // 7 ngày gần nhất
        $revenue_query .= " WHERE created_at >= CURDATE() - INTERVAL 7 DAY";
        break;
}
$revenue_query .= " GROUP BY DATE(created_at) ORDER BY date DESC";

$revenue_result = $conn->query($revenue_query);

$revenue_dates = [];
$revenue_totals = [];
while ($row = $revenue_result->fetch_assoc()) {
    $revenue_dates[] = $row['date'];
    $revenue_totals[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang quản trị - Fruit For Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<?php include_once __DIR__ . '/nav_admin.php'; ?>

<div class="container mt-5">
    <h2 class="text-center text-success mb-4">🎯 Trang quản trị - Fruit For Health</h2>

    <!-- Thống kê tổng quan -->
    <div class="row text-center mb-5">
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-primary d-flex flex-column justify-content-center">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="display-6 text-primary"><?= $total_products ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-success d-flex flex-column justify-content-center">
                <div class="card-body">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="display-6 text-success"><?= $total_users ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-warning d-flex flex-column justify-content-center">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="display-6 text-warning"><?= $total_orders ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm border-danger d-flex flex-column justify-content-center">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <p class="display-6 text-danger"><?= number_format($total_revenue, 0, ',', '.') ?> VND</p>
                </div>
            </div>
        </div>
    </div>


    <div class="row text-center mb-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-info">
            <div class="card-body">
                <h5 class="card-title">Sản phẩm còn hàng</h5>
                <p class="display-6 text-info"><?= $available_products ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-danger">
            <div class="card-body">
                <h5 class="card-title">Sắp hết hàng (≤5)</h5>
                <p class="display-6 text-danger"><?= $low_stock_products ?></p>
            </div>
        </div>
    </div>
</div>


    <!-- Bộ lọc thời gian -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="get" class="row row-cols-lg-auto g-3 align-items-center">
                <div class="col-auto">
                    <label class="form-label fw-bold">Lọc theo:</label>
                </div>
                <div class="col-auto">
                    <select name="filter" class="form-select" onchange="this.form.submit()">
                        <option value="7days" <?= $filter === '7days' ? 'selected' : '' ?>>7 ngày qua</option>
                        <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Hôm nay</option>
                        <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>Tháng này</option>
                        <option value="custom" <?= $filter === 'custom' ? 'selected' : '' ?>>Tùy chọn</option>
                    </select>
                </div>

                <?php if ($filter === 'custom'): ?>
                    <div class="col-auto">
                        <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                📊 Biểu đồ doanh thu 
                <?php
                    switch ($filter) {
                        case 'today': echo '(Hôm nay)'; break;
                        case 'month': echo '(Tháng này)'; break;
                        case 'custom': echo "($start_date đến $end_date)"; break;
                        default: echo '(7 ngày gần nhất)';
                    }
                ?>
            </h4>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    <!-- Bảng chi tiết doanh thu -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">📅 Chi tiết doanh thu</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ngày</th>
                        <th>Tổng doanh thu (VND)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($revenue_dates as $index => $date): ?>
                        <tr>
                            <td><?= $date ?></td>
                            <td class="text-end text-success"><?= number_format($revenue_totals[$index], 0, ',', '.') ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($revenue_dates)): ?>
                        <tr><td colspan="2" class="text-muted">Không có dữ liệu</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Top sản phẩm bán chạy -->
<div class="card mt-4 shadow">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0">🔥 Top 5 sản phẩm bán chạy</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0 text-center">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng đã bán</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_products as $index => $product): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td class="text-success fw-bold"><?= $product['total_sold'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($top_products)): ?>
                    <tr><td colspan="3" class="text-muted">Không có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Biểu đồ -->
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($revenue_dates) ?>,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: <?= json_encode($revenue_totals) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VND';
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
