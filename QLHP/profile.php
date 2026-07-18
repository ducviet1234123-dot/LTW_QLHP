<link rel="stylesheet" href="./css/style.css">
<?php include("header.php"); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["user"])) {
  header("Location: dangnhap.php");
  exit();
}else{
$user = $_SESSION["user"];  }
?>

<title>Thông tin cá nhân</title>
<div class="container">
    <div class="auth-shell">
        <div class="auth-card">
            <span class="hero-badge">Tài khoản</span>
            <h1>Thông tin cá nhân</h1>
            <p>Quản lý hồ sơ và theo dõi các hoạt động học tập của bạn tại THE FORUM.</p>
            <div class="profile-summary">
                <div class="profile-item">
                    <strong>Họ tên:</strong>
                    <span><?= htmlspecialchars($user["name"]) ?></span>
                </div>
                <div class="profile-item">
                    <strong>Email:</strong>
                    <span><?= htmlspecialchars($user["email"]) ?></span>
                </div>
                <div class="profile-item">
                    <strong>Năm sinh:</strong>
                    <span><?= htmlspecialchars($user["year_of_birth"]) ?></span>
                </div>
            </div>
            <div class="hero-button" style="justify-content: center; margin-top: 20px;">
                <a class="btn-primary" href="update.php">Cập nhật thông tin</a>
                <a class="btn-outline" href="Dangxuat.php">Đăng xuất</a>
            </div>
        </div>
        <div class="auth-side">
            <span class="hero-badge" style="background: rgba(255,255,255,0.2); color: #fff;">PROFILE</span>
            <h2>Hồ sơ của bạn</h2>
            <p>Giữ thông tin luôn cập nhật để nhận tư vấn phù hợp và tận dụng tốt nhất các khóa học.</p>
            <ul class="auth-list">
                <li><i class="fa-solid fa-user-check"></i> Theo dõi hồ sơ cá nhân</li>
                <li><i class="fa-solid fa-graduation-cap"></i> Kết nối với lộ trình học</li>
                <li><i class="fa-solid fa-bell"></i> Nhận thông báo quan trọng</li>
            </ul>
        </div>
    </div>
</div>
<?php include("footer.html"); ?>
