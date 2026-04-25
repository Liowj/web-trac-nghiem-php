<?php
session_start();
require_once 'config/database.php';
require 'vendor/autoload.php';
require_once 'views/layouts/header.php';

// Lấy tham số 'page' từ URL. Nếu không có thì mặc định là 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// ĐIỀU HƯỚNG (ROUTER)
if ($page === 'home') {
    // ---- THÊM MỚI: KIỂM TRA ROLE ĐỂ TỰ ĐỘNG CHUYỂN HƯỚNG ----
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            // Nếu là Admin, tự động đẩy về trang Quản trị
            echo "<script>window.location.href = 'index.php?page=admin';</script>";
            exit;
        } elseif ($_SESSION['role'] === 'student') {
            // Nếu là Sinh viên, tự động đẩy về trang Danh sách đề thi
            echo "<script>window.location.href = 'index.php?page=student_exams';</script>";
            exit;
        }
    }

    // ---- HIỂN THỊ TRANG CHỦ CHO KHÁCH (CHƯA ĐĂNG NHẬP) ----
    echo '<div class="row text-center mt-5">
            <div class="col-md-12">
                <h1 class="text-primary">Hệ Thống Thi Trắc Nghiệm & Tự Luận</h1>
                <p class="lead">Hỗ trợ 4 loại câu hỏi: Trắc nghiệm, Đúng/Sai, Điền khuyết, Nối từ.</p>
                <a href="index.php?page=student_exams" class="btn btn-success btn-lg mt-3">Bắt đầu làm bài</a>
            </div>
          </div>';
} 
elseif ($page === 'exam') {
    // ---- SỬA LẠI ROUTE CŨ ----
    if (!isset($_SESSION['user_id'])) {
        // Chưa đăng nhập thì đuổi về trang login
        echo "<script>
                alert('Vui lòng đăng nhập để xem danh sách đề thi!');
                window.location.href = 'index.php?page=login';
              </script>";
    } else {
        // Đã đăng nhập nhưng vô tình vào link cũ thì tự động đẩy sang link mới
        echo "<script>window.location.href = 'index.php?page=student_exams';</script>";
    }
}
elseif ($page === 'admin_delete_exam') {
    // 1. Kiểm tra quyền Admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    
    // 2. Lấy ID kỳ thi cần xóa
    $exam_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($exam_id > 0) {
        try {
            // 3. Xóa tất cả các câu hỏi thuộc kỳ thi này trước (Tránh lỗi khóa ngoại CSDL)
            $stmt_del_questions = $conn->prepare("DELETE FROM questions WHERE exam_id = ?");
            $stmt_del_questions->execute([$exam_id]);
            
            // 4. Tiến hành xóa kỳ thi
            $stmt_del_exam = $conn->prepare("DELETE FROM exams WHERE id = ?");
            $stmt_del_exam->execute([$exam_id]);
            
            // 5. Thông báo và chuyển hướng về trang danh sách
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
    // Kỉểm tra xem đã đăng nhập chưa VÀ có phải là admin không
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        echo "<script>
                alert('LỖI: Bạn không có quyền truy cập khu vực này!');
                window.location.href = 'index.php';
              </script>";
    } else {
        // Nếu chuẩn là admin thì cho hiển thị trang quản trị
        require_once 'views/admin/dashboard.php';
    }
}
elseif ($page === 'admin_exams') {
    // 1. Giao diện trang Quản lý Kỳ thi
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("<h2 class='text-danger text-center mt-5'>Từ chối truy cập!</h2>");
    }
    require_once 'views/admin/exams.php';
}
elseif ($page === 'admin_questions') {
    // Hiển thị giao diện Thêm câu hỏi
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
    // Xử lý lưu câu hỏi mới vào Database
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
            'exam_id' => $exam_id,
            'question_type' => $question_type,
            'question_text' => $question_text,
            'option_a' => $option_a,
            'option_b' => $option_b,
            'option_c' => $option_c,
            'option_d' => $option_d,
            'correct_answer' => $correct_answer
        ]);

        // Lưu xong thì thông báo và điều hướng quay lại đúng trang thêm câu hỏi của kỳ thi đó
        echo "<script>
                alert('Thêm câu hỏi thành công!');
                window.location.href = 'index.php?page=admin_questions&exam_id=" . $exam_id . "';
              </script>";
    }
}
elseif ($page === 'process_add_exam') {
    // 2. Logic xử lý khi Admin bấm nút "Lưu Kỳ Thi"
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập!");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $duration = (int)$_POST['duration'];
        
        // Thêm vào database
        $stmt = $conn->prepare("INSERT INTO exams (title, duration) VALUES (:title, :duration)");
        $stmt->execute(['title' => $title, 'duration' => $duration]);
        
        echo "<script>
                alert('Thêm kỳ thi thành công!');
                window.location.href = 'index.php?page=admin_exams';
              </script>";
    }
}
elseif ($page === 'admin_results') {
    // ---- ĐÃ THÊM: TRANG XEM ĐIỂM THI CHO ADMIN ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Từ chối truy cập! Yêu cầu quyền Quản trị viên.");
    }
    require_once 'views/admin/results.php';
}
elseif ($page === 'register') {
    // ---- HIỂN THỊ GIAO DIỆN ĐĂNG KÝ ----
    require_once 'views/auth/register.php';
}
elseif ($page === 'process_register') {
    // ---- XỬ LÝ LOGIC LƯU TÀI KHOẢN MỚI ----
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $fullname = trim($_POST['fullname']);
        $role = 'student'; // Mặc định người mới đăng ký sẽ là Sinh viên

        // 1. Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('LỖI: Tên đăng nhập này đã có người sử dụng!');
                    window.location.href = 'index.php?page=register';
                  </script>";
        } else {
            // 2. Nếu chưa ai dùng thì lưu vào Database
            $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, role) VALUES (:username, :password, :fullname, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $password, // Lưu ý: Đang lưu mật khẩu thô
                'fullname' => $fullname,
                'role' => $role
            ]);

            echo "<script>
                    alert('Đăng ký thành công! Xin mời đăng nhập.');
                    window.location.href = 'index.php?page=login';
                  </script>";
        }
    }
}
elseif ($page === 'login') {
    // ---- HIỂN THỊ TRANG ĐĂNG NHẬP ----
    require_once 'views/auth/login.php';
}
elseif ($page === 'process_login') {
    // ---- XỬ LÝ LOGIC ĐĂNG NHẬP ----
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Tìm user trong database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);
        $user = $stmt->fetch();

        if ($user) {
            // Đăng nhập thành công, lưu thông tin vào Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            // Bật thông báo và chuyển hướng sang trang làm bài
            if ($user['role'] === 'admin') {
                echo "<script>
                        alert('Xin chào Quản trị viên " . $user['fullname'] . "');
                        window.location.href = 'index.php?page=admin';
                      </script>";
            } else {
                // ĐÃ SỬA CHỖ NÀY: Sinh viên đăng nhập xong sẽ vào trang chọn đề thi
                echo "<script>
                        alert('Đăng nhập thành công! Chào mừng " . $user['fullname'] . "');
                        window.location.href = 'index.php?page=student_exams';
                      </script>";
            }
        } else {
            // Sai tài khoản/mật khẩu
            echo "<script>
                    alert('Sai tên đăng nhập hoặc mật khẩu!');
                    window.location.href = 'index.php?page=login';
                  </script>";
        }
    }
}
elseif ($page === 'student_exams') {
    // ---- ĐÃ THÊM: TRANG CHỌN ĐỀ THI CHO SINH VIÊN ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/exams_list.php';
}
elseif ($page === 'take_exam') {
    // ---- ĐÃ THÊM: TRANG LÀM BÀI THI CHO SINH VIÊN ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/take_exam.php';
}
elseif ($page === 'submit_exam') {
    // ---- XỬ LÝ CHẤM ĐIỂM VÀ HIỂN THỊ KẾT QUẢ ----
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        die("Vui lòng đăng nhập với tài khoản sinh viên!");
    }
    require_once 'views/student/submit_exam.php';
}
elseif ($page === 'logout') {
    // Xóa toàn bộ Session
    session_unset();
    session_destroy();
    
    // Thông báo và chuyển về trang chủ
    echo "<script>
            alert('Bạn đã đăng xuất thành công!');
            window.location.href = 'index.php';
          </script>";
}
else {
    // Nếu nhập sai link
    echo "<h2 class='text-center text-danger mt-5'>404 - Không tìm thấy trang!</h2>";
}

require_once 'views/layouts/footer.php';
?>