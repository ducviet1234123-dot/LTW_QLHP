<link rel="stylesheet" href="/css/style.css">
<?php include 'header.php'; ?>

<?php
require_once('mysqlConnect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
} 

$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';

if (isset($_POST['submit']) && $_POST['submit'] === 'sendContact') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $stmt = $mysqli->prepare("INSERT INTO lienhe (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>
        alert('Tin nhắn của bạn đã được gửi thành công!');
        window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Đã xảy ra lỗi khi gửi tin nhắn. Vui lòng thử lại sau.');</script>";
    }
    
    $stmt->close();
}
?>

<div class="contact-page-wrapper">
    <div class="contact-left">
        <div class="contact-info-list">
            <div class="info-item">
                <span class="icon">📍</span>
                <p>Trường Đại học Cần Thơ Khu II, Đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ</p>
            </div>
            <div class="info-item">
                <span class="icon">📞</span>
                <p>19006750</p>
            </div>
            <div class="info-item">
                <span class="icon">✉️</span>
                <p>support@theforum.vn</p>
            </div>
        </div>
        <hr class="divider">
        <div class="form-container">
            <h2>Liên hệ với chúng tôi</h2>
            <form method="POST">
                <div class="row">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Họ và tên" required>
                </div>
                <div class="row">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
                </div>
                <div class="row">
                    <!-- Dropdown thay cho thẻ input text cũ -->
                    <select name="subject" required>
                        <option value="" disabled selected>Chọn chủ đề cần tư vấn</option>
                        <option value="IELTS">IELTS</option>
                        <option value="TOEIC">TOEIC</option>
                        <option value="VSTEP">VSTEP</option>
                        <option value="Cambridge English">Cambridge English</option>
                        <option value="Tiếng Anh Giao Tiếp">Tiếng Anh Giao Tiếp</option>
                    </select>
                </div>
                <div class="row">
                    <textarea name="message" rows="5" placeholder="Nội dung lời nhắn..." required></textarea>
                </div>
                <div class="btn-submit-wrapper">
                    <button type="submit" name="submit" value="sendContact">Gửi liên hệ</button>
                </div>
            </form>
        </div>
    </div>
    <div class="contact-right">
        <!-- Đã đổi link iframe Google Maps thành Đại học Cần Thơ Khu 2 -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.841454343722!2d105.76804037503115!3d10.029938990077054!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0895a51d60719%3A0x9d76b0035f6d53d0!2sCan%20Tho%20University!5e0!3m2!1sen!2s!4v1690000000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<?php include 'footer.html'; ?>