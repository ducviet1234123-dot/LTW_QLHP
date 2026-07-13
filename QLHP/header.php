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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="logo">
        <a href="index.php">
            <h1>THE <br>FORUM</h1>
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="index.php">Trang chủ</a></li>

            <!-- Dromysqliwn Khóa học -->
            <li class="dromysqliwn">   
                <a href="khoahoc.php">
                    Khóa học
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <li><a href="khoahoc.php#ielts">IELTS</a></li>
                    <li><a href="khoahoc.php#toeic">TOEIC</a></li>
                    <li><a href="khoahoc.php#vstep">VSTEP</a></li>
                    <li><a href="khoahoc.php#cambridge">Cambridge English</a></li>
                    <li><a href="khoahoc.php#giaotiep">Tiếng Anh Giao Tiếp</a></li>
                </ul>
            </li>

            <li><a href="lienhe.php">Liên hệ</a></li>
        </ul>
    </nav>

    <div class="account">
        <?php 
            if(isset($_SESSION['user'])){
                $user = $_SESSION["user"];
                echo '<p>' . htmlspecialchars($user["name"]) . '</p>';
                echo '
                <div class="dromysqliwn profile-dromysqliwn">
                    <a href="#" class="profile-btn">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="submenu profile-menu">
                        <li><a href="profile.php"><i class="fa-regular fa-id-card"></i> Thông tin cá nhân</a></li>
                        <li><a href="khoahoc.php"><i class="fa-solid fa-book"></i> Khóa học hiện tại</a></li>
                        <li><a href="update.php"><i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin</a></li>';
                    if ($user["name"] === 'admin') {
                        echo '<li class="divider"></li>';
                        echo '<li><a href="quantri.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-user-shield"></i> Trang Quản Trị</a></li>';
                    }
                    echo'
                        <li class="divider"></li>
                        <li><a href="Dangxuat.php" class="logout-item"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                    </ul>
                </div>
                
                ';
            } else {    
                echo '
                    <a class="login" href="dangnhap.php">Đăng nhập</a>
                    <a class="register" href="dangky.php">Đăng ký</a>
                ';
            }
        ?>
    </div>
</header>

<main>