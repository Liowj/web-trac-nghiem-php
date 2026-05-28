<?php
$exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Lấy thông tin chi tiết cấu hình đề thi
$stmt = $conn->prepare("SELECT * FROM exams WHERE id = :id");
$stmt->execute(['id' => $exam_id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    die("<h3 class='text-danger text-center mt-5'>Kỳ thi không tồn tại!</h3>");
}

//Truy vấn đếm tổng số lượng câu hỏi của kỳ thi này
$q_stmt = $conn->prepare("SELECT COUNT(*) FROM questions WHERE exam_id = :exam_id");
$q_stmt->execute(['exam_id' => $exam_id]);
$total_questions = $q_stmt->fetchColumn();

// Tính số lần sinh viên này đã hoàn thành bài thi này trong quá khứ
$count_stmt = $conn->prepare("SELECT COUNT(*) FROM results WHERE user_id = :user_id AND exam_id = :exam_id");
$count_stmt->execute(['user_id' => $user_id, 'exam_id' => $exam_id]);
$attempts_done = $count_stmt->fetchColumn();

$now = time();
$start_time = strtotime($exam['start_time']);
$end_time = strtotime($exam['end_time']);

$is_early = $now < $start_time; // Chưa tới giờ thi
$is_late = $now > $end_time;    // Quá hạn thi
$out_of_attempts = $attempts_done >= $exam['max_attempts']; // Hết lượt làm bài
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">📋 QUY CHẾ & THÔNG TIN BÀI THI</h4>
                </div>
                <div class="card-body p-4">
                    <h3 class="text-center text-dark fw-bold mb-4"><?= htmlspecialchars($exam['title']) ?></h3>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light border rounded">
                                <strong>⏳ Thời gian làm bài:</strong> <span class="text-primary fw-bold"><?= $exam['duration'] ?> phút</span>
                            </div>
                        </div>
                        
<p><strong>📝 Số lượng câu hỏi:</strong> <span class="badge bg-primary fs-6"><?= $total_questions ?> câu</span></p>
                        <div class="col-md-6">
                            <div class="p-3 bg-light border rounded">
                                <strong>🎯 Số lượt thi cho phép:</strong> <span class="text-danger fw-bold"><?= $exam['max_attempts'] ?> lần</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="p-3 bg-light border rounded">
                                📅 <strong>Khung giờ diễn ra đề thi:</strong> <br>
                                - Bắt đầu từ: <span class="text-success fw-bold"><?= date('d/m/Y H:i:s', $start_time) ?></span> <br>
                                - Kết thúc lúc: <span class="text-danger fw-bold"><?= date('d/m/Y H:i:s', $end_time) ?></span>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <span class="badge bg-secondary p-2 fs-6">Bạn đã làm bài thi này: <b class="text-warning"><?= $attempts_done ?></b> / <?= $exam['max_attempts'] ?> lần</span>
                        </div>
                    </div>

                    <?php if ($out_of_attempts): ?>
                        <div class="alert alert-danger fw-bold text-center">❌ Bạn đã hết số lượt làm bài cho phép đối với đề thi này!</div>
                    <?php elseif ($is_early): ?>
                        <div class="alert alert-warning fw-bold text-center">⏳ Chưa đến thời gian mở đề thi. Xin vui lòng quay lại sau!</div>
                    <?php elseif ($is_late): ?>
                        <div class="alert alert-danger fw-bold text-center">🚫 Đề thi này đã đóng vào lúc <?= date('H:i d/m/Y', $end_time) ?>. Bạn không thể tham gia nữa!</div>
                    <?php else: ?>
                        <div class="alert alert-success fw-bold text-center">✅ Đề thi đang mở. Hệ thống đã sẵn sàng!</div>
                        <div class="text-center mt-3">
                            <a href="index.php?page=take_exam&id=<?= $exam_id ?>" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow">🚀 BẮT ĐẦU LÀM BÀI</a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-3">
                        <a href="index.php?page=student_exams" class="btn btn-outline-secondary">⬅ Quay lại danh sách đề thi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>