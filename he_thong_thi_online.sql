-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 25, 2026 lúc 11:09 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `he_thong_thi_online`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Thời gian làm bài (phút)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `exams`
--

INSERT INTO `exams` (`id`, `title`, `duration`, `created_at`) VALUES
(1, 'Mã nguồn mở', 45, '2026-04-24 20:52:08'),
(2, 'lập trình mạng', 1, '2026-04-24 22:05:58'),
(3, 'xử lý ảnh', 60, '2026-04-24 23:01:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_type` varchar(50) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question_type`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(1, 1, 'trac_nghiem', 'MIT là gì', '18', 'giấy phép mã nguồn mở', '30', '1', 'B'),
(2, 2, 'noi_tu', 'Socket là ............trong kết nối hai chiều giữa hai chương trình trên mạng.', '', '', '', '', 'endpoint'),
(3, 1, 'trac_nghiem', 'Ngôn ngữ lập trình PHP được thiết kế chủ yếu cho mục đích gì?', 'Lập trình web backend', 'Lập trình game 3D', 'Lập trình vi điều khiển', 'Thiết kế đồ họa', 'A'),
(4, 1, 'trac_nghiem', 'Cổng mặc định của giao thức web HTTP là bao nhiêu?', '21', '443', '80', '22', 'C'),
(5, 1, 'trac_nghiem', 'Trong mạng máy tính, LAN là viết tắt của cụm từ gì?', 'Large Area Network', 'Local Area Network', 'Light Area Network', 'Long Area Network', 'B'),
(6, 1, 'dung_sai', 'Hệ điều hành Ubuntu Linux là một phần mềm mã nguồn mở.', 'Đúng', 'Sai', '', '', 'A'),
(7, 1, 'dung_sai', 'Địa chỉ IPv4 bao gồm 6 cụm số (48 bit).', 'Đúng', 'Sai', '', '', 'B'),
(8, 1, 'dung_sai', 'JavaScript chỉ có thể chạy trên trình duyệt, không thể chạy trên Server.', 'Đúng', 'Sai', '', '', 'B'),
(9, 1, 'dien_khuyet', 'Trong mô hình MVC, chữ \'V\' là viết tắt của từ ____.', '', '', '', '', 'View'),
(10, 1, 'dien_khuyet', 'Lệnh ____ trong cơ sở dữ liệu SQL được dùng để truy xuất/lấy dữ liệu từ một bảng.', '', '', '', '', 'SELECT'),
(11, 1, 'dien_khuyet', 'Phím tắt mặc định để lưu tài liệu trong hầu hết các phần mềm trên Windows là Ctrl + ____.', '', '', '', '', 'S'),
(12, 1, 'noi_tu', 'Nối thiết bị sau với chức năng tương ứng: Màn hình máy tính', 'Thiết bị nhập liệu (Input)', 'Thiết bị xuất (Output)', 'Thiết bị lưu trữ', 'Thiết bị mạng', 'B'),
(13, 1, 'noi_tu', 'Nối ngôn ngữ sau với đặc điểm của nó: C++', 'Ngôn ngữ biên dịch', 'Ngôn ngữ thông dịch', 'Ngôn ngữ đánh dấu', 'Ngôn ngữ truy vấn', 'A'),
(14, 1, 'noi_tu', 'Nối hệ quản trị CSDL sau với công ty phát triển: SQL Server', 'Oracle', 'IBM', 'Microsoft', 'Google', 'C');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` float NOT NULL,
  `correct_count` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `results`
--

INSERT INTO `results` (`id`, `user_id`, `exam_id`, `score`, `correct_count`, `total_questions`, `completed_at`) VALUES
(1, 2, 1, 10, 1, 1, '2026-04-24 21:43:15'),
(2, 2, 1, 10, 1, 1, '2026-04-24 21:46:50'),
(3, 2, 2, 10, 1, 1, '2026-04-24 22:09:31'),
(4, 3, 2, 10, 1, 1, '2026-04-24 23:00:38'),
(5, 3, 1, 3.08, 4, 13, '2026-04-25 00:05:56'),
(6, 3, 1, 2.31, 3, 13, '2026-04-25 00:09:00'),
(7, 3, 1, 0, 0, 13, '2026-04-25 08:22:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `fullname`) VALUES
(1, 'admin', '123456', 'admin', 'Quản trị viên'),
(2, 'student1', '123456', 'student', 'Sinh viên Test'),
(3, 'Lợi đẹp trai', '123456', 'student', 'Trần Văn Lợi');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Chỉ mục cho bảng `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
