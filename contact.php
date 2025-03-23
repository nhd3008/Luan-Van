<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - Fruit For Health</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

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
            <div class="col-md-6 contact-info">
                <h5>Địa chỉ của chúng tôi</h5>
                <p>132 Đường 3/2 quận Ninh Kiều TP Cần Thơ</p>
                <p><strong>Email:</strong> nguyenhoangdonglm@gmail.com</p>
                <p><strong>Điện thoại:</strong> 0377296369</p>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4660.383548095782!2d105.76804037569762!3d10.029938972517476!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0895a51d60719%3A0x9d76b0035f6d53d0!2zxJDhuqFpIGjhu41jIEPhuqduIFRoxqE!5e1!3m2!1svi!2s!4v1742647472780!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
