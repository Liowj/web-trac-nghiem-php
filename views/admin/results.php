<?php
// Truy vấn lấy dữ liệu điểm thi, kết nối bảng results với users và exams
$sql = "SELECT r.*, u.fullname, e.title 
        FROM results r 
        JOIN users u ON r.user_id = u.id 
        JOIN exams e ON r.exam_id = e.id 
        ORDER BY r.completed_at DESC";
$stmt = $conn->query($sql);
$results = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h2 class="text-primary mb-4">📊 Bảng Điểm Sinh Viên</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>STT</th>
                        <th>Tên Sinh Viên</th>
                        <th>Kỳ Thi</th>
                        <th>Điểm Số (Hệ 10)</th>
                        <th>Số Câu Đúng</th>
                        <th>Thời Gian Nộp Bài</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($results) > 0): ?>
                        <?php $stt = 1; foreach ($results as $row): ?>
                            <tr>
                                <td class="text-center"><?php echo $stt++; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="text-center text-danger fw-bold fs-5"><?php echo $row['score']; ?></td>
                                <td class="text-center"><?php echo $row['correct_count']; ?> / <?php echo $row['total_questions']; ?></td>
                                <td class="text-center text-muted"><?php echo date('d/m/Y H:i:s', strtotime($row['completed_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có sinh viên nào hoàn thành bài thi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <a href="index.php?page=admin" class="btn btn-secondary mt-3">⬅ Quay lại Bảng điều khiển</a>
</div>