<link rel="stylesheet" href="/css/style.css">
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
<title>Dang ky thanh vien</title>
<form method="POST">
    <fieldset>
        <div style="text-align: center; color: black">
                <h1>THÔNG TIN ĐĂNG KÝ THÀNH VIÊN</h1>
        </div>
        <div class="row">
            <label for="name">Họ tên:</label>
            <input type="text" name="name" placeholder="Điền họ tên"> <br>
        </div>
        <div class="row">
            <label for="email">Địa chỉ Email:</label>
            <input type="email" name="email" placeholder="Điền email"> <br>
        </div>
        <div class="row">
            <label for="pass">Mật khẩu:</label>
            <input type="password" name="pass" placeholder="Điền mật khẩu"> <br>
        </div>
        <div class="row">
            <label for="year">Năm sinh:</label>
            <select  style="width:15%" name="year" id="year">
            <?php 
                $nowYear = date("Y");
                for($year = $nowYear; $year >= 1920; $year--){
                    echo "<option>$year</option>";
                }
            ?>
            </select>
        </div>
        <div style="text-align: center; margin :15px">
            <button type="submit" value="addMenber">Đăng ký</button>
            <button type="reset">Xoá form</button>
        </div> <hr>
        <div style="float: right; font-style:italic;">
        Đã có tài khoản? <a style="text-decoration: none;font-weight: bold;" href="dangnhap.php">Đăng nhập</a>
        </div>
    </fieldset>
</form>
<?php include("footer.html"); ?>