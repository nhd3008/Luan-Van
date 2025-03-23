<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['quiz'] = $_POST;
}

include 'includes/header.php';
include 'database/db_connect.php'; // MySQLi $conn

$quiz = $_SESSION['quiz'] ?? [];
$goal = $quiz['goal'] ?? '';
$taste = $quiz['taste'] ?? '';
$time = $quiz['time'] ?? '';
$allergy = $quiz['allergy'] ?? '';

// Hàm gọi Gemini API
function get_ai_suggestion($goal, $taste, $time, $allergy) {
    $api_key = "AIzaSyC16-nPbuy_GwVCiSv1PZ3cj3D9Qi-mv6k";

    $prompt = "Tôi muốn ăn trái cây để $goal.";
    $prompt .= $taste ? " Tôi thích vị $taste." : '';
    $prompt .= $time ? " Tôi thường ăn vào buổi $time." : '';
    $prompt .= $allergy ? " Tôi bị dị ứng với $allergy." : '';
    $prompt .= " Hãy gợi ý những loại trái cây phù hợp, bằng tiếng Việt, dưới dạng danh sách.";

    $data = [
        'contents' => [[
            'parts' => [[ 'text' => $prompt ]]
        ]]
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
        return "❌ Lỗi Gemini API ($http_status): " . ($result['error']['message'] ?? 'Không rõ lỗi') . "\n\n" . $response;
    }

    return $result['candidates'][0]['content']['parts'][0]['text'];
}

// Gọi AI
$suggestions = get_ai_suggestion($goal, $taste, $time, $allergy);

// 👉 Tự động lấy danh sách tên trái cây từ bảng products
$known_fruits = [];
$res = $conn->query("SELECT name FROM products");
while ($row = $res->fetch_assoc()) {
    $known_fruits[] = $row['name'];
}

// 👉 So khớp tên trái cây xuất hiện trong kết quả AI
$fruit_names = [];
foreach ($known_fruits as $fruit) {
    if (stripos($suggestions, $fruit) !== false) {
        $fruit_names[] = $fruit;
    }
}
$fruit_names = array_unique($fruit_names);

// 👉 Truy vấn CSDL để lấy sản phẩm phù hợp
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

<div class="container py-5">
  <h2 class="text-center mb-4">Gợi ý từ chuyên gia AI 🍉</h2>

  <div class="alert alert-info">
    <strong>Thông tin bạn đã chọn:</strong><br>
    - Mục tiêu: <?= htmlspecialchars($goal) ?><br>
    - Vị yêu thích: <?= htmlspecialchars($taste) ?><br>
    - Thời điểm ăn: <?= htmlspecialchars($time) ?><br>
    - Dị ứng với: <?= htmlspecialchars($allergy ?: 'Không') ?>
  </div>

  <div class="card p-4 mb-4">
    <h5>✅ Gợi ý trái cây từ AI:</h5>
    <pre><?= htmlspecialchars($suggestions) ?></pre>
  </div>

  <?php if (!empty($fruit_names)): ?>
    <div class="card p-3 mb-4 bg-light">
      <h6 class="mb-2">🔍 Từ khóa được phát hiện trong gợi ý:</h6>
      <ul class="mb-0">
        <?php foreach ($fruit_names as $keyword): ?>
          <li><?= htmlspecialchars($keyword) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (!empty($matched_products)): ?>
    <h5 class="mb-3">🍎 Các sản phẩm phù hợp trong cửa hàng:</h5>
    <div class="row">
      <?php foreach ($matched_products as $product): ?>
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="<?= $product['image_url'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $product['name'] ?></h5>
              <p class="card-text">Giá: <?= number_format($product['price']) ?> VNĐ</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">❌ Không tìm thấy sản phẩm nào phù hợp với gợi ý.</div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
