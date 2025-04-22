<?php
require_once __DIR__ . '/../database/db_connect.php';
require_once __DIR__ . '/../vendor/autoload.php';  // Đảm bảo đường dẫn đến vendor/autoload.php đúng


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Lấy dữ liệu doanh thu từ bảng revenue
$query = "SELECT order_id, total_amount, customer_name, customer_phone, customer_address, product_names, payment_method, created_at FROM revenue";
$result = $conn->query($query);

// Kiểm tra xem có dữ liệu hay không
if ($result && $result->num_rows > 0) {
    // Tạo một đối tượng Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Đặt tiêu đề cho các cột
    $sheet->setCellValue('A1', 'Order ID');
    $sheet->setCellValue('B1', 'Total Amount');
    $sheet->setCellValue('C1', 'Customer Name');
    $sheet->setCellValue('D1', 'Customer Phone');
    $sheet->setCellValue('E1', 'Customer Address');
    $sheet->setCellValue('F1', 'Product Names');
    $sheet->setCellValue('G1', 'Payment Method');
    $sheet->setCellValue('H1', 'Created At');

    // Duyệt qua từng dòng dữ liệu và ghi vào Excel
    $rowNumber = 2; // Bắt đầu ghi từ dòng 2
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['order_id']);
        $sheet->setCellValue('B' . $rowNumber, $row['total_amount']);
        $sheet->setCellValue('C' . $rowNumber, $row['customer_name']);
        $sheet->setCellValue('D' . $rowNumber, $row['customer_phone']);
        $sheet->setCellValue('E' . $rowNumber, $row['customer_address']);
        $sheet->setCellValue('F' . $rowNumber, $row['product_names']);
        $sheet->setCellValue('G' . $rowNumber, $row['payment_method']);
        $sheet->setCellValue('H' . $rowNumber, $row['created_at']);
        $rowNumber++;
    }

    // Tạo đối tượng Writer để lưu file Excel
    $writer = new Xlsx($spreadsheet);

    // Xuất file Excel
    $fileName = 'doanh_thu_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
} else {
    echo "Không có dữ liệu doanh thu để xuất.";
}
?>
