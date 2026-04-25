<?php
// Lấy danh sách tất cả các kỳ thi từ Database
$stmt = $conn->query("SELECT * FROM exams ORDER BY id DESC");
$exams = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Danh Sách Kỳ Thi</h2>
    
    <?php if (count($exams) > 0): ?>
        <div class="row">
            <?php foreach ($exams as $exam): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-primary border-top border-3">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title text-dark fw-bold mb-3"><?php echo htmlspecialchars($exam['title']); ?></h5>
                            <p class="card-text text-danger mb-4">
                                <strong>⏳ Thời gian:</strong> <?php echo $exam['duration']; ?> phút
                            </p>
                            <a href="index.php?page=take_exam&id=<?php echo $exam['id']; ?>" class="btn btn-success mt-auto w-100 fw-bold">
                                🚀 Bắt đầu làm bài
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center shadow-sm">
            <h5>Chưa có kỳ thi nào diễn ra!</h5>
            <p class="mb-0">Vui lòng quay lại sau khi giảng viên cập nhật đề thi.</p>
        </div>
    <?php endif; ?>
</div>