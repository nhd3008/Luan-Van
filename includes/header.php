<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/db_connect.php';

// Kiểm tra nếu người dùng đã đăng nhập
$is_logged_in = isset($_SESSION['user_id']);
$user_name = "";
$cart_count = 0;

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    
    // Lấy tổng số lượng sản phẩm trong giỏ hàng từ database
    $stmt = $conn->prepare("SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $cart_count = $result['total_items'] ?? 0;
    $stmt->close();
    
    // Lấy tên người dùng
    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['username'];
    }
    $stmt->close();
}
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold text-success" href="index.php">
                🍏 Fruit For Health
            </a>

            <!-- Nút menu trên mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu chính -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Sản Phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog Dinh Dưỡng</a></li>
                    <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz Sức Khỏe</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Liên Hệ</a></li>
                </ul>

                <!-- Giỏ hàng, Đơn hàng & Đăng nhập/Đăng xuất -->
                <div class="d-flex align-items-center ms-3">
                    <a href="cart.php" class="btn btn-outline-success me-2 position-relative">
                        🛒 Giỏ Hàng 
                        <?php if ($cart_count > 0): ?>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <?php if ($is_logged_in): ?>
                        <a href="order_history.php" class="btn btn-warning me-2">📦 Đơn hàng của bạn</a>
                        <span class="me-2 fw-bold">👤 <?php echo htmlspecialchars($user_name); ?></span>
                        <a href="auth/logout.php" class="btn btn-danger">Đăng Xuất</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-success">Đăng Nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
