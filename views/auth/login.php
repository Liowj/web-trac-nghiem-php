<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Đăng Nhập Hệ Thống</h4>
            </div>
            <div class="card-body p-4">
                <form action="index.php?page=process_login" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Nhập username...">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu...">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Đăng Nhập</button>
                    <div class="text-center">
                        <span>Chưa có tài khoản? </span>
                        <a href="index.php?page=register" class="text-decoration-none">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>