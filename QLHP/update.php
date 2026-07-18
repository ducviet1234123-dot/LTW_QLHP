<link rel="stylesheet" href="./css/style.css">
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

    $_SESSION["user"]["name"] = $name;
    $_SESSION["user"]["year_of_birth"] = $year;

    header("Location: profile.php");
    exit();
}
?>

<title>Cập nhật thông tin</title>
<div class="container">
    <div class="auth-shell">
        <div class="auth-card">
            <form method="POST">
                <fieldset>
                    <div style="text-align: center; color: black">
                        <h1>Cập nhật thông tin</h1>
                    </div>
                    <div class="form-row">
                        <label for="email">Email:</label>
                        <p><?= htmlspecialchars($user["email"]) ?></p>
                    </div>
                    <div class="form-row">
                        <label for="name">Họ tên:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user["name"]) ?>">
                    </div>
                    <div class="form-row">
                        <label for="year">Năm sinh:</label>
                        <select name="year">
                        <?php
                        $nowYear = date("Y");
                        for($y = $nowYear; $y >= 1920; $y--){
                            $sel = ($y == ($user["year_of_birth"] ?? $user["year of birth"])) ? "selected" : "";
                            echo "<option $sel>$y</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="hero-button" style="justify-content: center; margin-top: 20px;">
                        <button type="submit" class="btn-primary">Cập nhật</button>
                        <a class="btn-outline" href="profile.php">Quay lại</a>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="auth-side">
            <span class="hero-badge" style="background: rgba(255,255,255,0.2); color: #fff;">UPDATE</span>
            <h2>Chỉnh sửa hồ sơ</h2>
            <p>Giữ thông tin cá nhân luôn cập nhật để nhận tư vấn và hỗ trợ phù hợp hơn từ THE FORUM.</p>
            <ul class="auth-list">
                <li><i class="fa-solid fa-user-pen"></i> Cập nhật tên và năm sinh</li>
                <li><i class="fa-solid fa-shield-halved"></i> Bảo mật thông tin tài khoản</li>
                <li><i class="fa-solid fa-arrow-right"></i> Quay lại hồ sơ sau khi lưu</li>
            </ul>
        </div>
    </div>
</div>
<?php include("footer.html"); ?>
