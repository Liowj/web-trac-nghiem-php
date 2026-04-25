# 🎓 Hệ Thống Thi Trắc Nghiệm Online (PHP & MySQL)

Đây là dự án hệ thống thi trực tuyến được xây dựng bằng PHP và cơ sở dữ liệu MySQL. Dự án hỗ trợ nhiều loại câu hỏi và cho phép giảng viên quản lý ngân hàng câu hỏi dễ dàng.

## 🚀 Tính năng nổi bật
* **Hệ thống phân quyền:** Tách biệt rõ ràng giao diện cho Admin (Quản trị viên) và Student (Sinh viên).
* **Sinh viên:** Đăng ký, đăng nhập, tham gia làm bài thi, tự động chấm điểm và xem lại lịch sử kết quả.
* **Quản trị viên (Admin):** * Quản lý danh sách kỳ thi (Thêm, sửa, xóa, quy định thời gian làm bài).
  * Hỗ trợ 4 loại câu hỏi: Trắc nghiệm, Đúng/Sai, Điền khuyết, Nối từ.
  * **Đặc biệt:** Hỗ trợ tính năng Import (Nhập) hàng loạt hàng trăm câu hỏi cùng lúc từ file Excel (`.xlsx`).

---

## 🛠️ Hướng dẫn cài đặt và chạy dự án

**Yêu cầu hệ thống:** Máy tính của bạn cần được cài đặt sẵn [XAMPP](https://www.apachefriends.org/) và [Composer](https://getcomposer.org/).

### Bước 1: Tải mã nguồn về máy
Mở Terminal trong thư mục `htdocs` của XAMPP và chạy lệnh sau:
```bash
git clone [https://github.com/Liowj/web-trac-nghiem-php.git](https://github.com/Liowj/web-trac-nghiem-php.git)

Bước 2: Cài đặt thư viện
Di chuyển vào trong thư mục dự án và chạy lệnh Composer để tải thư viện đọc file Excel:
cd web-trac-nghiem-php
composer install

Bước 3: Thiết lập Cơ sở dữ liệu (Database)
Mở bảng điều khiển XAMPP, bật Apache và MySQL.

Mở trình duyệt, truy cập vào http://localhost/phpmyadmin.

Tạo một cơ sở dữ liệu mới với tên: he_thong_thi_online.

Bấm vào tab Import (Nhập), chọn và tải lên file he_thong_thi_online.sql có sẵn trong thư mục dự án.
Bước 4: Chạy dự án
Mở trình duyệt và truy cập vào đường dẫn:
👉 http://localhost/web-trac-nghiem-php (hoặc tên thư mục bạn vừa đổi).