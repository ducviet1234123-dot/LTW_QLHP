<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE FORUM</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet"
        href="./css/style.css">

</head>

<body>

    <header class="site-header">
        <div class="site-logo">
            <a href="index.php">
                <h1>THE <br>FORUM</h1>
            </a>
        </div>

        <nav class="site-nav">
            <ul class="site-nav-list">
                <li><a href="index.php">Trang chủ</a></li>

                <!-- Dromysqliwn Khóa học -->
                <li class="nav-dropdown">
                    <a href="khoahoc.php">
                        Khóa học
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="site-submenu">
                        <li><a href="khoahoc.php#ielts">IELTS</a></li>
                        <li><a href="khoahoc.php#toeic">TOEIC</a></li>
                        <li><a href="khoahoc.php#vstep">VSTEP</a></li>
                        <li><a href="khoahoc.php#cambridge">Cambridge English</a></li>
                        <li><a href="khoahoc.php#giaotiep">Tiếng Anh Giao Tiếp</a></li>
                    </ul>
                </li>
                <?php
                if (isset($_SESSION['user'])) {
                    $user = $_SESSION['user'];

                    if (isset($user['name']) && $user['name'] === 'admin') {
                        echo '<li><a href="xemlienhe.php">Xem liên hệ</a></li>';
                    } else {
                        echo '<li><a href="lienhe.php">Liên hệ</a></li>';
                    }
                } else {
                    echo '<li><a href="lienhe.php">Liên hệ</a></li>';
                }
                ?>
            </ul>
        </nav>

        <div class="site-account">
            <?php
            if (isset($_SESSION['user'])) {
                $user = $_SESSION["user"];
                echo '<p>' . htmlspecialchars($user["name"]) . '</p>';
                echo '
                <div class="dropdown profile-dropdown">
                    <a href="#" class="profile-btn">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="site-submenu profile-menu">
                        <li><a href="profile.php"><i class="fa-regular fa-id-card"></i> Thông tin cá nhân</a></li>
                        <li><a href="khoahoc.php"><i class="fa-solid fa-book"></i> Khóa học hiện tại</a></li>
                        <li><a href="update.php"><i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin</a></li>';
                if ($user["name"] === 'admin') {
                    echo '<li class="divider"></li>';
                    echo '<li><a href="quantri.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-user-shield"></i> Trang Quản Trị</a></li>';
                }
                echo '
                        <li class="divider"></li>
                        <li><a href="Dangxuat.php" class="logout-item"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                    </ul>
                </div>
                
                ';
            } else {
                echo '
                    <a class="auth-link" href="dangnhap.php">Đăng nhập</a>
                    <a class="auth-button" href="dangky.php">Đăng ký</a>
                ';
            }
            ?>
        </div>
    </header>

    <main>