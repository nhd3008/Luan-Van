<?php
// quiz_result.php - Gợi ý trái cây theo kết quả quiz với tích hợp AI
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['quiz'] = $_POST;
}

include 'includes/header.php';

$quiz = $_SESSION['quiz'] ?? [];
$goal = $quiz['goal'] ?? '';
$taste = $quiz['taste'] ?? '';
$time = $quiz['time'] ?? '';
$allergy = $quiz['allergy'] ?? '';

function get_ai_suggestion($goal, $taste, $time, $allergy) {
    $api_key = ; // Thay bằng API key thật

    $prompt = "Tôi muốn ăn trái cây để $goal.";
    $prompt .= $taste ? " Tôi thích vị $taste." : '';
    $prompt .= $time ? " Tôi thường ăn vào buổi $time." : '';
    $prompt .= $allergy ? " Tôi bị dị ứng với $allergy." : '';
    $prompt .= " Hãy gợi ý những loại trái cây phù hợp, bằng tiếng Việt, dưới dạng danh sách.";

    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => 0.7
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_status !== 200 || !isset($result['choices'][0]['message']['content'])) {
        return "❌ Lỗi API ($http_status): " . ($result['error']['message'] ?? 'Không rõ lỗi') . "\n\nPhản hồi từ server:\n" . $response;
    }

    return $result['choices'][0]['message']['content'];
}

$suggestions = get_ai_suggestion($goal, $taste, $time, $allergy);
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

  <div class="card p-4">
    <h5>✅ Gợi ý trái cây từ AI:</h5>
    <pre><?= htmlspecialchars($suggestions) ?></pre>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
