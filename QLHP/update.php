<link rel="stylesheet" href="/css/style.css">
<?php include("header.php"); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("mysqlConnect.php");
if (!isset($_SESSION["user"])) {
  header("Location: dangnhap.php");
  exit();
}

$user = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $year = $_POST["year"];
    $email = $user["email"];

    $thongtin = $mysqli->prepare("UPDATE nguoidung SET `name`=?, `year_of_birth`=? WHERE email=?");
    $thongtin->bind_param("sis", $name, $year, $email);
    $thongtin->execute();

    //Cap nhat session
    $_SESSION["user"]["name"] = $name;
    $_SESSION["user"]["year of birth"] = $year;

    header("Location: profile.php");
    exit();
}
?>

    <title>Cap nhat thong tin</title>
<form method="POST">
  <fieldset>
    <div style="text-align: center; color: blue">
        <h1>Cập nhật thông tin</h1>
    </div>
    <div class="form-row">
        <label for="email">Email:</label>
        <nav><?= $user["email"] ?><br></nav>
    </div>
    <div class="form-row">
        <label for="name">Họ tên:</label>
        <input style="width:55%" type="text" name="name" value="<?= $user["name"] ?>"><br>
    </div>
    <div class="form-row">
        <label for="year">Năm sinh:</label>
        <select style="width:30%" name="year">
        <?php
        $nowYear = date("Y");
        for($y = $nowYear; $y >= 1920; $y--){
            $sel = ($y == $user["year of birth"]) ? "selected" : "";
            echo "<option $sel>$y</option>";
        }
        ?>
    </select><br>
    </div>
    <div style="text-align: center; margin :15px">
        <button type="submit">Cập nhật</button>
    </div>
  </fieldset>
</form>
<?php include("footer.html"); ?>   
