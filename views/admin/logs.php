<?php
// Truy vấn lịch sử từ bảng exam_logs
$stmt = $conn->query("SELECT * FROM exam_logs ORDER BY id DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="list-group shadow-sm">
            <a href="index.php?page=admin" class="list-group-item list-group-item-action">Bảng điều khiển</a>
            <a href="index.php?page=admin_exams" class="list-group-item list-group-item-action">📝 Quản lý Kỳ thi</a>
            <a href="index.php?page=admin_logs" class="list-group-item list-group-item-action active bg-danger border-danger">📜 Lịch sử thêm đề</a>
            <a href="index.php?page=admin_results" class="list-group-item list-group-item-action">📊 Xem Kết quả</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">📜 Nhật Ký Hệ Thống - Lịch Sử Thêm Đề Thi</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 8%;">STT</th>
                            <th style="width: 25%;">Người thực hiện</th>
                            <th>Nội dung hành động</th>
                            <th style="width: 25%;">Thời gian thực hiện</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($logs) > 0): ?>
                            <?php foreach ($logs as $index => $log): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td class="text-start">
                                        <strong><?= htmlspecialchars($log['admin_fullname']) ?></strong> <br>
                                        <small class="text-muted">(ID: <?= $log['admin_id'] ?>)</small>
                                    </td>
                                    <td class="text-start">
                                        <span class="badge bg-success"><?= htmlspecialchars($log['action']) ?></span>: 
                                        <span class="fw-bold text-primary">"<?= htmlspecialchars($log['exam_title']) ?>"</span>
                                        <small class="text-muted">(Mã đề: <?= $log['exam_id'] ?? 'Đã xóa' ?>)</small>
                                    </td>
                                    <td class="text-muted"><?= date('d/m/Y - H:i:s', strtotime($log['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Chưa ghi nhận lịch sử tạo đề nào trong hệ thống!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>