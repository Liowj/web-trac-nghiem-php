<?php
// Thông tin cấu hình database
$host = 'localhost';
$dbname = 'he_thong_thi_online'; // Tên database bạn vừa tạo
$username = 'root';              // Mặc định XAMPP user là root
$password = '';                  // Mặc định XAMPP password để trống

try {
    // Khởi tạo kết nối PDO tới MySQL
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Thiết lập chế độ báo lỗi (Exception) để dễ debug khi code
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cài đặt kiểu dữ liệu trả về mặc định là mảng kết hợp (dễ dùng cho PHP)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Uncomment dòng bên dưới (bỏ dấu //) để kiểm tra kết nối lần đầu
    // echo "Kết nối cơ sở dữ liệu thành công!";

} catch(PDOException $e) {
    // Dừng chương trình và in ra lỗi nếu kết nối thất bại
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>