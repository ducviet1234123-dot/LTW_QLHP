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

<title>Thong tin ca nhan</title>
<h2>Thông tin cá nhân</h2>
<p>Họ tên: <?= $user["name"] ?></p>
<p>Email: <?= $user["email"] ?></p>
<p>Năm sinh: <?= $user["year_of_birth"] ?></p>
<a href="update.php">Cập nhật thông tin</a> |
<a href="Dangxuat.php">Đăng xuất</a>
<?php include("footer.html"); ?>   
