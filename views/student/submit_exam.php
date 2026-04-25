<?php
// Ngăn chặn việc truy cập thẳng vào trang này mà không qua form nộp bài
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<div class='container mt-5'><h3 class='text-danger text-center'>Yêu cầu không hợp lệ!</h3></div>");
}

$exam_id = isset($_POST['exam_id']) ? (int)$_POST['exam_id'] : 0;
// Lấy mảng đáp án sinh viên gửi lên (format: answers[ID_Câu_Hỏi] = 'Đáp án chọn')
$student_answers = isset($_POST['answers']) ? $_POST['answers'] : [];

// 1. Lấy tất cả câu hỏi của kỳ thi này từ Database để lấy đáp án chuẩn
$stmt = $conn->prepare("SELECT id, correct_answer FROM questions WHERE exam_id = :exam_id");
$stmt->execute(['exam_id' => $exam_id]);
$questions = $stmt->fetchAll();

$total_questions = count($questions);
$correct_count = 0;

// 2. Chấm điểm: Vòng lặp đối chiếu đáp án của Sinh viên với đáp án của Database
foreach ($questions as $q) {
    $q_id = $q['id'];
    $correct_ans = trim($q['correct_answer']);
    
    // Nếu sinh viên có chọn đáp án cho câu hỏi này
    if (isset($student_answers[$q_id])) {
        $student_ans = trim($student_answers[$q_id]);
        
        // So sánh 2 đáp án (strcasecmp giúp so sánh không phân biệt hoa/thường: a = A)
        if (strcasecmp($student_ans, $correct_ans) == 0) {
            $correct_count++;
        }
    }
}

// 3. Tính điểm theo thang điểm 10
$score = $total_questions > 0 ? round(($correct_count / $total_questions) * 10, 2) : 0;
// 4. Lưu kết quả vào Database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO results (user_id, exam_id, score, correct_count, total_questions) 
                        VALUES (:user_id, :exam_id, :score, :correct_count, :total_questions)");
$stmt->execute([
    'user_id' => $user_id,
    'exam_id' => $exam_id,
    'score' => $score,
    'correct_count' => $correct_count,
    'total_questions' => $total_questions
]);
?>

<div class="container mt-5 text-center">
    <div class="card shadow border-success mx-auto" style="max-width: 500px;">
        <div class="card-header bg-success text-white py-3">
            <h3 class="mb-0">🎉 KẾT QUẢ BÀI THI</h3>
        </div>
        <div class="card-body py-5">
            <h1 class="display-1 text-success fw-bold mb-3"><?php echo $score; ?></h1>
            <p class="text-muted fs-5 mb-4">Điểm Hệ 10</p>
            
            <hr class="w-50 mx-auto">
            
            <p class="fs-5 mt-4">Bạn đã trả lời đúng:</p>
            <h3 class="text-primary"><strong><?php echo $correct_count; ?> / <?php echo $total_questions; ?></strong> câu hỏi</h3>
            
            <a href="index.php?page=student_exams" class="btn btn-outline-success btn-lg mt-4 w-100 fw-bold">
                Quay lại danh sách kỳ thi
            </a>
        </div>
    </div>
</div>