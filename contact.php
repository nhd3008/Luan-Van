<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - Fruit For Health</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Fruit For Health</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Giỏ hàng</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Liên hệ</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h2 class="text-center">Liên Hệ Chúng Tôi</h2>
        <p class="text-center">Nếu bạn có bất kỳ câu hỏi nào, hãy liên hệ với chúng tôi bằng cách điền vào biểu mẫu dưới đây.</p>
        
        <div class="row">
            <div class="col-md-6">
                <form action="contact_process.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi Tin Nhắn</button>
                </form>
            </div>
            <div class="col-md-6">
                <h5>Địa chỉ của chúng tôi</h5>
                <p>123 Đường Trái Cây, Quận 1, TP. Hồ Chí Minh</p>
                <p><strong>Email:</strong> support@fruitforhealth.com</p>
                <p><strong>Điện thoại:</strong> 0123 456 789</p>
                <div>
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.668288662956!2d106.70098731474828!3d10.762622692333336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fcf1f26a2d5%3A0xa0b9fdf656f9b60a!2sBen%20Thanh%20Market!5e0!3m2!1sen!2s!4v1623858644623!5m2!1sen!2s" 
                        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-light text-center p-3 mt-4">
        <p>&copy; 2025 Fruit For Health. Mọi quyền được bảo lưu.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
