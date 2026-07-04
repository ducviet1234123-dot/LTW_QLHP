<?php
$hostname = "localhost"; // Ví dụ: "localhost"
$username = "root"; // Ví dụ: "root"
$password = ""; // Ví dụ: "" (mật khẩu rỗng)
$localhost = "khoahoc";

$mysqli = new mysqli($hostname, $username, $password, $localhost);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
?>  