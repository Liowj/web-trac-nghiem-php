<?php
// SỬA CÂU SQL CŨ THÀNH CÂU SQL DƯỚI ĐÂY:
$stmt = $conn->query("SELECT e.*, COUNT(q.id) AS total_questions 
                      FROM exams e 
                      LEFT JOIN questions q ON e.id = q.exam_id 
                      GROUP BY e.id 
                      ORDER BY e.id DESC");
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
                            
                            <p class="card-text text-danger mb-1">
                                <strong>⏳ Thời gian làm:</strong> <?php echo $exam['duration']; ?> phút
                            </p>
                            
                            <p class="card-text text-primary mb-4">
                                <strong>📝 Số câu hỏi:</strong> <?php echo $exam['total_questions']; ?> câu
                            </p>
                            
                            <a href="index.php?page=exam_info&id=<?php echo $exam['id']; ?>" class="btn btn-primary mt-auto w-100 fw-bold">
                                🔍 Xem thông tin & Vào thi
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
