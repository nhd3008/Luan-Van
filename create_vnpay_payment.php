<?php
session_start();
require_once 'vnpay/config.php';

$order_id = $_GET['order_id'] ?? null;
$amount = $_GET['amount'] ?? 0;

if (!$order_id || $amount <= 0) {
    die("Dữ liệu không hợp lệ.");
}

// Tạo mã giao dịch duy nhất
$vnp_TxnRef = 'ORDER' . $order_id . '-' . time();
$vnp_OrderInfo = "Thanh toán đơn hàng #$order_id";
$vnp_OrderType = 'billpayment';
$vnp_Amount = $amount * 100; // VNPAY yêu cầu đơn vị là VND * 100
$vnp_Locale = 'vn';
$vnp_BankCode = '';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

// Tạo URL thanh toán
$vnp_Url = $vnp_Url;
$vnp_Returnurl = $vnp_Returnurl;

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);
if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

// Chuyển hướng sang VNPAY
header('Location: ' . $vnp_Url);
exit;
?>
