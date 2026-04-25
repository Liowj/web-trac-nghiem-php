<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="font-weight-light my-2 fw-bold">ĐĂNG KÝ TÀI KHOẢN</h3>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?page=process_register" method="POST">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="fullname" name="fullname" type="text" placeholder="Nhập họ và tên..." required />
                            <label for="fullname">Họ và Tên</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="username" name="username" type="text" placeholder="Nhập tên đăng nhập..." required />
                            <label for="username">Tên đăng nhập</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="password" name="password" type="password" placeholder="Nhập mật khẩu..." required />
                            <label for="password">Mật khẩu</label>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Đăng Ký Ngay</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="index.php?page=login" class="text-decoration-none">Đã có tài khoản? Đi đến Đăng nhập</a></div>
                </div>
            </div>
        </div>
    </div>
</div>