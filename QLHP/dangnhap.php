<link rel="stylesheet" href="./css/style.css">
<?php
include("header.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("mysqlConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = sha1($_POST["pass"]);

    $thongtin = $mysqli->prepare("SELECT * FROM nguoidung WHERE email=? AND password=?");
    $thongtin->bind_param("ss", $email, $pass);
    $thongtin->execute();
    $kq = $thongtin->get_result();

    if ($row = $kq->fetch_assoc()) {
        $_SESSION["user"] = $row;
        header("Location: index.php");
        exit();
    } else echo "<script>alert('Sai email hoặc mật khẩu');</script>";
}
?>

<title>Đăng nhập</title>
<div class="container">
    <div class="auth-shell">
        <div class="auth-card">
            <form method="POST">
                <fieldset>
                    <div style="text-align: center; color: black">
                        <h1>Đăng nhập</h1>
                    </div>
                    <div class="form-row">
                        <label for="email">Email:</label>
                        <input placeholder="Điền email" type="email" name="email" required>
                    </div>
                    <div class="form-row">
                        <label for="pass">Mật khẩu:</label>
                        <input placeholder="Điền mật khẩu" type="password" name="pass" required>
                    </div>
                    <div class="hero-button" style="justify-content: center; margin-bottom: 20px;">
                        <button type="submit" class="btn-primary">Đăng nhập</button>
                    </div>
                    <hr>
                    <div style="text-align: right; font-style: italic; margin-top: 12px;">
                        Chưa có tài khoản? <a class="auth-link" href="dangky.php">Đăng ký</a>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="auth-side">
            <span class="hero-badge" style="background: rgba(255,255,255,0.2); color: #fff;">THE FORUM</span>
            <h2>Chào mừng bạn trở lại</h2>
            <p>Đăng nhập để theo dõi khóa học, cập nhật thông tin cá nhân và nhận các ưu đãi mới nhất từ trung tâm.</p>
            <ul class="auth-list">
                <li><i class="fa-solid fa-circle-check"></i> Truy cập nhanh khóa học đang học</li>
                <li><i class="fa-solid fa-circle-check"></i> Theo dõi tiến độ học tập</li>
                <li><i class="fa-solid fa-circle-check"></i> Nhận thông báo học tập mới</li>
            </ul>
        </div>
    </div>
</div>
<?php include("footer.html"); ?>