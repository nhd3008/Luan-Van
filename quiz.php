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
    <div class="mb-4">
      <label class="form-label"><strong>1. Bạn muốn cải thiện điều gì?</strong></label><br>
      <input type="radio" name="goal" value="tăng trí nhớ" required> Tăng trí nhớ 🧠<br>
      <input type="radio" name="goal" value="tăng đề kháng"> Tăng đề kháng 💪<br>
      <input type="radio" name="goal" value="tốt cho tim mạch"> Tốt cho tim mạch ❤️<br>
      <input type="radio" name="goal" value="giảm cân"> Giảm cân 🧘
    </div>

    <div class="mb-4">
      <label class="form-label"><strong>2. Bạn thích vị gì?</strong></label><br>
      <input type="radio" name="taste" value="ngọt"> Ngọt<br>
      <input type="radio" name="taste" value="chua"> Chua<br>
      <input type="radio" name="taste" value="nhẹ nhàng"> Nhẹ nhàng
    </div>

    <div class="mb-4">
      <label class="form-label"><strong>3. Bạn thường ăn trái cây vào thời điểm nào?</strong></label><br>
      <input type="radio" name="time" value="sáng"> Buổi sáng ☀️<br>
      <input type="radio" name="time" value="trưa"> Buổi trưa ⛅<br>
      <input type="radio" name="time" value="tối"> Buổi tối 🌙
    </div>

    <div class="mb-4">
      <label class="form-label"><strong>4. Bạn bị dị ứng với trái cây nào không?</strong></label>
      <input type="text" class="form-control" name="allergy" placeholder="(Ví dụ: dứa, xoài...)">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success px-4 py-2">Nhận gợi ý 🍓</button>
    </div>
  </form>
</div>
</body>
</html>
<?php include 'includes/footer.php'; ?>

<?php