<div class="row mt-4">
    <div class="col-md-3">
        <div class="list-group shadow-sm">
            <a href="index.php?page=admin" class="list-group-item list-group-item-action">Bảng điều khiển</a>
            <a href="index.php?page=admin_exams" class="list-group-item list-group-item-action active">📝 Quản lý Kỳ thi</a>
            <a href="index.php?page=admin_logs" class="list-group-item list-group-item-action text-danger fw-bold">📜 Lịch sử thêm đề</a>
            <a href="index.php?page=admin_results" class="list-group-item list-group-item-action">📊 Xem Kết quả</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Thêm Kỳ Thi Mới</h5>
            </div>
            <div class="card-body">
                <form action="index.php?page=process_add_exam" method="POST" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Tên kỳ thi</label>
                        <input type="text" name="title" class="form-control" required placeholder="Ví dụ: Lập trình mã nguồn mở">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Thời gian (Phút)</label>
                        <input type="number" name="duration" class="form-control" required placeholder="Ví dụ: 45">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-danger">Số lần làm bài tối đa</label>
                        <input type="number" name="max_attempts" class="form-control border-danger" value="1" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-primary">Thời gian mở đề</label>
                        <input type="datetime-local" name="start_time" class="form-control border-primary" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-primary">Thời gian đóng đề</label>
                        <input type="datetime-local" name="end_time" class="form-control border-primary" required>
                    </div>
                    
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success fw-bold">💾 Lưu Kỳ Thi</button>
                    </div>
                </form>
            </div>
        </div>

        <h5 class="text-primary">Danh sách Kỳ thi hiện có</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm text-center align-middle small">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên kỳ thi</th>
                        <th>Thời gian làm</th>
                        <th>Lượt làm tối đa</th>
                        <th>Khung thời gian diễn ra đề thi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM exams ORDER BY id DESC");
                    while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td class="text-start fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['duration']; ?> phút</td>
                        <td class="fw-bold text-danger"><?php echo $row['max_attempts']; ?> lần</td>
                        <td class="text-start text-muted">
                            🔹 Mở: <span class="text-success fw-bold"><?php echo $row['start_time'] ? date('d/m/Y H:i', strtotime($row['start_time'])) : 'Chưa đặt'; ?></span><br>
                            🔸 Đóng: <span class="text-danger fw-bold"><?php echo $row['end_time'] ? date('d/m/Y H:i', strtotime($row['end_time'])) : 'Chưa đặt'; ?></span>
                        </td>
                        <td>
                            <a href="index.php?page=admin_questions&exam_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary mb-1">➕ Thêm câu hỏi</a>
                            <a href="index.php?page=admin_delete_exam&id=<?php echo $row['id']; ?>"
                                class="btn btn-danger btn-sm text-white fw-bold mb-1"
                                onclick="return confirm('⚠️ Bạn có chắc chắn muốn xóa kỳ thi này không? Toàn bộ câu hỏi thuộc kỳ thi này cũng sẽ bị xóa sạch!');">
                                🗑️ Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>