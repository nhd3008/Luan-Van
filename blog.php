<?php
require_once __DIR__ . '/includes/header.php';

// Tạm thời dùng mảng giả lập bài viết, sau này có thể lấy từ database
$blogs = [
    [
        'title' => 'Lợi ích tuyệt vời của trái cây giàu vitamin C',
        'image' => 'uploads/blog1.jpg',
        'excerpt' => 'Vitamin C không chỉ tăng sức đề kháng mà còn làm đẹp da, giúp hấp thụ sắt tốt hơn.',
        'link' => 'blog_detail.php?id=1'
    ],
    [
        'title' => 'Ăn trái cây đúng thời điểm để hấp thụ tối ưu',
        'image' => 'uploads/blog2.jpg',
        'excerpt' => 'Không phải lúc nào ăn trái cây cũng tốt. Hãy xem thời điểm nào giúp cơ thể hấp thu tốt nhất.',
        'link' => 'blog_detail.php?id=2'
    ],
    [
        'title' => 'Top 5 loại trái cây hỗ trợ giảm cân hiệu quả',
        'image' => 'uploads/blog3.jpg',
        'excerpt' => 'Bí quyết giữ dáng nhờ trái cây lành mạnh, ít calo nhưng giàu dinh dưỡng.',
        'link' => 'blog_detail.php?id=3'
    ]
];
?>

<div class="container my-5">
    <h1 class="text-center text-success fw-bold mb-4">📚 Blog Dinh Dưỡng</h1>
    <div class="row">
        <?php foreach ($blogs as $blog): ?>
            <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm blog-card">
                    <img src="<?php echo $blog['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title text-success fw-bold"><?php echo htmlspecialchars($blog['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                        <a href="<?php echo $blog['link']; ?>" class="btn btn-outline-primary btn-sm">Đọc thêm</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
