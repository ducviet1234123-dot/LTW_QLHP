<?php
$hostname = "localhost"; // Ví dụ: "localhost"
$username = "root"; // Ví dụ: "root"
$password = ""; // Ví dụ: "" (mật khẩu rỗng)
    
// Kết nối đến MySQL Server
$mysqli = new mysqli($hostname, $username, $password,"dkkh");
// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
?>