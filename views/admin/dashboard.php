<div class="row mt-4">
    <div class="col-md-3">
        <div class="list-group shadow-sm">
            <a href="index.php?page=admin" class="list-group-item list-group-item-action active">Bảng điều khiển</a>
            <a href="index.php?page=admin_exams" class="list-group-item list-group-item-action">📝 Quản lý Kỳ thi</a>
            <a href="index.php?page=admin_results" class="btn btn-info btn-lg m-2 text-white fw-bold">📊 Xem Bảng Điểm</a>
            <a href="#" class="list-group-item list-group-item-action">❓ Quản lý Câu hỏi</a>
            <a href="#" class="list-group-item list-group-item-action">📊 Xem Kết quả</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Khu vực Quản trị Hệ thống</h4>
            </div>
            <div class="card-body">
                <h5 class="text-primary">Xin chào Quản trị viên: <?php echo $_SESSION['fullname']; ?></h5>
                <p>Tại đây, bạn có thể tạo các kỳ thi mới, cấu hình 4 loại câu hỏi (Trắc nghiệm, Đúng/Sai, Điền khuyết, Nối từ) và theo dõi điểm số của sinh viên.</p>
                <hr>
                <div class="alert alert-info">
                    <strong>Gợi ý:</strong> Hãy bắt đầu bằng việc tạo một Kỳ thi mới ở menu bên trái!
                </div>
            </div>
        </div>
    </div>
</div>