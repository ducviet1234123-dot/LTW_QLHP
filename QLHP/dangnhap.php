<link rel="stylesheet" href="/css/style.css">
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
    $kq = $thongtin->get_result();  //$kq giống như 1 “bảng dữ liệu tạm”

    if ($row = $kq->fetch_assoc()) {    //fetch_assoc() lấy 1 dòng dữ liệu đầu tiên từ $kq
        $_SESSION["user"] = $row;
        header("Location: index.php");
        exit();
    } else echo "<script>alert('Sai email hoặc mật khẩu');</script>";
}
// if (isset($_SESSION["user"])) {
//   header("Location: profile.php");
//   exit();
// }
?>

<title>Dang nhap</title>
<form method="POST">
  <fieldset>
    <div style="text-align: center; color: black">
        <h1>Đăng nhập</h1>
    </div>
    <div class="row">
        <label for="email">Email:</label>
        <input placeholder="Điền email" type="email" name="email" required><br>
    </div>
    <div class="row">
        <label for="pass">Mật khẩu:</label>
        <input placeholder="Điền mật khẩu" type="password" name="pass" required><br>
    </div>
    <div style="text-align: center; margin :5px">
        <button type="submit">Đăng nhập</button> <br>
    </div>  <hr>
    <div style="float: right; font-style:italic;">
    chưa có tài khoản? hãy <a style="text-decoration: none;font-weight: bold;" href="Dangky.php">Đăng ký</a>
</div>
  </fieldset>
</form>
<?php include("footer.php"); ?>