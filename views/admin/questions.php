<?php
// Lấy ID kỳ thi từ URL và truy vấn tên kỳ thi để hiển thị cho đẹp
$exam_id = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;
$stmt = $conn->prepare("SELECT title FROM exams WHERE id = :id");
$stmt->execute(['id' => $exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("<h3 class='text-danger text-center mt-5'>Kỳ thi không tồn tại!</h3>");
}
?>

<div class="row mt-4">
    <div class="col-md-12">
        <h4>Quản lý câu hỏi cho kỳ thi: <span class="text-primary"><?php echo $exam['title']; ?></span></h4>
        <a href="index.php?page=admin_exams" class="btn btn-secondary mb-3">⬅ Quay lại danh sách Kỳ thi</a>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Thêm Câu Hỏi Mới</h5>
            </div>
            <div class="card-body">
                <form action="index.php?page=process_add_question" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    
                    <div class="mb-3">
                        <label class="fw-bold">Loại câu hỏi:</label>
                        <select name="question_type" class="form-select border-warning" required>
                            <option value="trac_nghiem">1. Trắc nghiệm (4 đáp án A, B, C, D)</option>
                            <option value="dung_sai">2. Đúng / Sai (Chỉ nhập đáp án A và B)</option>
                            <option value="dien_khuyet">3. Điền khuyết (Bỏ trống các đáp án, chỉ nhập câu trả lời đúng)</option>
                            <option value="noi_tu">4. Nối từ (Nhập các cặp từ vào các ô đáp án)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Nội dung câu hỏi:</label>
                        <textarea name="question_text" class="form-control" rows="3" required placeholder="Nhập nội dung câu hỏi..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Đáp án A (hoặc "Đúng"):</label>
                            <input type="text" name="option_a" class="form-control" placeholder="Nhập nội dung đáp án...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Đáp án B (hoặc "Sai"):</label>
                            <input type="text" name="option_b" class="form-control" placeholder="Nhập nội dung đáp án...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Đáp án C:</label>
                            <input type="text" name="option_c" class="form-control" placeholder="Nhập nội dung đáp án...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Đáp án D:</label>
                            <input type="text" name="option_d" class="form-control" placeholder="Nhập nội dung đáp án...">
                        </div>
                    </div>
                    
                    <div class="mb-3 p-3 bg-light border rounded">
                        <label class="fw-bold text-danger">Đáp án Đúng (Bắt buộc để chấm điểm):</label>
                        <input type="text" name="correct_answer" class="form-control border-danger" required placeholder="Ví dụ: A, B, True, False, hoặc từ cần điền...">
                        <small class="text-muted">Hệ thống sẽ dùng giá trị này để so sánh với bài làm của sinh viên.</small>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-warning fw-bold">
                            💾 Lưu Câu Hỏi
                        </button>
                        <a href="index.php?page=admin_import_questions&exam_id=<?php echo $exam_id; ?>" class="btn btn-success fw-bold">
                            📥 Import từ file Excel
                        </a>
                        </div>

                </form>
            </div>
            
        </div>
        
    </div>
</div>