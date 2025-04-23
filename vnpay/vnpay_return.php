<?php
session_start();
require_once 'config.php';
require_once '../database/db_connect.php'; // File này kết nối CSDL, chỉnh lại path nếu cần

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = [];

foreach ($_GET as $key => $value) {
    if ($key != 'vnp_SecureHash' && $key != 'vnp_SecureHashType') {
        $inputData[$key] = $value;
    }
}

// Sắp xếp lại và tạo chuỗi để kiểm tra
ksort($inputData);
$hashData = '';
foreach ($inputData as $key => $value) {
    $hashData .= $key . '=' . $value . '&';
}
$hashData = rtrim($hashData, '&');


// Tạo SecureHash từ chuỗi đã tạo
$secureHashCheck = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Debug: In ra chuỗi hash đã tính và SecureHash từ VNPAY
echo "<pre>";
echo "Chuỗi kiểm tra (hash data): $hashData\n";
echo "SecureHash từ VNPAY: $vnp_SecureHash\n";
echo "SecureHash tính được: $secureHashCheck\n";
echo "</pre>";

// Kiểm tra hash có khớp không
if ($secureHashCheck === $vnp_SecureHash) {
    // Giao dịch hợp lệ
    if ($_GET['vnp_ResponseCode'] == '00') {
        $orderCode = $_GET['vnp_TxnRef'];
        $amount = $_GET['vnp_Amount'] / 100;
        $transactionNo = $_GET['vnp_TransactionNo'];

        // Tách order_id từ vnp_TxnRef: vd: ORDER123-164578 -> lấy số 123
        preg_match('/ORDER(\d+)-/', $orderCode, $matches);
        $order_id = $matches[1] ?? 0;

        if ($order_id > 0) {
            // Cập nhật trạng thái đơn hàng
            $stmt = $conn->prepare("UPDATE orders SET status = 'Paid', payment_method = 'VNPAY', payment_time = NOW(), transaction_code = ? WHERE order_id = ?");
            $stmt->bind_param("si", $transactionNo, $order_id);
            $stmt->execute();
        }

        echo "<h2 style='color:green;'>Thanh toán thành công!</h2>";
        echo "<p>Mã đơn hàng: $order_id</p>";
        echo "<p>Số tiền: " . number_format($amount, 0, ',', '.') . "đ</p>";
        echo "<p>Mã giao dịch: $transactionNo</p>";
    } else {
        echo "<h2 style='color:red;'>Thanh toán thất bại</h2>";
        echo "<p>Lỗi: Mã phản hồi " . $_GET['vnp_ResponseCode'] . "</p>";
    }
} else {
    echo "<h2 style='color:red;'>Thanh toán thành công</h2>";
    header("Location: /fruit_store/order_details.php"); // Thay đổi 'order_details.php' thành đường dẫn trang chi tiết đơn hàng của bạn
exit();
}
