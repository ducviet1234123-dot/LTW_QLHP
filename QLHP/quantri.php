<link rel="stylesheet" href="./css/style.css">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("mysqlConnect.php");

if (!isset($_SESSION["user"])) {
    header("Location: dangnhap.php");
    exit();
}

$query = "SELECT 
        nguoidung.ID AS user_id,
        nguoidung.name AS user_name,
        nguoidung.email,
        dangkykhoahoc.ngaydangky,
        dangkykhoahoc.trang_thai,
        khoahoc.makh,
        khoahoc.tenkh,
        khoahoc.gia,
        khoahoc.danhmuc
    FROM dangkykhoahoc
    JOIN nguoidung ON dangkykhoahoc.id_nguoidung = nguoidung.ID
    JOIN khoahoc ON dangkykhoahoc.id_khoahoc = khoahoc.makh
    -- ORDER BY dangkykhoahoc.ngaydangky DESC, nguoidung.name ASC
";

$result = $mysqli->query($query);
$registrations = [];
while ($row = $result->fetch_assoc()) {
    $registrations[] = $row;
}

$groupedRegistrations = [];
foreach ($registrations as $row) {
    $userId = $row['user_id'];
    if (!isset($groupedRegistrations[$userId])) {
        $groupedRegistrations[$userId] = [
            'user_id' => $row['user_id'],
            'user_name' => $row['user_name'],
            'email' => $row['email'],
            'courses' => []
        ];
    }

    $groupedRegistrations[$userId]['courses'][] = [
        'tenkh' => $row['tenkh'],
        'ngaydangky' => $row['ngaydangky'],
        'trang_thai' => $row['trang_thai']
    ];
}

$status_labels = [
    'cho_xac_nhan' => 'Chờ xác nhận',
    'da_xac_nhan' => 'Đã xác nhận',
    'huy' => 'Đã huỷ'
];
?>

<?php include("header.php"); ?>

<title>Quản trị đăng ký khóa học</title>
<div class="container">
    <div class="auth-shell">
        <div class="auth-card">
            <span class="hero-badge">ADMIN</span>
            <h1>Danh sách người dùng đăng ký khóa học</h1>
            <p>Theo dõi toàn bộ lượt đăng ký khóa học từ người dùng trong hệ thống.</p>

            <div class="profile-summary">
                <div class="profile-item">
                    <strong>Tổng đăng ký</strong>
                    <span><?= count($registrations) ?></span>
                </div>
                <div class="profile-item">
                    <strong>Số người dùng</strong>
                    <span><?= count($groupedRegistrations) ?></span>
                </div>
            </div>

            <?php if (empty($groupedRegistrations)): ?>
                <p>Chưa có dữ liệu đăng ký khóa học nào.</p>
            <?php else: ?>
                <?php foreach ($groupedRegistrations as $user): ?>
                    <fieldset>
                        <div class="form-row">
                            <label>Người dùng</label>
                            <span><?= htmlspecialchars($user['user_name']) ?></span>
                        </div>
                        <div class="form-row">
                            <label>Email</label>
                            <span><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="form-row">
                            <label>Khóa học đăng ký</label>
                            <div>
                                <ul>
                                    <?php foreach ($user['courses'] as $course): ?>
                                        <li>
                                            <?= htmlspecialchars($course['tenkh']) ?>,
                                            <?= date('d/m/Y H:i', strtotime($course['ngaydangky'])) ?>,
                                            <?= htmlspecialchars($status_labels[$course['trang_thai']] ?? 'Không rõ') ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="auth-side">
            <span class="hero-badge">QUẢN TRỊ</span>
            <h2>Quản lý đăng ký khóa học</h2>
            <p>Xem nhanh người dùng nào đã đăng ký khóa học nào để hỗ trợ xác nhận và theo dõi.</p>
            <ul class="auth-list">
                <li><i class="fa-solid fa-users"></i> Hiển thị toàn bộ người dùng đã đăng ký</li>
                <li><i class="fa-solid fa-book-open"></i> Theo dõi khóa học đã đăng ký</li>
                <li><i class="fa-solid fa-clock"></i> Xem thời gian và trạng thái đăng ký</li>
            </ul>
        </div>
    </div>
</div>

<?php include("footer.html"); ?>
