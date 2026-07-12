CREATE DATABASE IF NOT EXISTS dkkh
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dkkh;

-- Bảng người dùng
CREATE TABLE nguoidung (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tendangnhap VARCHAR(100) NOT NULL UNIQUE,
  matkhau VARCHAR(255) NOT NULL
);

-- Bảng khóa học
CREATE TABLE khoahoc (
  makh INT AUTO_INCREMENT PRIMARY KEY,
  tenkh VARCHAR(200) NOT NULL,
  mota TEXT,
  gia DECIMAL(10,2) NOT NULL,
  giangvien VARCHAR(100) NOT NULL,
  danhmuc VARCHAR(50),
  thutu INT DEFAULT 0,
  hinhanh VARCHAR(255),
  sohocvien INT DEFAULT 0,
  ketqua JSON,
  noidung JSON
);


-- Bảng đăng ký khóa học
CREATE TABLE dangkykhoahoc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_nguoidung INT NOT NULL,
  id_khoahoc INT NOT NULL,
  ngaydangky TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ma_giao_dich VARCHAR(100),
  so_tien DECIMAL(10,2),
  trang_thai ENUM('cho_xac_nhan','da_xac_nhan','huy') DEFAULT 'cho_xac_nhan',
  UNIQUE KEY unique_enroll (id_nguoidung, id_khoahoc),
  FOREIGN KEY (id_nguoidung) REFERENCES nguoidung(id),
  FOREIGN KEY (id_khoahoc) REFERENCES khoahoc(makh)
);
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

INSERT INTO khoahoc 
(tenkh, mota, danhmuc, thutu, sohocvien, gia, giangvien, hinhanh, ketqua, noidung) 
VALUES
-- IELTS
('IELTS 0–5.0', 
 'Khóa học nền tảng cho người mới bắt đầu làm quen với IELTS, tập trung vào xây dựng vốn từ và kỹ năng cơ bản.', 
 'ielts', 1, 950, 2500000, 'Nguyễn Thị Lan', 'img/ielts.jpg',
 JSON_OBJECT('muctieu','Đạt Band 5.0'), 
 JSON_ARRAY('Giới thiệu định dạng đề thi','Xây dựng kỹ năng nghe đọc cơ bản','Từ vựng học thuật nền tảng')),

('IELTS 5.0–6.0', 
 'Khóa học dành cho người đã có nền tảng, phát triển kỹ năng viết luận và đọc hiểu học thuật.', 
 'ielts', 2, 620, 3200000, 'Trần Văn Minh', 'img/ielts.jpg',
 JSON_OBJECT('muctieu','Đạt Band 6.0'), 
 JSON_ARRAY('Chiến lược Reading','Kỹ năng Writing Task 2','Phát triển Speaking tự nhiên')),

('IELTS 6.0–7.0', 
 'Khóa học nâng cao giúp học viên luyện tập chuyên sâu 4 kỹ năng, hướng tới band 7.0.', 
 'ielts', 3, 410, 3800000, 'Phạm Thị Hương', 'img/ielts.jpg',
 JSON_OBJECT('muctieu','Đạt Band 7.0'), 
 JSON_ARRAY('Luyện đề toàn diện','Phân tích bài mẫu','Chiến lược Speaking nâng cao')),

('IELTS 7.0–8.0+', 
 'Khóa học chuyên sâu cho học viên muốn đạt band cao, tập trung luyện đề sát thực tế.', 
 'ielts', 4, 210, 4500000, 'Nguyễn Hoàng Nam', 'img/ielts.jpg',
 JSON_OBJECT('muctieu','Đạt Band 8.0+'), 
 JSON_ARRAY('Luyện đề full test','Phân tích lỗi sai','Chiến lược đạt điểm tối đa')),

-- TOEIC
('TOEIC 0–350+', 
 'Khóa học nền tảng cho người mới bắt đầu, xây dựng ngữ pháp và từ vựng cơ bản.', 
 'toeic', 1, 1240, 1500000, 'Lê Quốc Bảo', 'img/toeic.png',
 JSON_OBJECT('muctieu','Đạt tối thiểu 350 điểm'), 
 JSON_ARRAY('Ngữ pháp nền tảng','Từ vựng cơ bản','Làm quen định dạng đề thi')),

('TOEIC 350–450+', 
 'Khóa học trung cấp giúp nâng cao kỹ năng làm bài và mở rộng vốn từ.', 
 'toeic', 2, 980, 1800000, 'Nguyễn Thị Mai', 'img/toeic.png',
 JSON_OBJECT('muctieu','Đạt 450 điểm trở lên'), 
 JSON_ARRAY('Chiến lược Part 5-6','Luyện nghe hiểu','Phân tích đề thi thực tế')),

('TOEIC 450–650+', 
 'Khóa học nâng cao tập trung vào luyện nghe đọc tốc độ cao và chiến lược làm bài.', 
 'toeic', 3, 620, 2200000, 'Phan Văn Dũng', 'img/toeic.png',
 JSON_OBJECT('muctieu','Đạt 650 điểm trở lên'), 
 JSON_ARRAY('Listening nâng cao','Reading chuyên sâu','Luyện đề sát thực tế')),

('TOEIC 650–850+', 
 'Khóa học chuyên sâu giúp học viên đạt điểm cao, luyện đề toàn diện.', 
 'toeic', 4, 310, 2800000, 'Trương Mỹ Linh', 'img/toeic.png',
 JSON_OBJECT('muctieu','Đạt 850 điểm trở lên'), 
 JSON_ARRAY('Full test practice','Phân tích lỗi sai','Chiến lược đạt điểm tối đa')),

-- VSTEP
('VSTEP A2', 
 'Khóa học dành cho người mới bắt đầu theo khung năng lực ngoại ngữ Việt Nam.', 
 'vstep', 1, 390, 1900000, 'Ngô Thanh Tùng', 'img/vstep.jpg',
 JSON_OBJECT('muctieu','Đạt trình độ A2'), 
 JSON_ARRAY('Ngữ pháp cơ bản','Kỹ năng nghe đọc đơn giản','Giao tiếp hàng ngày')),

('VSTEP B1', 
 'Khóa học trung cấp giúp học viên đạt trình độ B1, giao tiếp và viết cơ bản.', 
 'vstep', 2, 210, 2300000, 'Đặng Thị Hòa', 'img/vstep.jpg',
 JSON_OBJECT('muctieu','Đạt trình độ B1'), 
 JSON_ARRAY('Kỹ năng viết luận ngắn','Phát triển kỹ năng nói','Luyện đề thực tế')),

('VSTEP B2', 
 'Khóa học nâng cao giúp học viên đạt trình độ B2, sử dụng tiếng Anh độc lập.', 
 'vstep', 3, 95, 2800000, 'Nguyễn Văn Khánh', 'img/vstep.jpg',
 JSON_OBJECT('muctieu','Đạt trình độ B2'), 
 JSON_ARRAY('Kỹ năng đọc hiểu nâng cao','Viết học thuật','Thảo luận nhóm')),

('VSTEP C1', 
 'Khóa học chuyên sâu cho học viên muốn đạt trình độ C1, sử dụng tiếng Anh thành thạo.', 
 'vstep', 4, 60, 3200000, 'Trần Thị Hạnh', 'img/vstep.jpg',
 JSON_OBJECT('muctieu','Đạt trình độ C1'), 
 JSON_ARRAY('Luyện đề toàn diện','Phân tích bài mẫu','Kỹ năng thuyết trình')),

('VSTEP C2', 
 'Khóa học cao cấp nhất theo khung năng lực, hướng tới trình độ gần như bản ngữ.', 
 'vstep', 5, 30, 3800000, 'Hoàng Anh Thư', 'img/vstep.jpg',
 JSON_OBJECT('muctieu','Đạt trình độ C2'), 
 JSON_ARRAY('Luyện đề sát thực tế','Phân tích chuyên sâu','Chiến lược đạt điểm tối đa')),

-- Cambridge English
('Cambridge KET', 
 'Khóa học dành cho người mới bắt đầu theo chuẩn Cambridge English.', 
 'cambridge', 1, 500, 2000000, 'Nguyễn Văn Phúc', 'img/cambridge.jpg',
 JSON_OBJECT('muctieu','Đạt chứng chỉ KET'), 
 JSON_ARRAY('Ngữ pháp cơ bản','Từ vựng hàng ngày','Kỹ năng nghe nói đơn giản')),

('Cambridge PET', 
 'Khóa học trung cấp giúp học viên đạt chứng chỉ PET.', 
 'cambridge', 2, 350, 2500000, 'Phạm Thị Hương', 'img/cambridge.jpg',
 JSON_OBJECT('muctieu','Đạt chứng chỉ PET'), 
 JSON_ARRAY('Kỹ năng đọc hiểu','Viết luận ngắn','Giao tiếp công việc')),

('Cambridge FCE', 
 'Khóa học nâng cao giúp học viên đạt chứng chỉ FCE.', 
 'cambridge', 3, 220, 3000000, 'Nguyễn Hoàng Nam', 'img/cambridge.jpg',
 JSON_OBJECT('muctieu','Đạt chứng chỉ FCE'), 
 JSON_ARRAY('Luyện đề toàn diện','Phân tích bài mẫu','Kỹ năng viết học thuật')),

('Cambridge CAE', 
 'Khóa học chuyên sâu giúp học viên đạt chứng chỉ CAE.', 
 'cambridge', 4, 120, 3500000, 'Trương Mỹ Linh', 'img/cambridge.jpg',
 JSON_OBJECT('muctieu','Đạt chứng chỉ CAE'), 
 JSON_ARRAY('Luyện đề nâng cao','Chiến lược Speaking','Phân tích lỗi sai')),

('Cambridge CPE', 
 'Khóa học cao cấp nhất theo chuẩn Cambridge, hướng tới trình độ gần như bản ngữ.', 
 'cambridge', 5, 60, 4000000, 'Nguyễn Thị Mai', 'img/cambridge.jpg',
 JSON_OBJECT('muctieu','Đạt chứng chỉ CPE'), 
 JSON_ARRAY('Full test practice','Phân tích chuyên sâu','Chiến lược đạt điểm tối đa')),

-- Giao tiếp
('Giao tiếp cơ bản', 
 'Khóa học dành cho người mới bắt đầu, phát triển phản xạ nghe nói tự nhiên.', 
 'giaotiep', 1, 1580, 1200000, 'Hoàng Anh Thư', 'img/giaotiep.jpg',
 JSON_OBJECT('muctieu','Tự tin giao tiếp cơ bản'), 
 JSON_ARRAY('Chào hỏi, giới thiệu bản thân','Giao tiếp hàng ngày','Phát triển phản xạ nghe nói')),

('Giao tiếp thành thạo', 
 'Khóa học trung cấp giúp học viên giao tiếp lưu loát trong công việc và đời sống.', 
 'giaotiep', 2, 820, 1600000, 'Nguyễn Văn Phúc', 'img/giaotiep.jpg',
 JSON_OBJECT('muctieu','Giao tiếp lưu loát'), 
 JSON_ARRAY('Thảo luận nhóm','Trình bày ý kiến','Giao tiếp công sở')),

('Giao tiếp tự tin', 
 'Khóa học nâng cao giúp học viên tự tin làm chủ mọi tình huống giao tiếp phức tạp.', 
 'giaotiep', 3, 450, 1800000, 'Nguyễn Văn Phúc', 'img/giaotiep.jpg',
 JSON_OBJECT('muctieu', 'Tự tin làm chủ giao tiếp'), 
 JSON_ARRAY('Thuyết trình', 'Đàm phán cơ bản', 'Phản xạ nâng cao'));


INSERT INTO `lienhe` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'duy', 'duy123@gmail.com', 'toiec', 'qưewqewq', '2026-07-06 09:22:58'),
(2, 'hhhh', 'duy181204@gmail.com', 'toiec', 'hahaa', '2026-07-06 09:24:23'),
(3, 'duy', 'quyenloveksomuch2323@gmail.com', 'ádsa', 'ádsadas', '2026-07-06 09:24:59'),
(4, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdas', '2026-07-06 09:32:14'),
(5, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdsadsad', '2026-07-06 09:33:16'),
(6, 'duy', 'duy123@gmail.com', 'toiec', 'ádasdasdasdas', '2026-07-06 09:41:26'),
(7, 'Hoang minh', 'ducviet1234123@gmail.com', '123', 'ascsbdjcsdbckJSC', '2026-07-06 10:23:45');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
