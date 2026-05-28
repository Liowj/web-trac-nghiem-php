-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2026 at 08:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `he_thong_thi_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Thời gian làm bài (phút)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `max_attempts` int(11) DEFAULT 1 COMMENT 'Số lần làm bài tối đa',
  `start_time` datetime DEFAULT NULL COMMENT 'Thời gian mở đề',
  `end_time` datetime DEFAULT NULL COMMENT 'Thời gian đóng đề'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `title`, `duration`, `created_at`, `max_attempts`, `start_time`, `end_time`) VALUES
(6, 'Lập trình C', 45, '2026-05-23 10:59:39', 2, '2026-05-15 17:59:00', '2026-05-30 17:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `exam_logs`
--

CREATE TABLE `exam_logs` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `exam_title` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_fullname` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_logs`
--

INSERT INTO `exam_logs` (`id`, `exam_id`, `exam_title`, `admin_id`, `admin_fullname`, `action`, `created_at`) VALUES
(1, 5, 'mã nguồn mở', 1, 'Quản trị viên', 'Thêm kỳ thi mới', '2026-05-23 03:49:54'),
(2, 6, 'Lập trình C', 1, 'Quản trị viên', 'Thêm kỳ thi mới', '2026-05-23 10:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
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
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `question_type`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(27, 6, 'trac_nghiem', 'Giấy phép mã nguồn mở nào yêu cầu các phần mềm phái sinh cũng phải sử dụng cùng loại giấy phép (Copyleft)?', 'MIT License', 'Apache 2.0', 'GNU GPL', 'BSD License', 'C'),
(28, 6, 'trac_nghiem', 'Nền tảng nào dưới đây là dịch vụ lưu trữ mã nguồn phổ biến nhất thế giới hiện nay dựa trên Git?', 'Google Drive', 'GitHub', 'Dropbox', 'OneDrive', 'B'),
(29, 6, 'trac_nghiem', 'Phần mềm nào sau đây KHÔNG phải là phần mềm mã nguồn mở?', 'Hệ điều hành Ubuntu', 'Trình duyệt Firefox', 'Hệ quản trị CSDL MySQL', 'Microsoft Office', 'D'),
(30, 6, 'dung_sai', 'Phần mềm mã nguồn mở luôn luôn miễn phí 100% cho mọi mục đích sử dụng.', 'Đúng', 'Sai', '', '', 'B'),
(31, 6, 'dung_sai', 'Bất kỳ ai cũng có quyền xem, chỉnh sửa và phân phối lại mã nguồn của một phần mềm mã nguồn mở (nếu tuân theo giấy phép của nó).', 'Đúng', 'Sai', '', '', 'A'),
(32, 6, 'dung_sai', 'Hệ điều hành Linux được phát triển hoàn toàn bởi một công ty duy nhất.', 'Đúng', 'Sai', '', '', 'B'),
(33, 6, 'dien_khuyet', 'Hệ điều hành di động mã nguồn mở phổ biến nhất thế giới hiện nay do Google phát triển là ____.', '', '', '', '', 'Android'),
(34, 6, 'dien_khuyet', 'Trong Git, lệnh \"git ____\" được sử dụng để tải một kho lưu trữ (repository) từ GitHub về máy tính cục bộ.', '', '', '', '', 'clone'),
(35, 6, 'dien_khuyet', 'Cụm từ LAMP (ngăn xếp công nghệ web) là viết tắt của Linux, Apache, MySQL và ngôn ngữ lập trình ____.', '', '', '', '', 'PHP'),
(36, 6, 'noi_tu', 'Cha đẻ của hệ điều hành Linux (người tạo ra phiên bản kernel đầu tiên) là ai?', '', '', '', '', 'Linus Torvalds'),
(37, 6, 'noi_tu', 'CMS (hệ quản trị nội dung) mã nguồn mở dùng để thiết kế website phổ biến nhất thế giới là gì?', '', '', '', '', 'WordPress'),
(38, 6, 'noi_tu', 'Phần mềm web server mã nguồn mở phổ biến nhất thế giới có biểu tượng là một chiếc lông chim tên là gì?', '', '', '', '', 'Apache');

-- --------------------------------------------------------

--
-- Table structure for table `results`
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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `fullname`) VALUES
(1, 'admin', '123456', 'admin', 'Quản trị viên'),
(2, 'student1', '123456', 'student', 'Sinh viên Test'),
(3, 'Lợi đẹp trai', '123456', 'student', 'Trần Văn Lợi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_logs`
--
ALTER TABLE `exam_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exam_logs`
--
ALTER TABLE `exam_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
