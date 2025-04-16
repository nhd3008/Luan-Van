<?php
session_start();
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quiz Tư vấn Trái cây</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-4">Quiz tư vấn trái cây theo sức khỏe 🍎</h2>
  <form action="quiz_result.php" method="POST">
    <!-- Câu 1 -->
    <div class="mb-4">
      <label class="form-label"><strong>1. Bạn muốn cải thiện điều gì?</strong></label><br>
      <input type="radio" name="goal" value="tăng trí nhớ" required> Tăng trí nhớ 🧠<br>
      <input type="radio" name="goal" value="tăng đề kháng"> Tăng đề kháng 💪<br>
      <input type="radio" name="goal" value="tốt cho tim mạch"> Tốt cho tim mạch ❤️<br>
      <input type="radio" name="goal" value="giảm cân"> Giảm cân 🧘
    </div>

    <!-- Câu 2 -->
    <div class="mb-4">
      <label class="form-label"><strong>2. Bạn thích vị gì?</strong></label><br>
      <input type="radio" name="taste" value="ngọt"> Ngọt<br>
      <input type="radio" name="taste" value="chua"> Chua<br>
      <input type="radio" name="taste" value="nhẹ nhàng"> Nhẹ nhàng
    </div>

    <!-- Câu 3 -->
    <div class="mb-4">
      <label class="form-label"><strong>3. Bạn thường ăn trái cây vào thời điểm nào?</strong></label><br>
      <input type="radio" name="time" value="sáng"> Buổi sáng ☀️<br>
      <input type="radio" name="time" value="trưa"> Buổi trưa ⛅<br>
      <input type="radio" name="time" value="tối"> Buổi tối 🌙
    </div>

    <!-- Câu 4 -->
    <div class="mb-4">
      <label class="form-label"><strong>4. Bạn bị dị ứng với trái cây nào không?</strong></label>
      <input type="text" class="form-control" name="allergy" placeholder="(Ví dụ: dứa, xoài...)">
    </div>

    <!-- Câu 5 -->
    <div class="mb-4">
      <label class="form-label"><strong>5. Bạn đang gặp vấn đề sức khỏe nào?</strong></label>
      <input type="text" class="form-control" name="health_issue" placeholder="VD: thiếu máu, táo bón, huyết áp cao...">
    </div>

    <!-- Câu 6 -->
    <div class="mb-4">
      <label class="form-label"><strong>6. Bạn có chế độ ăn đặc biệt nào không?</strong></label><br>
      <input type="checkbox" name="diet[]" value="ăn chay"> Ăn chay<br>
      <input type="checkbox" name="diet[]" value="keto"> Keto<br>
      <input type="checkbox" name="diet[]" value="low-carb"> Low-carb<br>
      <input type="checkbox" name="diet[]" value="bình thường"> Bình thường
    </div>

    <!-- Câu 7 -->
    <div class="mb-4">
      <label class="form-label"><strong>7. Bạn muốn trái cây hỗ trợ điều gì?</strong></label><br>
      <input type="checkbox" name="support[]" value="ngủ ngon"> Ngủ ngon 😴<br>
      <input type="checkbox" name="support[]" value="giảm stress"> Giảm stress 😌<br>
      <input type="checkbox" name="support[]" value="làm đẹp da"> Làm đẹp da ✨<br>
      <input type="checkbox" name="support[]" value="tăng năng lượng"> Tăng năng lượng ⚡
    </div>

    <!-- Câu 8 -->
    <div class="mb-4">
      <label class="form-label"><strong>8. Bạn có đang mang thai hoặc cho con bú?</strong></label><br>
      <input type="radio" name="pregnant" value="có"> Có<br>
      <input type="radio" name="pregnant" value="không"> Không
    </div>

    <!-- Câu 10 -->
    <div class="mb-4">
      <label class="form-label"><strong>9. Bạn muốn AI lưu ý điều gì khi tư vấn?</strong></label>
      <textarea class="form-control" name="custom_note" rows="3" placeholder="VD: Tôi bị tiểu đường nhẹ, đang ăn kiêng..."></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success px-4 py-2">Nhận gợi ý 🍓</button>
    </div>
  </form>
</div>
</body>
</html>
<?php include 'includes/footer.php'; ?>
