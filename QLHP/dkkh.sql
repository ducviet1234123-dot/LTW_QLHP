-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 13, 2026 lúc 06:24 PM
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
-- Cơ sở dữ liệu: `dkkh`
--
CREATE DATABASE IF NOT EXISTS `dkkh`;
USE `dkkh`;
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dangkykhoahoc`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoahoc`
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
-- Đang đổ dữ liệu cho bảng `khoahoc`
--

INSERT INTO `khoahoc` (`makh`, `tenkh`, `mota`, `gia`, `giangvien`, `danhmuc`, `thutu`, `hinhanh`, `sohocvien`, `ketqua`, `noidung`) VALUES
(1, 'IELTS 0–5.0', 'Khóa học nền tảng cho người mới bắt đầu làm quen với IELTS, tập trung vào xây dựng vốn từ và kỹ năng cơ bản.', 2500000.00, 'Nguyễn Thị Lan', 'ielts', 1, 'img/ielts.jpg', 950, '{\"muctieu\": \"Đạt Band 5.0\"}', '[\"Giới thiệu định dạng đề thi\", \"Xây dựng kỹ năng nghe đọc cơ bản\", \"Từ vựng học thuật nền tảng\"]'),
(2, 'IELTS 5.0–6.0', 'Khóa học dành cho người đã có nền tảng, phát triển kỹ năng viết luận và đọc hiểu học thuật.', 3200000.00, 'Trần Văn Minh', 'ielts', 2, 'img/ielts.jpg', 620, '{\"muctieu\": \"Đạt Band 6.0\"}', '[\"Chiến lược Reading\", \"Kỹ năng Writing Task 2\", \"Phát triển Speaking tự nhiên\"]'),
(3, 'IELTS 6.0–7.0', 'Khóa học nâng cao giúp học viên luyện tập chuyên sâu 4 kỹ năng, hướng tới band 7.0.', 3800000.00, 'Phạm Thị Hương', 'ielts', 3, 'img/ielts.jpg', 410, '{\"muctieu\": \"Đạt Band 7.0\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Chiến lược Speaking nâng cao\"]'),
(4, 'IELTS 7.0–8.0+', 'Khóa học chuyên sâu cho học viên muốn đạt band cao, tập trung luyện đề sát thực tế.', 4500000.00, 'Nguyễn Hoàng Nam', 'ielts', 4, 'img/ielts.jpg', 210, '{\"muctieu\": \"Đạt Band 8.0+\"}', '[\"Luyện đề full test\", \"Phân tích lỗi sai\", \"Chiến lược đạt điểm tối đa\"]'),
(5, 'TOEIC 0–350+', 'Khóa học nền tảng cho người mới bắt đầu, xây dựng ngữ pháp và từ vựng cơ bản.', 1500000.00, 'Lê Quốc Bảo', 'toeic', 1, 'img/toeic.png', 1240, '{\"muctieu\": \"Đạt tối thiểu 350 điểm\"}', '[\"Ngữ pháp nền tảng\", \"Từ vựng cơ bản\", \"Làm quen định dạng đề thi\"]'),
(6, 'TOEIC 350–450+', 'Khóa học trung cấp giúp nâng cao kỹ năng làm bài và mở rộng vốn từ.', 1800000.00, 'Nguyễn Thị Mai', 'toeic', 2, 'img/toeic.png', 980, '{\"muctieu\": \"Đạt 450 điểm trở lên\"}', '[\"Chiến lược Part 5-6\", \"Luyện nghe hiểu\", \"Phân tích đề thi thực tế\"]'),
(7, 'TOEIC 450–650+', 'Khóa học nâng cao tập trung vào luyện nghe đọc tốc độ cao và chiến lược làm bài.', 2200000.00, 'Phan Văn Dũng', 'toeic', 3, 'img/toeic.png', 620, '{\"muctieu\": \"Đạt 650 điểm trở lên\"}', '[\"Listening nâng cao\", \"Reading chuyên sâu\", \"Luyện đề sát thực tế\"]'),
(8, 'TOEIC 650–850+', 'Khóa học chuyên sâu giúp học viên đạt điểm cao, luyện đề toàn diện.', 2800000.00, 'Trương Mỹ Linh', 'toeic', 4, 'img/toeic.png', 310, '{\"muctieu\": \"Đạt 850 điểm trở lên\"}', '[\"Full test practice\", \"Phân tích lỗi sai\", \"Chiến lược đạt điểm tối đa\"]'),
(9, 'VSTEP A2', 'Khóa học dành cho người mới bắt đầu theo khung năng lực ngoại ngữ Việt Nam.', 1900000.00, 'Ngô Thanh Tùng', 'vstep', 1, 'img/vstep.jpg', 390, '{\"muctieu\": \"Đạt trình độ A2\"}', '[\"Ngữ pháp cơ bản\", \"Kỹ năng nghe đọc đơn giản\", \"Giao tiếp hàng ngày\"]'),
(10, 'VSTEP B1', 'Khóa học trung cấp giúp học viên đạt trình độ B1, giao tiếp và viết cơ bản.', 2300000.00, 'Đặng Thị Hòa', 'vstep', 2, 'img/vstep.jpg', 210, '{\"muctieu\": \"Đạt trình độ B1\"}', '[\"Kỹ năng viết luận ngắn\", \"Phát triển kỹ năng nói\", \"Luyện đề thực tế\"]'),
(11, 'VSTEP B2', 'Khóa học nâng cao giúp học viên đạt trình độ B2, sử dụng tiếng Anh độc lập.', 2800000.00, 'Nguyễn Văn Khánh', 'vstep', 3, 'img/vstep.jpg', 95, '{\"muctieu\": \"Đạt trình độ B2\"}', '[\"Kỹ năng đọc hiểu nâng cao\", \"Viết học thuật\", \"Thảo luận nhóm\"]'),
(12, 'VSTEP C1', 'Khóa học chuyên sâu cho học viên muốn đạt trình độ C1, sử dụng tiếng Anh thành thạo.', 3200000.00, 'Trần Thị Hạnh', 'vstep', 4, 'img/vstep.jpg', 60, '{\"muctieu\": \"Đạt trình độ C1\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Kỹ năng thuyết trình\"]'),
(13, 'VSTEP C2', 'Khóa học cao cấp nhất theo khung năng lực, hướng tới trình độ gần như bản ngữ.', 3800000.00, 'Hoàng Anh Thư', 'vstep', 5, 'img/vstep.jpg', 30, '{\"muctieu\": \"Đạt trình độ C2\"}', '[\"Luyện đề sát thực tế\", \"Phân tích chuyên sâu\", \"Chiến lược đạt điểm tối đa\"]'),
(14, 'Cambridge KET', 'Khóa học dành cho người mới bắt đầu theo chuẩn Cambridge English.', 2000000.00, 'Nguyễn Văn Phúc', 'cambridge', 1, 'img/cambridge.jpg', 500, '{\"muctieu\": \"Đạt chứng chỉ KET\"}', '[\"Ngữ pháp cơ bản\", \"Từ vựng hàng ngày\", \"Kỹ năng nghe nói đơn giản\"]'),
(15, 'Cambridge PET', 'Khóa học trung cấp giúp học viên đạt chứng chỉ PET.', 2500000.00, 'Phạm Thị Hương', 'cambridge', 2, 'img/cambridge.jpg', 350, '{\"muctieu\": \"Đạt chứng chỉ PET\"}', '[\"Kỹ năng đọc hiểu\", \"Viết luận ngắn\", \"Giao tiếp công việc\"]'),
(16, 'Cambridge FCE', 'Khóa học nâng cao giúp học viên đạt chứng chỉ FCE.', 3000000.00, 'Nguyễn Hoàng Nam', 'cambridge', 3, 'img/cambridge.jpg', 220, '{\"muctieu\": \"Đạt chứng chỉ FCE\"}', '[\"Luyện đề toàn diện\", \"Phân tích bài mẫu\", \"Kỹ năng viết học thuật\"]'),
(17, 'Cambridge CAE', 'Khóa học chuyên sâu giúp học viên đạt chứng chỉ CAE.', 3500000.00, 'Trương Mỹ Linh', 'cambridge', 4, 'img/cambridge.jpg', 120, '{\"muctieu\": \"Đạt chứng chỉ CAE\"}', '[\"Luyện đề nâng cao\", \"Chiến lược Speaking\", \"Phân tích lỗi sai\"]'),
(18, 'Cambridge CPE', 'Khóa học cao cấp nhất theo chuẩn Cambridge, hướng tới trình độ gần như bản ngữ.', 4000000.00, 'Nguyễn Thị Mai', 'cambridge', 5, 'img/cambridge.jpg', 60, '{\"muctieu\": \"Đạt chứng chỉ CPE\"}', '[\"Full test practice\", \"Phân tích chuyên sâu\", \"Chiến lược đạt điểm tối đa\"]'),
(19, 'Giao tiếp cơ bản', 'Khóa học dành cho người mới bắt đầu, phát triển phản xạ nghe nói tự nhiên.', 1200000.00, 'Hoàng Anh Thư', 'giaotiep', 1, 'img/giaotiep.jpg', 1580, '{\"muctieu\": \"Tự tin giao tiếp cơ bản\"}', '[\"Chào hỏi, giới thiệu bản thân\", \"Giao tiếp hàng ngày\", \"Phát triển phản xạ nghe nói\"]'),
(20, 'Giao tiếp thành thạo', 'Khóa học trung cấp giúp học viên giao tiếp lưu loát trong công việc và đời sống.', 1600000.00, 'Nguyễn Văn Phúc', 'giaotiep', 2, 'img/giaotiep.jpg', 820, '{\"muctieu\": \"Giao tiếp lưu loát\"}', '[\"Thảo luận nhóm\", \"Trình bày ý kiến\", \"Giao tiếp công sở\"]'),
(21, 'Giao tiếp tự tin', 'Khóa học nâng cao giúp học viên tự tin làm chủ mọi tình huống giao tiếp phức tạp.', 1800000.00, 'Nguyễn Văn Phúc', 'giaotiep', 3, 'img/giaotiep.jpg', 450, '{\"muctieu\": \"Tự tin làm chủ giao tiếp\"}', '[\"Thuyết trình\", \"Đàm phán cơ bản\", \"Phản xạ nâng cao\"]');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lienhe`
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
-- Đang đổ dữ liệu cho bảng `lienhe`
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
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year_of_birth` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`ID`, `name`, `email`, `password`, `year_of_birth`) VALUES
(1, 'Minh Khang', 'khang@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2026');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enroll` (`id_nguoidung`,`id_khoahoc`),
  ADD KEY `id_khoahoc` (`id_khoahoc`);

--
-- Chỉ mục cho bảng `khoahoc`
--
ALTER TABLE `khoahoc`
  ADD PRIMARY KEY (`makh`);

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khoahoc`
--
ALTER TABLE `khoahoc`
  MODIFY `makh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `dangkykhoahoc`
--
ALTER TABLE `dangkykhoahoc`
  ADD CONSTRAINT `dangkykhoahoc_ibfk_1` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`id`),
  ADD CONSTRAINT `dangkykhoahoc_ibfk_2` FOREIGN KEY (`id_khoahoc`) REFERENCES `khoahoc` (`makh`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
