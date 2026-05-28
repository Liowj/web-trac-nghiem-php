<?php
session_start();
require_once 'config/database.php';
require 'vendor/autoload.php';
require_once 'views/layouts/header.php';

// Lấy tham số 'page' từ URL. Nếu không có thì mặc định là 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// ĐIỀU HƯỚNG (ROUTER)
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
                <h1 class="text-primary">Hệ Thống Thi Trắc Nghiệm & Tự Luận</h1>
                <p class="lead">Hỗ trợ 4 loại câu hỏi: Trắc nghiệm, Đúng/Sai, Điền khuyết, tự luận ngắn.</p>
                <a href="index.php?page=student_exams" class="btn btn-success btn-lg mt-3">Bắt đầu làm bài</a>
            </div>
          </div>';
} 
elseif ($page === 'exam') {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
                alert('Vui lòng đăng nhập để xem danh sách đề thi!');
                window.location.href = 'index.php?page=login';
              </script>";
    } else {
        echo "<script>window.location.href = 'index.php?page=student_exams';</script>";
    }
}
elseif ($page === 'admin_delete_exam') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    $exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($exam_id > 0) {
        try {
            $stmt_del_questions = $conn->prepare("DELETE FROM questions WHERE exam_id = ?");
            $stmt_del_questions->execute([$exam_id]);
            
            $stmt_del_exam = $conn->prepare("DELETE FROM exams WHERE id = ?");
            $stmt_del_exam->execute([$exam_id]);
            
            echo "<script>
                    alert('✅ Đã xóa kỳ thi và toàn bộ câu hỏi liên quan thành công!');
                    window.location.href = 'index.php?page=admin_exams';
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    alert('❌ Lỗi khi xóa: " . $e->getMessage() . "');
                    window.location.href = 'index.php?page=admin_exams';
                  </script>";
        }
    } else {
         echo "<script>
                alert('⚠️ Không tìm thấy kỳ thi để xóa!');
                window.location.href = 'index.php?page=admin_exams';
              </script>";
    }
}
elseif ($page === 'admin') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        echo "<script>
                alert('LỖI: Bạn không có quyền truy cập khu vực này!');
                window.location.href = 'index.php';
              </script>";
    } else {
        require_once 'views/admin/dashboard.php';
    }
}
elseif ($page === 'admin_exams') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("<h2 class='text-danger text-center mt-5'>Từ chối truy cập!</h2>");
    }
    require_once 'views/admin/exams.php';
}
elseif ($page === 'admin_questions') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    require_once 'views/admin/questions.php';
}
elseif ($page === 'admin_import_questions') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    require_once 'views/admin/import_questions.php';
}
elseif ($page === 'process_add_question') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $exam_id = (int)$_POST['exam_id'];
        $question_type = $_POST['question_type'];
        $question_text = trim($_POST['question_text']);
        $option_a = trim($_POST['option_a']);
        $option_b = trim($_POST['option_b']);
        $option_c = trim($_POST['option_c']);
        $option_d = trim($_POST['option_d']);
        $correct_answer = trim($_POST['correct_answer']);

        $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_type, question_text, option_a, option_b, option_c, option_d, correct_answer) 
                        VALUES (:exam_id, :question_type, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_answer)");
        $stmt->execute([
            'exam_id' => $exam_id, 'question_type' => $question_type, 'question_text' => $question_text,
            'option_a' => $option_a, 'option_b' => $option_b, 'option_c' => $option_c, 'option_d' => $option_d, 'correct_answer' => $correct_answer
        ]);

        echo "<script>
                alert('Thêm câu hỏi thành công!');
                window.location.href = 'index.php?page=admin_questions&exam_id=" . $exam_id . "';
              </script>";
    }
}
elseif ($page === 'process_add_exam') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $duration = (int)$_POST['duration'];
        $max_attempts = (int)$_POST['max_attempts'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        
        // 1. Lưu thông tin đề thi mới vào database
        $stmt = $conn->prepare("INSERT INTO exams (title, duration, max_attempts, start_time, end_time) VALUES (:title, :duration, :max_attempts, :start_time, :end_time)");
        $stmt->execute(['title' => $title, 'duration' => $duration, 'max_attempts' => $max_attempts, 'start_time' => $start_time, 'end_time' => $end_time]);
        $exam_id = $conn->lastInsertId();

        // 2. TỰ ĐỘNG GHI LỊCH SỬ THÊM ĐỀ
        $log_stmt = $conn->prepare("INSERT INTO exam_logs (exam_id, exam_title, admin_id, admin_fullname, action) VALUES (:exam_id, :exam_title, :admin_id, :admin_fullname, 'Thêm kỳ thi mới')");
        $log_stmt->execute([
            'exam_id' => $exam_id,
            'exam_title' => $title,
            'admin_id' => $_SESSION['user_id'],
            'admin_fullname' => $_SESSION['fullname'],
        ]);
        
        echo "<script>
                alert('Thêm kỳ thi và ghi lịch sử thành công!');
                window.location.href = 'index.php?page=admin_exams';
              </script>";
    }
}
elseif ($page === 'admin_logs') {
    // ---- THÊM MỚI: TRANG XEM LỊCH SỬ THÊM ĐỀ ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    require_once 'views/admin/logs.php';
}
elseif ($page === 'exam_info') {
    // ---- THÊM MỚI: TRANG ĐỆM HIỂN THỊ THỜI GIAN & KHUNG GIỜ THI ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/exam_info.php';
}
elseif ($page === 'admin_results') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập! Yêu cầu quyền Quản trị viên.");
    }
    require_once 'views/admin/results.php';
}
elseif ($page === 'register') {
    require_once 'views/auth/register.php';
}
elseif ($page === 'process_register') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $fullname = trim($_POST['fullname']);
        $role = 'student';

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('LỖI: Tên đăng nhập này đã có người sử dụng!');
                    window.location.href = 'index.php?page=register';
                  </script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, role) VALUES (:username, :password, :fullname, :role)");
            $stmt->execute(['username' => $username, 'password' => $password, 'fullname' => $fullname, 'role' => $role]);

            echo "<script>
                    alert('Đăng ký thành công! Xin mời đăng nhập.');
                    window.location.href = 'index.php?page=login';
                  </script>";
        }
    }
}
elseif ($page === 'login') {
    require_once 'views/auth/login.php';
}
elseif ($page === 'process_login') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                echo "<script>
                        alert('Xin chào Quản trị viên " . $user['fullname'] . "');
                        window.location.href = 'index.php?page=admin';
                      </script>";
            } else {
                echo "<script>
                        alert('Đăng nhập thành công! Chào mừng " . $user['fullname'] . "');
                        window.location.href = 'index.php?page=student_exams';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Sai tên đăng nhập hoặc mật khẩu!');
                    window.location.href = 'index.php?page=login';
                  </script>";
        }
    }
}
elseif ($page === 'student_exams') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/exams_list.php';
}
elseif ($page === 'take_exam') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    
    // Bảo mật nâng cao: Ngăn chặn sinh viên điền bừa link trên URL để hack vào thi khi quá giờ hoặc hết lượt
    $exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $chk = $conn->prepare("SELECT * FROM exams WHERE id = ?");
    $chk->execute([$exam_id]);
    $ex_data = $chk->fetch();
    
    if ($ex_data) {
        $cnt = $conn->prepare("SELECT COUNT(*) FROM results WHERE user_id = ? AND exam_id = ?");
        $cnt->execute([$_SESSION['user_id'], $exam_id]);
        $taken = $cnt->fetchColumn();
        
        $now = time();
        if ($taken >= $ex_data['max_attempts'] || $now < strtotime($ex_data['start_time']) || $now > strtotime($ex_data['end_time'])) {
            echo "<script>alert('Lỗi bảo mật: Bạn không đủ điều kiện làm bài thi này!'); window.location.href='index.php?page=student_exams';</script>";
            exit;
        }
    }
    require_once 'views/student/take_exam.php';
}
elseif ($page === 'submit_exam') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/submit_exam.php';
}
elseif ($page === 'logout') {
    session_unset();
    session_destroy();
    echo "<script>
            alert('Bạn đã đăng xuất thành công!');
            window.location.href = 'index.php';
          </script>";
}
else {
    echo "<h2 class='text-center text-danger mt-5'>404 - Không tìm thấy trang!</h2>";
}

require_once 'views/layouts/footer.php';
?>