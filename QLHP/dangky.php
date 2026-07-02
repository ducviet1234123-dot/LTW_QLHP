<link rel="stylesheet" href="/css/style.css">
<?php include("header.php"); ?>
<?php
require_once("mysqlConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = sha1($_POST["pass"]);
    $year = $_POST["year"];
    $gender = $_POST["gender"];

    $thongtin = $mysqli->prepare("INSERT INTO users VALUES (?,?,?,?,?)");
    $thongtin->bind_param("sssis", $email, $name, $pass, $year, $gender);
    if ($thongtin->execute())
        echo "<script>alert('Đăng ký thành thông');</script>";
    else
        echo "<script>alert('Email đã tồn tại');</script>";
}
?>
<title>Dang ky thanh vien</title>
<form method="POST">
    <fieldset>
        <div style="text-align: center; color: blue">
                <h1>THÔNG TIN ĐĂNG KÝ THÀNH VIÊN</h1>
        </div>
        <div class="row">
            <label for="name">Họ tên:</label>
            <input style="width:55%" type="text" name="name"> <br>
        </div>
        <div class="row">
            <label for="email">Địa chỉ Email:</label>
            <input style="width:55%" type="email" name="email"> <br>
        </div>
        <div class="row">
            <label for="pass">Mật khẩu:</label>
            <input style="width:35%" type="password" name="pass"> <br>
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
        <div class="row" style="margin-top: 15px;">
            <label for="gender">Giới tính:</label>
            <div>
                <input style="width:5%" type="radio" name="gender" value="male">Nam
                <input style="width:5%" type="radio" name="gender" value="female">Nữ
            </div>
        </div>
        <div style="text-align: center; margin :15px">
            <button type="submit" value="addMenber">Đăng ký</button>
            <button type="reset">Xoá form</button>
        </div> <hr>
        <nav style="float: right; font-style:italic;">
        Đã có tài khoản? <a style="text-decoration: none;font-weight: bold;" href="Dangnhap.php">Đăng nhập</a>
        </nav>
    </fieldset>
</form>
<?php include("footer.php"); ?>