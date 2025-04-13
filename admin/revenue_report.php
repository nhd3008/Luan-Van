<?php
session_start();
require_once __DIR__ . '/../includes/middleware_admin.php';
require_once __DIR__ . '/../database/db_connect.php';


// Truy vấn dữ liệu doanh thu theo ngày
$query = "SELECT DATE(created_at) as date, SUM(total_amount) as total FROM revenue GROUP BY DATE(created_at) ORDER BY date DESC";
$result = $conn->query($query);

$dates = [];
$totals = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['date'];
    $totals[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo doanh thu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Báo cáo doanh thu</h2>

    <canvas id="revenueChart" height="120"></canvas>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Doanh thu theo ngày (VND)',
                    data: <?php echo json_encode($totals); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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

    <h4 class="mt-5">Chi tiết</h4>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Tổng doanh thu (VND)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dates as $index => $date): ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td class="text-end text-success"><?php echo number_format($totals[$index], 0, ',', '.'); ?> VND</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
