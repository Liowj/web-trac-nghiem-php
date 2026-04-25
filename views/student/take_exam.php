<?php
// Lấy ID kỳ thi từ URL
$exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin kỳ thi
$stmt = $conn->prepare("SELECT * FROM exams WHERE id = :id");
$stmt->execute(['id' => $exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("<div class='container mt-5'><h3 class='text-danger text-center'>Kỳ thi không tồn tại!</h3></div>");
}

// Lấy danh sách câu hỏi của kỳ thi này
$stmt = $conn->prepare("SELECT * FROM questions WHERE exam_id = :exam_id ORDER BY id ASC");
$stmt->execute(['exam_id' => $exam_id]);
$questions = $stmt->fetchAll();
?>

<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-primary mb-4 sticky-top" style="top: 10px; z-index: 1000;">
            <div class="card-body text-center bg-light">
                <h3 class="text-primary fw-bold"><?php echo htmlspecialchars($exam['title']); ?></h3>
                <h4 class="text-danger fw-bold mb-0">
                    ⏳ Thời gian còn lại: <span id="timer" class="badge bg-danger fs-4 text-white">Đang tải...</span>
                </h4>
            </div>
        </div>

        <form action="index.php?page=submit_exam" method="POST" id="examForm">
            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
            
            <?php 
            $count = 1;
            foreach ($questions as $q): 
            ?>
            <div class="card mb-4 shadow-sm border-0 bg-white">
                <div class="card-header bg-white border-bottom-0 fw-bold fs-5 pt-3">
                    Câu <?php echo $count++; ?>: <span class="text-dark"><?php echo htmlspecialchars($q['question_text']); ?></span>
                </div>
                <div class="card-body pt-1">
                    
                    <?php if ($q['question_type'] == 'trac_nghiem' || $q['question_type'] == 'dung_sai'): ?>
                        <?php if(!empty($q['option_a'])): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input border-secondary" type="radio" name="answers[<?php echo $q['id']; ?>]" value="A" id="q<?php echo $q['id']; ?>_A">
                            <label class="form-check-label" style="cursor: pointer;" for="q<?php echo $q['id']; ?>_A"><strong>A.</strong> <?php echo htmlspecialchars($q['option_a']); ?></label>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($q['option_b'])): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input border-secondary" type="radio" name="answers[<?php echo $q['id']; ?>]" value="B" id="q<?php echo $q['id']; ?>_B">
                            <label class="form-check-label" style="cursor: pointer;" for="q<?php echo $q['id']; ?>_B"><strong>B.</strong> <?php echo htmlspecialchars($q['option_b']); ?></label>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($q['option_c'])): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input border-secondary" type="radio" name="answers[<?php echo $q['id']; ?>]" value="C" id="q<?php echo $q['id']; ?>_C">
                            <label class="form-check-label" style="cursor: pointer;" for="q<?php echo $q['id']; ?>_C"><strong>C.</strong> <?php echo htmlspecialchars($q['option_c']); ?></label>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($q['option_d'])): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input border-secondary" type="radio" name="answers[<?php echo $q['id']; ?>]" value="D" id="q<?php echo $q['id']; ?>_D">
                            <label class="form-check-label" style="cursor: pointer;" for="q<?php echo $q['id']; ?>_D"><strong>D.</strong> <?php echo htmlspecialchars($q['option_d']); ?></label>
                        </div>
                        <?php endif; ?>
                        
                    <?php elseif ($q['question_type'] == 'dien_khuyet'): ?>
                        <input type="text" name="answers[<?php echo $q['id']; ?>]" class="form-control" placeholder="Nhập câu trả lời của bạn vào đây...">
                        
                    <?php elseif ($q['question_type'] == 'noi_tu'): ?>
                        <textarea name="answers[<?php echo $q['id']; ?>]" class="form-control" rows="2" placeholder="Ví dụ: 1-A, 2-B..."></textarea>
                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="text-center mb-5">
                <button type="submit" class="btn btn-success btn-lg fw-bold px-5 shadow" onclick="return confirm('Bạn có chắc chắn muốn nộp bài không?');">✅ Nộp Bài</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Lấy thời gian làm bài (phút) từ PHP và đổi ra Giây
    let timeLimitInMinutes = <?php echo (int)$exam['duration']; ?>;
    let timeLeftInSeconds = timeLimitInMinutes * 60;

    let timerDisplay = document.getElementById('timer');
    let examForm = document.getElementById('examForm');

    // Hàm đếm ngược chạy mỗi 1 giây (1000 milliseconds)
    let countdown = setInterval(function() {
        if (timeLeftInSeconds <= 0) {
            // Hết giờ: Dừng đếm ngược, hiển thị 00:00, thông báo và tự động nộp bài
            clearInterval(countdown);
            timerDisplay.innerHTML = "00:00";
            alert("⏳ Đã hết thời gian làm bài! Hệ thống sẽ tự động nộp bài của bạn.");
            examForm.submit(); // Lệnh tự động submit form
        } else {
            // Còn giờ: Tính toán số phút và số giây còn lại
            let minutes = Math.floor(timeLeftInSeconds / 60);
            let seconds = timeLeftInSeconds % 60;

            // Thêm số 0 đằng trước nếu nhỏ hơn 10 (vd: 09:05)
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            // Hiển thị ra màn hình
            timerDisplay.innerHTML = minutes + ":" + seconds;
            
            // Trừ đi 1 giây
            timeLeftInSeconds--;
        }
    }, 1000);
</script>