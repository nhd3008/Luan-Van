<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['quiz'] = $_POST;
}
include 'database/db_connect.php'; // MySQLi $conn

$quiz = $_SESSION['quiz'] ?? [];
$goal = $quiz['goal'] ?? '';
$taste = $quiz['taste'] ?? '';
$time = $quiz['time'] ?? '';
$allergy = $quiz['allergy'] ?? '';

function get_ai_suggestion($goal, $taste, $time, $allergy) {
    $api_key = "AIzaSyC16-nPbuy_GwVCiSv1PZ3cj3D9Qi-mv6k";
    $prompt = "Tôi muốn ăn trái cây để $goal.";
    $prompt .= $taste ? " Tôi thích vị $taste." : '';
    $prompt .= $time ? " Tôi thường ăn vào buổi $time." : '';
    $prompt .= $allergy ? " Tôi bị dị ứng với $allergy." : '';
    $prompt .= " Hãy gợi ý những loại trái cây phù hợp, bằng tiếng Việt, dưới dạng danh sách.";

    $data = [
        'contents' => [[ 'parts' => [[ 'text' => $prompt ]] ]]
    ];

    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=$api_key";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);
    if ($http_status !== 200 || !isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return "❌ Lỗi Gemini API ($http_status): " . ($result['error']['message'] ?? 'Không rõ lỗi');
    }
    return $result['candidates'][0]['content']['parts'][0]['text'];
}

$suggestions = get_ai_suggestion($goal, $taste, $time, $allergy);

// Trích xuất tên trái cây
$known_fruits = [];
$res = $conn->query("SELECT name FROM products");
while ($row = $res->fetch_assoc()) {
    $known_fruits[] = $row['name'];
}

$fruit_names = [];
foreach ($known_fruits as $fruit) {
    if (stripos($suggestions, $fruit) !== false) {
        $fruit_names[] = $fruit;
    }
}
$fruit_names = array_unique($fruit_names);

// Truy vấn sản phẩm
$matched_products = [];
if (!empty($fruit_names)) {
    $conditions = array_map(fn($f) => "name LIKE ?", $fruit_names);
    $sql = "SELECT * FROM products WHERE " . implode(" OR ", $conditions);
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $types = str_repeat("s", count($fruit_names));
        $params = array_map(fn($name) => '%' . $name . '%', $fruit_names);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $matched_products = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Gợi ý Quiz | Fruit For Health</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kết quả quiz và gợi ý trái cây từ chuyên gia AI cho bạn">
  <meta name="keywords" content="trái cây, quiz, AI, gợi ý">
  <meta name="author" content="Fruit For Health Team">

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon.png" type="image/png">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/quiz.css">

  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background-color: #f8f9fa;
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
  </style>
</head>
<!-- Thay thế phần bên trong <body> bằng phiên bản cải tiến sau: -->

<body>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
  <h2 class="text-center mb-5 text-success fw-bold">
    🌿 Gợi ý Trái Cây Từ Chuyên Gia AI
  </h2>

  <!-- Thông tin người dùng -->
  <div class="alert alert-info shadow-sm border-start border-5 border-info">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-circle-info me-2"></i>Thông tin bạn đã chọn:</h5>
    <ul class="mb-0 ps-3">
      <li><strong>Mục tiêu:</strong> <?= htmlspecialchars($goal) ?></li>
      <li><strong>Vị yêu thích:</strong> <?= htmlspecialchars($taste) ?></li>
      <li><strong>Thời điểm ăn:</strong> <?= htmlspecialchars($time) ?></li>
      <li><strong>Dị ứng với:</strong> <?= htmlspecialchars($allergy ?: 'Không') ?></li>
    </ul>
  </div>

  <!-- Gợi ý từ AI -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-success text-white fw-semibold">
      <i class="fa-solid fa-robot me-2"></i>Gợi ý trái cây từ AI
    </div>
    <div class="card-body bg-light">
      <pre class="mb-0" style="white-space: pre-wrap; word-break: break-word; font-size: 1rem; line-height: 1.6;">
<?= htmlspecialchars($suggestions) ?>
      </pre>
    </div>
  </div>

  <!-- Từ khóa phát hiện -->
  <?php if (!empty($fruit_names)): ?>
    <div class="alert alert-light border-start border-success border-4 shadow-sm">
      <h6 class="fw-bold text-success"><i class="fa-solid fa-magnifying-glass me-2"></i>Từ khóa trái cây phát hiện:</h6>
      <div class="d-flex flex-wrap gap-2 mt-2">
        <?php foreach ($fruit_names as $keyword): ?>
          <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
            <i class="fa-solid fa-lemon me-1"></i><?= htmlspecialchars($keyword) ?>
          </span>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- Sản phẩm phù hợp -->
  <?php if (!empty($matched_products)): ?>
    <h4 class="fw-bold text-primary mb-4">🍎 Sản phẩm phù hợp trong cửa hàng:</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($matched_products as $product): ?>
        <div class="col">
          <div class="card h-100 border-0 shadow-sm rounded-4">
            <img src="<?= $product['image_url'] ?>" class="card-img-top rounded-top-4" alt="<?= $product['name'] ?>" style="height: 220px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-success"><?= htmlspecialchars($product['name']) ?></h5>

              <p class="card-text text-danger fw-bold mb-1">
                <?= number_format($product['selling_price']) ?> VNĐ
              </p>

              <?php if ($product['discount'] > 0): ?>
                <p class="card-text text-muted mb-2">
                  <del><?= number_format($product['selling_price'] + $product['discount']) ?> VNĐ</del>
                </p>
              <?php endif; ?>

              <?php if ($product['stock_quantity'] > 0): ?>
                <p class="text-success fw-semibold mb-2">
                  ✅ Còn hàng (<?= $product['stock_quantity'] ?> <?= $product['unit'] ?>)
                </p>
              <?php else: ?>
                <p class="text-secondary fw-semibold mb-2">
                  ❌ Hết hàng
                </p>
              <?php endif; ?>

              <div class="mt-auto d-flex justify-content-between align-items-center">
                <a href="product_detail.php?id=<?= $product['product_id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                  <i class="fa fa-eye"></i> Xem
                </a>
                <?php if ($product['stock_quantity'] > 0): ?>
                  <form action="cart.php" method="post" class="mb-0">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <button type="submit" class="btn btn-success btn-sm rounded-pill">
                      <i class="fa fa-cart-plus"></i> Mua
                    </button>
                  </form>
                <?php else: ?>
                  <button class="btn btn-secondary btn-sm rounded-pill" disabled>
                    <i class="fa fa-ban"></i> Hết hàng
                  </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning shadow-sm">
      <i class="fa-solid fa-circle-exclamation me-2"></i>
      Không tìm thấy sản phẩm nào phù hợp với gợi ý từ AI.
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
