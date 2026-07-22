<?php include 'header.php'; ?>

<!-- <head><i class="fa-solid fa-route"></i>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./css/style.css"></head> -->

<main>
    <section class="hero">
        <div class="container">
            <div class="hero-left">
                <span class="hero-badge">
                      🔥 Cam kết tăng 1.0 band IELTS
                </span>
                <h1>Học IELTS cùng <span>English Master</span></h1>
                <p>
                    Luyện thi IELTS hiệu quả với lộ trình bài bản,
                    giáo viên nhiều kinh nghiệm và hệ thống bài tập
                    sát đề thi thật.
                </p>
                <div class="hero-button">
                    <a href="dangky.php" class="btn-primary">Đăng ký ngay</a>
                    <a href="khoahoc.php" class="btn-outline">Xem khóa học</a>
                </div>
                <div class="hero-info">
                    <div>
                        <h3>5000+</h3>
                        <p>Học viên</p>
                    </div>
                    <div>
                        <h3>98%</h3>
                        <p>Đạt mục tiêu</p>
                    </div>
                    <div>
                        <h3>8+</h3>
                        <p>Năm kinh nghiệm</p>
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-circle"></div>
                <img src="./img/teacher.png" alt="Teacher">
                
                <div class="floating-card score">
                    <h2>9.0</h2>
                    <span>IELTS</span>
                </div>

                <div class="floating-card student">
                    👨‍🎓 5000+ học viên
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>Vì sao nên chọn English Master?</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fa-solid fa-route"></i>
                    <h3>Lộ trình rõ ràng</h3>
                    <p>Học theo từng giai đoạn giúp tăng band nhanh, không học lan man.</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h3>Giảng viên 8.5+</h3>
                    <p>Đội ngũ nhiều năm kinh nghiệm luyện thi IELTS.</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-book-open"></i>
                    <h3>Giáo trình chuẩn</h3>
                    <p>Bài học sát đề thi Cambridge mới nhất.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION NỘI DUNG KHÓA HỌC -->
    <style>
        .course-content-section {
            padding: 80px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #0b4a45; /* Dark teal background */
        }

        .course-content-section .section-title {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .course-content-section .section-title::before,
        .course-content-section .section-title::after {
            content: '✧';
            font-size: 2rem;
            color: #5edbca;
        }

        /* Khung nội dung có thể cuộn/kéo thả */
        .course-content-section .content-card {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 40px 30px 40px 40px;
            max-width: 800px;
            width: 100%;
            max-height: 420px; /* Giới hạn chiều cao để xuất hiện thanh kéo cuộn */
            overflow-y: auto;  /* Cho phép cuộn nội dung */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        /* Tùy chỉnh thanh cuộn / nút kéo cho đẹp mắt */
        .course-content-section .content-card::-webkit-scrollbar {
            width: 8px;
        }

        .course-content-section .content-card::-webkit-scrollbar-track {
            background: #e0f2f1;
            border-radius: 10px;
        }

        .course-content-section .content-card::-webkit-scrollbar-thumb {
            background: #0b4a45;
            border-radius: 10px;
        }

        .course-content-section .content-card::-webkit-scrollbar-thumb:hover {
            background: #052623;
        }

        .course-content-section .skill-group {
            margin-bottom: 30px;
        }

        .course-content-section .skill-group:last-child {
            margin-bottom: 0;
        }

        .course-content-section .skill-title {
            color: #0b4a45;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .course-content-section .skill-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #333;
            margin: 0;
        }
        
        .course-content-section .skill-description p {
             margin-bottom: 10px;
        }
    </style>

    <section class="course-content-section">
        <h2 class="section-title">Nội dung khóa học</h2>

        <div class="content-card">
            <div class="skill-group">
                <h3 class="skill-title">KỸ NĂNG LISTENING</h3>
                <div class="skill-description">
                    <p>Phân tích chuyên sâu về cấu trúc 4 phần của bài thi Listening và đặc điểm từng dạng câu hỏi.</p>
                    <p>Hướng dẫn chiến lược nghe hiểu chủ động, xác định từ khóa quan trọng và dự đoán nội dung trước khi nghe.</p>
                    <p>Luyện tập kỹ năng nghe chi tiết và phân tích thông tin phức tạp qua các bài mẫu sát đề thi thực tế.</p>
                </div>
            </div>

            <div class="skill-group">
                <h3 class="skill-title">KỸ NĂNG SPEAKING</h3>
                <div class="skill-description">
                    <p>Hướng dẫn xây dựng cấu trúc câu trả lời logic và mạch lạc theo từng phần trong bài thi Speaking.</p>
                    <p>Phát triển vốn từ vựng học thuật, cải thiện tính chính xác ngữ pháp và nâng cao kỹ thuật phát âm chuẩn.</p>
                    <p>Áp dụng kỹ thuật Paraphrasing và Cohesive Devices để tăng tính tự nhiên, chuyên nghiệp trong bài nói.</p>
                </div>
            </div>

            <div class="skill-group">
                <h3 class="skill-title">KỸ NĂNG READING</h3>
                <div class="skill-description">
                    <p>Tập trung phân tích chiến lược đọc hiệu quả, bao gồm kỹ thuật Skimming, Scanning và Critical Reading.</p>
                    <p>Giải quyết các dạng câu hỏi phức tạp như Matching Information, True/False/Not Given, và Summary Completion.</p>
                    <p>Rèn luyện khả năng đọc hiểu nhanh, phân tích thông tin học thuật và quản lý thời gian tối ưu trong phòng thi.</p>
                </div>
            </div>
            
            <div class="skill-group">
                <h3 class="skill-title">KỸ NĂNG WRITING</h3>
                <div class="skill-description">
                    <p>Chuyên sâu từng dạng bài Writing Task 1 (biểu đồ, quy trình) và Task 2 (bài luận học thuật).</p>
                    <p>Hướng dẫn cách lập luận chặt chẽ, tổ chức ý tưởng khoa học và sử dụng từ vựng học thuật chính xác.</p>
                    <p>Phân tích bài mẫu band cao, thực hành viết bài, và cải thiện thông qua phản hồi chi tiết từ giảng viên.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- KẾT THÚC SECTION NỘI DUNG KHÓA HỌC -->

</main> 

<script src="./js/script.js"></script>
<?php include 'footer.html'; ?>