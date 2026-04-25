<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between">
            <h4 class="mb-0">Kỳ thi: Lập trình Mã nguồn mở</h4>
            <h4 class="mb-0">⏱ Thời gian còn lại: <span id="timer">45:00</span></h4>
        </div>
        <div class="card-body">
            <form action="#" method="POST">
                
                <div class="mb-4">
                    <h5>Câu 1 (Trắc nghiệm): PHP là viết tắt của từ gì?</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1a" value="A">
                        <label class="form-check-label" for="q1a">A. Personal Home Page</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1b" value="B">
                        <label class="form-check-label" for="q1b">B. PHP: Hypertext Preprocessor</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="q1" id="q1c" value="C">
                        <label class="form-check-label" for="q1c">C. Private Hosting Platform</label>
                    </div>
                </div>
                <hr>

                <div class="mb-4">
                    <h5>Câu 2 (Đúng/Sai): HTML là một ngôn ngữ lập trình.</h5>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2t" value="True">
                        <label class="form-check-label text-success fw-bold" for="q2t">Đúng</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2f" value="False">
                        <label class="form-check-label text-danger fw-bold" for="q2f">Sai</label>
                    </div>
                </div>
                <hr>

                <div class="mb-4">
                    <h5>Câu 3 (Điền khuyết): Điền từ thích hợp vào chỗ trống.</h5>
                    <p class="fs-5">
                        Hệ quản trị cơ sở dữ liệu thường được sử dụng chung với PHP trong XAMPP là 
                        <input type="text" class="form-control d-inline-block w-auto mx-2" name="q3_blank1" placeholder="Nhập từ..."> 
                        được phát triển bởi Oracle.
                    </p>
                </div>
                <hr>

                <div class="mb-4">
                    <h5>Câu 4 (Nối từ): Nối các khái niệm ở cột A với định nghĩa ở cột B.</h5>
                    <div class="row align-items-center mb-2">
                        <div class="col-md-3 fw-bold">1. Frontend</div>
                        <div class="col-md-1 text-center">👉</div>
                        <div class="col-md-5">
                            <select class="form-select" name="q4_match1">
                                <option value="">-- Chọn đáp án tương ứng --</option>
                                <option value="A">A. Lưu trữ dữ liệu</option>
                                <option value="B">B. Giao diện người dùng</option>
                                <option value="C">C. Xử lý logic máy chủ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center mb-2">
                        <div class="col-md-3 fw-bold">2. MySQL</div>
                        <div class="col-md-1 text-center">👉</div>
                        <div class="col-md-5">
                            <select class="form-select" name="q4_match2">
                                <option value="">-- Chọn đáp án tương ứng --</option>
                                <option value="A">A. Lưu trữ dữ liệu</option>
                                <option value="B">B. Giao diện người dùng</option>
                                <option value="C">C. Xử lý logic máy chủ</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Nộp Bài Trắc Nghiệm</button>
                </div>
            </form>
        </div>
    </div>
</div>