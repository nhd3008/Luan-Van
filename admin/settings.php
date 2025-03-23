<?php
include 'includes/middleware_admin.php';
include '../database/db_connect.php';
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store_name = $_POST['store_name'];
    $stmt = $conn->prepare("UPDATE settings SET store_name = ?");
    $stmt->bind_param("s", $store_name);
    $stmt->execute();
}

$query = "SELECT store_name FROM settings";
$result = $conn->query($query);
$row = $result->fetch_assoc();
?>

<h2>Cài đặt hệ thống</h2>
<form method="POST">
    <label>Tên cửa hàng:</label>
    <input type="text" name="store_name" value="<?= $row['store_name'] ?>" required>
    <button type="submit">Lưu</button>
</form>

<?php include 'includes/footer.php'; ?>
