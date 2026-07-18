<link rel="stylesheet" href="./css/style.css">
<?php include("header.php"); ?>
<?php
require_once("mysqlConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = sha1($_POST["pass"]);
    $year = $_POST["year"];

    $thongtin = $mysqli->prepare("INSERT INTO `nguoidung` (`name`, `email`, `password`, `year_of_birth`) VALUES (?, ?, ?, ?)");
    $thongtin->bind_param("sssi", $name, $email, $pass, $year);
    if ($thongtin->execute()){
        echo "<script>
        alert('Đăng ký thành công!');
        window.location.href = 'dangnhap.php';
        </script>";
    }else
        echo "<script>alert('Email đã tồn tại');</script>";
}
?>
<title>Đăng ký thành viên</title>
<div class="container">
    <div class="auth-shell">
        <div class="auth-card">
            <form method="POST">
                <fieldset>
                    <div style="text-align: center; color: black">
                        <h1>Thông tin đăng ký thành viên</h1>
                    </div>
                    <div class="form-row">
                        <label for="name">Họ tên:</label>
                        <input type="text" name="name" placeholder="Điền họ tên">
                    </div>
                    <div class="form-row">
                        <label for="email">Địa chỉ Email:</label>
                        <input type="email" name="email" placeholder="Điền email">
                    </div>
                    <div class="form-row">
                        <label for="pass">Mật khẩu:</label>
                        <input type="password" name="pass" placeholder="Điền mật khẩu">
                    </div>
                    <div class="form-row">
                        <label for="year">Năm sinh:</label>
                        <select name="year" id="year">
                        <?php 
                            $nowYear = date("Y");
                            for($year = $nowYear; $year >= 1920; $year--){
                                echo "<option>$year</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="hero-button" style="justify-content: center; margin-bottom: 20px;">
                        <button type="submit" class="btn-primary" value="addMenber">Đăng ký</button>
                        <button type="reset" class="btn-outline">Xoá form</button>
                    </div>
                    <hr>
                    <div style="text-align: right; font-style: italic; margin-top: 12px;">
                        Đã có tài khoản? <a class="auth-link" href="dangnhap.php">Đăng nhập</a>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="auth-side">
            <span class="hero-badge" style="background: rgba(255,255,255,0.2); color: #fff;">BẮT ĐẦU HÔM NAY</span>
            <h2>Tạo tài khoản để bắt đầu</h2>
            <p>Đăng ký tài khoản để truy cập khóa học phù hợp, nhận tư vấn và theo dõi lộ trình học tập của bạn.</p>
            <ul class="auth-list">
                <li><i class="fa-solid fa-circle-check"></i> Lựa chọn khóa học phù hợp</li>
                <li><i class="fa-solid fa-circle-check"></i> Nhận tư vấn miễn phí</li>
                <li><i class="fa-solid fa-circle-check"></i> Quản lý thông tin cá nhân</li>
            </ul>
        </div>
    </div>
</div>
<?php include("footer.html"); ?>