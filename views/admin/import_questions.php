<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

$exam_id = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $exam_id_post = $_POST['exam_id'];
    
    if ($file) {
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(); 
            
            unset($rows[0]); // Bỏ qua dòng tiêu đề
            
            $count = 0;
            $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_type, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($rows as $row) {
                $type = trim($row[0] ?? '');
                $text = trim($row[1] ?? '');
                
                if (!empty($text)) {
                    $opt_a = trim($row[2] ?? '');
                    $opt_b = trim($row[3] ?? '');
                    $opt_c = trim($row[4] ?? '');
                    $opt_d = trim($row[5] ?? '');
                    $correct = trim($row[6] ?? '');
                    
                    $stmt->execute([$exam_id_post, $type, $text, $opt_a, $opt_b, $opt_c, $opt_d, $correct]);
                    $count++;
                }
            }
            echo "<script>
                    alert('🎉 Import thành công $count câu hỏi vào hệ thống!');
                    window.location.href = 'index.php?page=admin_questions&exam_id=$exam_id_post';
                  </script>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Lỗi đọc file: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="container mt-5">
    <div class="card shadow border-primary">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📥 Import Câu Hỏi Bằng Excel</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Cấu trúc file Excel chuẩn:</strong><br>
                * Cột A: Loại câu hỏi (trac_nghiem, dung_sai, dien_khuyet)<br>
                * Cột B: Nội dung câu hỏi<br>
                * Cột C, D, E, F: Lần lượt là Đáp án A, B, C, D<br>
                * Cột G: Đáp án đúng (Ghi A, B, C, D hoặc nội dung đáp án tự luận)<br>
                <em>Lưu ý: Dòng 1 để làm tiêu đề, nạp dữ liệu từ dòng 2.</em>
            </div>
            
            <form action="index.php?page=admin_import_questions" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Chọn file Excel (.xlsx)</label>
                    <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls" required>
                </div>
                <button type="submit" class="btn btn-success fw-bold">🚀 Bắt Đầu Import</button>
            </form>
        </div>
    </div>
</div>      