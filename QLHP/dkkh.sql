-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2026 at 05:27 PM
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
-- Database: `dkkh`
--

-- --------------------------------------------------------

--
-- Table structure for table `dangkykhoahoc`
--

CREATE TABLE `dangkykhoahoc` (
  `id` int(11) NOT NULL,
  `id_nguoidung` int(11) NOT NULL,
  `id_khoahoc` int(11) NOT NULL,
  `ngaydangky` timestamp NOT NULL DEFAULT current_timestamp(),
  `ma_giao_dich` varchar(100) DEFAULT NULL,
  `so_tien` decimal(10,2) DEFAULT NULL,
  `trang_thai` enum('cho_xac_nhan','da_xac_nhan','huy') DEFAULT 'cho_xac_nhan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dangkykhoahoc`
--

INSERT INTO `dangkykhoahoc` (`id`, `id_nguoidung`, `id_khoahoc`, `ngaydangky`, `ma_giao_dich`, `so_tien`, `trang_thai`) VALUES
(1, 1, 16, '2026-07-19 13:19:55', 'khang 123', 5500000.00, 'cho_xac_nhan'),
(2, 1, 15, '2026-07-19 13:30:41', 'khang', 4200000.00, 'cho_xac_nhan'),
(3, 1, 13, '2026-07-19 13:30:52', '321', 6800000.00, 'cho_xac_nhan'),
(4, 1, 21, '2026-07-19 13:31:03', '321', 3500000.00, 'cho_xac_nhan'),
(5, 3, 4, '2026-07-19 13:40:14', 'Minh', 9500000.00, 'cho_xac_nhan');

-- --------------------------------------------------------

--
-- Table structure for table `khoahoc`
--

CREATE TABLE `khoahoc` (
  `makh` int(11) NOT NULL,
  `tenkh` varchar(200) NOT NULL,
  `mota` text DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `giangvien` varchar(100) NOT NULL,
  `danhmuc` varchar(50) DEFAULT NULL,
  `thutu` int(11) DEFAULT 0,
  `hinhanh` varchar(255) DEFAULT NULL,
  `sohocvien` int(11) DEFAULT 0,
  `ketqua` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ketqua`)),
  `noidung` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`noidung`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khoahoc`
--

INSERT INTO `khoahoc` (`makh`, `tenkh`, `mota`, `gia`, `giangvien`, `danhmuc`, `thutu`, `hinhanh`, `sohocvien`, `ketqua`, `noidung`) VALUES
(1, 'IELTS 0–5.0', 'Khóa học nền tảng cho người mới bắt đầu làm quen với IELTS, tập trung vào xây dựng vốn từ và kỹ năng cơ bản.', 4500000.00, 'Nguyễn Thị Thùy Dung', 'ielts', 1, 'img/ielts_0_5.jpg', 950, '{\"muctieu\": \"Đạt Band 5.0\"}', '[\"Giới thiệu định dạng đề thi\", \"Xây dựng kỹ năng nghe đọc cơ bản\", \"Từ vựng học thuật nền tảng\"]'),
(2, 'IELTS 5.0–6.0', 'Khóa học dành cho người đã có nền tảng, phát triển kỹ năng viết luận và đọc hiểu học thuật.', 5800000.00, 'Trần Văn Minh', 'ielts', 2, 'img/ielts_5_6.jpg', 620, '{\"muctieu\": \"Đạt Band 6.0\"}', '[\"Chiến lược Reading\", \"Kỹ năng Writing Task 2\", \"Phát triển Speaking tự nhiên\"]'),
(3, 'IELTS 6.0–7.0', 'Khóa học nâng cao giúp học viên luyện tập chuyên sâu 4 kỹ năng, hướng tới band 7.0.', 7200000.00, 'Phạm Thị Hương', 'ielts', 3, 'img/ielts_6_7.jpg', 410, '{\"muctieu\": \"Đạt Band 7.0\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Chiến lược Speaking nâng cao\"]'),
(4, 'IELTS 7.0–8.0+', 'Khóa học chuyên sâu cho học viên muốn đạt band cao, tập trung luyện đề sát thực tế.', 9500000.00, 'David Miller', 'ielts', 4, 'img/ielts_7_8.jpg', 210, '{\"muctieu\": \"Đạt Band 8.0+\"}', '[\"Luyện đề full test\", \"Phân tích lỗi sai\", \"Chiến lược đạt điểm tối đa\"]'),
(5, 'TOEIC 0–350+', 'Khóa học nền tảng cho người mới bắt đầu, xây dựng ngữ pháp và từ vựng cơ bản.', 2200000.00, 'Lê Quốc Bảo', 'toeic', 1, 'img/toeic_350.jpg', 1240, '{\"muctieu\": \"Đạt tối thiểu 350 điểm\"}', '[\"Ngữ pháp nền tảng\", \"Từ vựng cơ bản\", \"Làm quen định dạng đề thi\"]'),
(6, 'TOEIC 350–450+', 'Khóa học trung cấp giúp nâng cao kỹ năng làm bài và mở rộng vốn từ.', 2800000.00, 'Nguyễn Thị Mai', 'toeic', 2, 'img/toeic_450.jpg', 980, '{\"muctieu\": \"Đạt 450 điểm trở lên\"}', '[\"Chiến lược Part 5-6\", \"Luyện nghe hiểu\", \"Phân tích đề thi thực tế\"]'),
(7, 'TOEIC 450–650+', 'Khóa học nâng cao tập trung vào luyện nghe đọc tốc độ cao và chiến lược làm bài.', 3500000.00, 'Phan Văn Dũng', 'toeic', 3, 'img/toeic_650.jpg', 620, '{\"muctieu\": \"Đạt 650 điểm trở lên\"}', '[\"Listening nâng cao\", \"Reading chuyên sâu\", \"Luyện đề sát thực tế\"]'),
(8, 'TOEIC 650–850+', 'Khóa học chuyên sâu giúp học viên đạt điểm cao, luyện đề toàn diện.', 4200000.00, 'Trương Mỹ Linh', 'toeic', 4, 'img/toeic_850.jpg', 310, '{\"muctieu\": \"Đạt 850 điểm trở lên\"}', '[\"Full test practice\", \"Phân tích lỗi sai\", \"Chiến lược đạt điểm tối đa\"]'),
(9, 'VSTEP A2', 'Khóa học dành cho người mới bắt đầu theo khung năng lực ngoại ngữ Việt Nam.', 3000000.00, 'Ngô Thanh Tùng', 'vstep', 1, 'img/vstep_a2.jpg', 390, '{\"muctieu\": \"Đạt trình độ A2\"}', '[\"Ngữ pháp cơ bản\", \"Kỹ năng nghe đọc đơn giản\", \"Giao tiếp hàng ngày\"]'),
(10, 'VSTEP B1', 'Khóa học trung cấp giúp học viên đạt trình độ B1, giao tiếp và viết cơ bản.', 3800000.00, 'Đặng Thị Hòa', 'vstep', 2, 'img/vstep_b1.jpg', 210, '{\"muctieu\": \"Đạt trình độ B1\"}', '[\"Kỹ năng viết luận ngắn\", \"Phát triển kỹ năng nói\", \"Luyện đề thực tế\"]'),
(11, 'VSTEP B2', 'Khóa học nâng cao giúp học viên đạt trình độ B2, sử dụng tiếng Anh độc lập.', 4500000.00, 'Nguyễn Văn Khánh', 'vstep', 3, 'img/vstep_b2.jpg', 95, '{\"muctieu\": \"Đạt trình độ B2\"}', '[\"Kỹ năng đọc hiểu nâng cao\", \"Viết học thuật\", \"Thảo luận nhóm\"]'),
(12, 'VSTEP C1', 'Khóa học chuyên sâu cho học viên muốn đạt trình độ C1, sử dụng tiếng Anh thành thạo.', 5500000.00, 'Nguyễn Thị Minh Anh', 'vstep', 4, 'img/vstep_c1.jpg', 60, '{\"muctieu\": \"Đạt trình độ C1\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Kỹ năng thuyết trình\"]'),
(13, 'VSTEP C2', 'Khóa học cao cấp nhất theo khung năng lực, hướng tới trình độ gần như bản ngữ.', 6800000.00, 'Trương Mỹ Linh', 'vstep', 5, 'img/vstep_c2.jpg', 30, '{\"muctieu\": \"Đạt trình độ C2\"}', '[\"Luyện đề sát thực tế\", \"Phân tích chuyên sâu\", \"Chiến lược đạt điểm tối đa\"]'),
(14, 'Cambridge KET', 'Khóa học dành cho người mới bắt đầu theo chuẩn Cambridge English.', 3500000.00, 'Nguyễn Văn Phúc', 'cambridge', 1, 'img/cambridge_ket.jpg', 500, '{\"muctieu\": \"Đạt chứng chỉ KET\"}', '[\"Ngữ pháp cơ bản\", \"Từ vựng hàng ngày\", \"Kỹ năng nghe nói đơn giản\"]'),
(15, 'Cambridge PET', 'Khóa học trung cấp giúp học viên đạt chứng chỉ PET.', 4200000.00, 'Đinh Hoàng Khang', 'cambridge', 2, 'img/cambridge_pet.jpg', 350, '{\"muctieu\": \"Đạt chứng chỉ PET\"}', '[\"Kỹ năng đọc hiểu\", \"Viết luận ngắn\", \"Giao tiếp công việc\"]'),
(16, 'Cambridge FCE', 'Khóa học nâng cao giúp học viên đạt chứng chỉ FCE.', 5500000.00, 'Nguyễn Hoàng Nam', 'cambridge', 3, 'img/cambridge_fce.jpg', 220, '{\"muctieu\": \"Đạt chứng chỉ FCE\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Kỹ năng viết học thuật\"]'),
(17, 'Cambridge CAE', 'Khóa học chuyên sâu giúp học viên đạt chứng chỉ CAE.', 6800000.00, 'Sarah Jenkins', 'cambridge', 4, 'img/cambridge_cae.jpg', 120, '{\"muctieu\": \"Đạt chứng chỉ CAE\"}', '[\"Luyện đề nâng cao\", \"Chiến lược Speaking\", \"Phân tích lỗi sai\"]'),
(18, 'Cambridge CPE', 'Khóa học cao cấp nhất theo chuẩn Cambridge, hướng tới trình độ gần như bản ngữ.', 8500000.00, 'Johnathan Scott', 'cambridge', 5, 'img/cambridge_cpe.jpg', 60, '{\"muctieu\": \"Đạt chứng chỉ CPE\"}', '[\"Full test practice\", \"Phân tích chuyên sâu\", \"Chiến lược đạt điểm tối đa\"]'),
(19, 'Giao tiếp cơ bản', 'Khóa học dành cho người mới bắt đầu, phát triển phản xạ nghe nói tự nhiên.', 2000000.00, 'Huỳnh Anh Thư', 'giaotiep', 1, 'img/giaotiep_1.jpg', 1580, '{\"muctieu\": \"Tự tin giao tiếp cơ bản\"}', '[\"Chào hỏi, giới thiệu bản thân\", \"Giao tiếp hàng ngày\", \"Phát triển phản xạ nghe nói\"]'),
(20, 'Giao tiếp thành thạo', 'Khóa học trung cấp giúp học viên giao tiếp lưu loát trong công việc và đời sống.', 2800000.00, 'Đặng Thị Hòa', 'giaotiep', 2, 'img/giaotiep_2.jpg', 820, '{\"muctieu\": \"Giao tiếp lưu loát\"}', '[\"Thảo luận nhóm\", \"Trình bày ý kiến\", \"Giao tiếp công sở\"]'),
(21, 'Giao tiếp tự tin', 'Khóa học nâng cao giúp học viên tự tin làm chủ mọi tình huống giao tiếp phức tạp.', 3500000.00, 'Quách Hoàng Nhi', 'giaotiep', 3, 'img/giaotiep_3.jpg', 450, '{\"muctieu\": \"Tự tin làm chủ giao tiếp\"}', '[\"Thuyết trình\", \"Đàm phán cơ bản\", \"Phản xạ nâng cao\"]');

-- --------------------------------------------------------

--
-- Table structure for table `lienhe`
--

CREATE TABLE `lienhe` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lienhe`
--

INSERT INTO `lienhe` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'duy', 'duy123@gmail.com', 'toiec', 'qưewqewq', '2026-07-06 02:22:58'),
(2, 'hhhh', 'duy181204@gmail.com', 'toiec', 'hahaa', '2026-07-06 02:24:23'),
(3, 'duy', 'quyenloveksomuch2323@gmail.com', 'ádsa', 'ádsadas', '2026-07-06 02:24:59'),
(4, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdas', '2026-07-06 02:32:14'),
(5, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdsadsad', '2026-07-06 02:33:16'),
(6, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdasdasdas', '2026-07-06 02:41:26'),
(7, 'Hoang minh', 'ducviet1234123@gmail.com', '123', 'ascsbdjcsdbckJSC', '2026-07-06 03:23:45');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year_of_birth` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`ID`, `name`, `email`, `password`, `year_of_birth`) VALUES
(1, 'Minh Khang', 'khang@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2010'),
(2, 'admin', 'admin@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2026'),
(3, 'minh luu', 'luuminh@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2026');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enroll` (`id_nguoidung`,`id_khoahoc`),
  ADD KEY `id_khoahoc` (`id_khoahoc`);

--
-- Indexes for table `khoahoc`
--
ALTER TABLE `khoahoc`
  ADD PRIMARY KEY (`makh`);

--
-- Indexes for table `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `khoahoc`
--
ALTER TABLE `khoahoc`
  MODIFY `makh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  ADD CONSTRAINT `dangkykhoahoc_ibfk_1` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`ID`),
  ADD CONSTRAINT `dangkykhoahoc_ibfk_2` FOREIGN KEY (`id_khoahoc`) REFERENCES `khoahoc` (`makh`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
