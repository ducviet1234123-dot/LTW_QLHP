<link rel="stylesheet" href="/css/style.css">
<?php include 'header.html'; ?>

<?php
require_once('mysqlConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] === 'sendContact') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $stmt = $mysqli->prepare("INSERT INTO `lienhe` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            echo "<script>
            alert('Tin nhắn của bạn đã được gửi thành công!'); window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Đã xảy ra lỗi khi gửi tin nhắn. Vui lòng thử lại.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Đã xảy ra lỗi khi chuẩn bị gửi tin nhắn. Vui lòng thử lại.');</script>";
    }
}

?>

<form method="POST">
    <fieldset>
        <div style="text-align: center; color: black">
            <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
        </div>
        <div class="contact-info">
            <p>Nếu bạn có bất kỳ thắc mắc nào về khóa học IELTS hay lộ trình học, hãy để lại tin nhắn cho The Forum nhé!</p>
        </div>
        <div class="row">
            <label for="name">Họ tên:</label>
            <input type="text" name="name" placeholder="Nhập họ và tên của bạn" required> <br>
        </div>
        <div class="row">
            <label for="email">Địa chỉ Email:</label>
            <input type="email" name="email" placeholder="Nhập email để chúng tôi phản hồi" required> <br>
        </div>
        <div class="row">
            <label for="subject">Tiêu đề:</label>
            <input type="text" name="subject" placeholder="Bạn cần tư vấn về vấn đề gì?" required> <br>
        </div>
        <div class="row">
            <label style="vertical-align: top;" for="message">Nội dung:</label>
            <textarea name="message" rows="6" placeholder="Nhập chi tiết câu hỏi hoặc lời nhắn của bạn tại đây..." required></textarea> <br>
        </div>
        <div style="text-align: center; margin: 20px 15px">
            <button type="submit" name="submit" value="sendContact">Gửi tin nhắn</button>
            <button type="reset">Xoá form</button>
        </div> 
    </fieldset>
</form>

<?php include 'footer.html'; ?>