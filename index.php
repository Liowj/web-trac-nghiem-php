<?php
session_start();
require_once 'config/database.php';
require 'vendor/autoload.php';
require_once 'views/layouts/header.php';

// Lấy tham số 'page' từ URL. Nếu không có thì mặc định là 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

/**
 * Hàm kiểm tra bảo mật khu vực Admin
 */
function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo "<script>alert('Khu vực giới hạn! Bạn không có quyền truy cập.'); window.location.href='index.php?page=home';</script>";
        exit;
    }
}

/**
 * Hàm kiểm tra bảo mật khu vực Sinh viên
 */
function checkStudent() {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập hệ thống!'); window.location.href='index.php?page=login';</script>";
        exit;
    }
}

// ==================== ĐIỀU HƯỚNG (ROUTER) ====================

if ($page === 'home') {
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            echo "<script>window.location.href = 'index.php?page=admin';</script>";
            exit;
        } elseif ($_SESSION['role'] === 'student') {
            echo "<script>window.location.href = 'index.php?page=student_exams';</script>";
            exit;
        }
    }
    echo '<div class="row text-center mt-5">
            <div class="col-md-12">
                <h1 class="text-primary text-uppercase fw-bold">Hệ Thống Thi Trắc Nghiệm & Tự Luận Online</h1>
                <p class="lead">Hỗ trợ đa dạng câu hỏi: Trắc nghiệm, Đúng/Sai, Điền khuyết, Nối từ.</p>
                <a href="index.php?page=login" class="btn btn-primary btn-lg mt-3 fw-bold shadow-sm">👉 Đi tới Đăng Nhập</a>
            </div>
          </div>';
} 

// ------------------- CHỨC NĂNG XỬ LÝ TÀI KHOẢN -------------------

elseif ($page === 'login') {
    require_once 'views/auth/login.php';
} 
elseif ($page === 'process_login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Lấy thông tin tài khoản bao gồm cả cột role
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Kiểm tra tài khoản và mật khẩu
        if ($user && $password === $user['password']) { 
            // Lưu thông tin vào Session để dùng cho toàn hệ thống
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role']; // Cập nhật quyền thực tế từ database

            // Phân quyền điều hướng thông minh
            if ($_SESSION['role'] === 'admin') {
                echo "<script>window.location.href = 'index.php?page=admin';</script>";
            } else {
                echo "<script>window.location.href = 'index.php?page=student_exams';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Tên đăng nhập hoặc mật khẩu không chính xác!'); window.location.href = 'index.php?page=login';</script>";
            exit;
        }
    }
} 
elseif ($page === 'register') {
    require_once 'views/auth/register.php';
} 
elseif ($page === 'process_register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = trim($_POST['fullname']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Kiểm tra xem tên đăng nhập đã có người sử dụng chưa
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('Tên đăng nhập đã tồn tại trên hệ thống!'); window.location.href='index.php?page=register';</script>";
            exit;
        }

        // Đăng ký tài khoản mới mặc định quyền là 'student'
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, 'student')");
        if ($stmt->execute([$fullname, $username, $password])) {
            echo "<script>alert('Đăng ký tài khoản thành công!'); window.location.href='index.php?page=login';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra trong quá trình đăng ký!'); window.location.href='index.php?page=register';</script>";
        }
        exit;
    }
} 
elseif ($page === 'logout') {
    session_destroy();
    echo "<script>window.location.href = 'index.php?page=login';</script>";
    exit;
} 

// ------------------- PHÂN HỆ SINH VIÊN (STUDENT) -------------------

elseif ($page === 'student_exams') {
    checkStudent();
    require_once 'views/student/exams_list.php';
} 
elseif ($page === 'exam_info') {
    checkStudent();
    require_once 'views/student/exam_info.php';
} 
elseif ($page === 'take_exam') {
    checkStudent();
    $exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Bảo mật nâng cao: Chặn học sinh đổi ID trên URL để cố tình làm bài trái phép
    $chk = $conn->prepare("SELECT * FROM exams WHERE id = ?");
    $chk->execute([$exam_id]);
    $ex_data = $chk->fetch();
    
    if ($ex_data) {
        $cnt = $conn->prepare("SELECT COUNT(*) FROM results WHERE user_id = ? AND exam_id = ?");
        $cnt->execute([$_SESSION['user_id'], $exam_id]);
        $taken = $cnt->fetchColumn();
        
        $now = time();
        if ($taken >= $ex_data['max_attempts'] || $now < strtotime($ex_data['start_time']) || $now > strtotime($ex_data['end_time'])) {
            echo "<script>alert('Lỗi bảo mật: Bạn không đủ điều kiện hoặc đã hết lượt tham gia bài thi này!'); window.location.href='index.php?page=student_exams';</script>";
            exit;
        }
    }
    require_once 'views/student/take_exam.php';
} 
elseif ($page === 'submit_exam') {
    checkStudent();
    // Chặn trường hợp truy cập bừa không qua nút nộp bài
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<script>window.location.href='index.php?page=student_exams';</script>";
        exit;
    }
    require_once 'views/student/submit_exam.php';
} 

// ------------------- PHÂN HỆ QUẢN TRỊ VIÊN (ADMIN) -------------------

elseif ($page === 'admin') {
    checkAdmin();
    require_once 'views/admin/dashboard.php';
} 
elseif ($page === 'admin_exams') {
    checkAdmin();
    require_once 'views/admin/exams.php';
} 
elseif ($page === 'process_add_exam') {
    checkAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $duration = (int)$_POST['duration'];
        $max_attempts = (int)$_POST['max_attempts'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $stmt = $conn->prepare("INSERT INTO exams (title, duration, max_attempts, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $duration, $max_attempts, $start_time, $end_time])) {
            $exam_id = $conn->lastInsertId();
            
            // Ghi nhận nhật ký thêm đề thi mới
            $log_stmt = $conn->prepare("INSERT INTO exam_logs (admin_id, admin_fullname, action, exam_id, exam_title) VALUES (?, ?, 'Thêm kỳ thi mới', ?, ?)");
            $log_stmt->execute([$_SESSION['user_id'], $_SESSION['fullname'], $exam_id, $title]);

            echo "<script>alert('Thêm đề thi và cấu hình thành công!'); window.location.href='index.php?page=admin_exams';</script>";
        } else {
            echo "<script>alert('Không thể thêm đề thi, vui lòng kiểm tra lại!'); window.location.href='index.php?page=admin_exams';</script>";
        }
        exit;
    }
} 
elseif ($page === 'admin_delete_exam') {
    checkAdmin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    $stmt = $conn->prepare("DELETE FROM exams WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Đã xóa kỳ thi thành công khỏi hệ thống!'); window.location.href='index.php?page=admin_exams';</script>";
    exit;
} 
elseif ($page === 'admin_questions') {
    checkAdmin();
    require_once 'views/admin/questions.php';
} 
elseif ($page === 'process_add_question') {
    checkAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $exam_id = (int)$_POST['exam_id'];
        $question_type = $_POST['question_type'];
        $question_text = trim($_POST['question_text']);
        $option_a = trim($_POST['option_a'] ?? '');
        $option_b = trim($_POST['option_b'] ?? '');
        $option_c = trim($_POST['option_c'] ?? '');
        $option_d = trim($_POST['option_d'] ?? '');
        $correct_answer = trim($_POST['correct_answer']);

        $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_type, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$exam_id, $question_type, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer]);
        
        echo "<script>alert('Thêm câu hỏi mới thành công!'); window.location.href='index.php?page=admin_questions&exam_id=$exam_id';</script>";
        exit;
    }
} 
elseif ($page === 'admin_import_questions') {
    checkAdmin();
    require_once 'views/admin/import_questions.php';
} 
elseif ($page === 'admin_results') {
    checkAdmin();
    require_once 'views/admin/results.php';
} 
elseif ($page === 'admin_logs') {
    checkAdmin();
    require_once 'views/admin/logs.php';
} 

// TRƯỜNG HỢP LINK BẬY BẠ HOẶC SAI ROUTE
else {
    echo "<div class='container text-center mt-5'><h3 class='text-danger fw-bold'>⚠️ Đường dẫn không tồn tại trên hệ thống!</h3></div>";
}

require_once 'views/layouts/footer.php';
?>