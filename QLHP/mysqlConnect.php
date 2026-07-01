<?php
$hostname = "localhost"; // Ví dụ: "localhost"
$username = "root"; // Ví dụ: "root"
$password = ""; // Ví dụ: "" (mật khẩu rỗng)
    
// Kết nối đến MySQL Server
$mysqli = new mysqli($hostname, $username, $password,"member");

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
// KHÔNG cần chọn database ở đây, vì các file create_book.php và view_book.php đã tự gọi $mysqli->select_db("bookstore");
?>