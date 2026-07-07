<link rel="stylesheet" href="/css/style.css">
<?php include 'header.php'; ?>

<?php
require_once('mysqlConnect.php');

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}



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

if(isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $name = $user["name"];
    $email = $user["email"];

    $stmt = $mysqli->prepare("SELECT * FROM nguoidung WHERE email=?");
    $stmt->bind_param("s", $_SESSION['user']['email']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $email = $row['email'];
    }
    $stmt->close();
}

?>

<div class="contact-page-wrapper">
    <div class="contact-left">
        <div class="contact-info-list">
            <div class="info-item">
                <span class="icon">📍</span>
                <p>Toà nhà Ladeco, 266 Đội Cấn, phường Liễu Giai, Quận Ba Đình, Hà Nội</p>
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
                    <input type="text" name="subject" placeholder="Tiêu đề cần tư vấn" required>
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
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8954452285197!2d105.81403517596956!3d21.036869287501317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab1446924849%3A0x6bba8475928d3283!2zMjY2IMSQ4buZaSBD4bqlbiwgTGnhu4V1IEdpYWksIEJhIMSQw6xuaCwgSMOgIE7hu5lpLCBWaWV0bmFt!5e0!3m2!1sen!2s!4v1710000000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>


<?php include 'footer.html'; ?>