<link rel="stylesheet" href="/css/style.css">
<?php include("header.php"); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("mysqlConnect.php");
if (!isset($_SESSION["user"])) {
  header("Location: Dangnhap.php");
  exit();
}

$user = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $year = $_POST["year"];
    $gender = $_POST["gender"];
    $email = $user["email"];

    $thongtin = $mysqli->prepare("UPDATE users SET name=?, year=?, gender=? WHERE email=?");
    $thongtin->bind_param("siss", $name, $year, $gender, $email);
    $thongtin->execute();

    //Cap nhat session
    $_SESSION["user"]["name"] = $name;
    $_SESSION["user"]["year"] = $year;
    $_SESSION["user"]["gender"] = $gender;

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
    <div class="row">
        <label for="email">Email:</label>
        <nav><?= $user["email"] ?><br></nav>
    </div>
    <div class="row">
        <label for="name">Họ tên:</label>
        <input style="width:55%" type="text" name="name" value="<?= $user["name"] ?>"><br>
    </div>
    <div class="row">
        <label for="year">Năm sinh:</label>
        <select style="width:30%" name="year">
        <?php
        $nowYear = date("Y");
        for($y = $nowYear; $y >= 1920; $y--){
            $sel = ($y == $user["year"]) ? "selected" : "";
            echo "<option $sel>$y</option>";
        }
        ?>
    </select><br>
    </div>
    <div class="row">
        <label for="gender">Giới tính:</label>
        <nav>
            <input style="width:50%" type="radio" name="gender" value="male">Nam
            <input style="width:50%" type="radio" name="gender" value="female">Nữ
        </nav>
    </div>
    <div style="text-align: center; margin :15px">
        <button type="submit">Cập nhật</button>
    </div>
  </fieldset>
</form>
<?php include("footer.php"); ?>   
