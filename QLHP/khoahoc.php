<?php include 'header.php'; ?>
<?php
/**
 * khoahoc.php — Trang danh sách khóa học + đăng ký khóa học
 */
require_once 'mysqlConnect.php';

$is_logged_in = isset($_SESSION['user_id']);

// ---------------------------------------------------------------
// 1. XỬ LÝ ĐĂNG KÝ KHÓA HỌC (POST)
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'enroll') {

    if (!$is_logged_in) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Vui lòng đăng nhập trước khi đăng ký khóa học.'];
    } else {
        $ma_kh = $_POST['course_id'] ?? '';

        try {
            // Bổ sung: Kiểm tra xem khóa học này thực sự có tồn tại hay không
            $check_course = $mysqli->prepare("SELECT 1 FROM khoahoc WHERE MaKH = ?");
            $check_course->execute([$ma_kh]);
            
            if (!$check_course->fetch()) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Khóa học không tồn tại.'];
            } else {
                // Đồng nhất tên bảng DangKyKhoaHoc hoặc dangkykhoahoc tùy CSDL của bạn
                $stmt = $mysqli->prepare(
                    "INSERT INTO DangKyKhoaHoc (ID_NguoiDung, ID_KhoaHoc) VALUES (?, ?)"
                );
                $stmt->execute([$_SESSION['user_id'], $ma_kh]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký khóa học thành công!'];
            }
        } catch (mysqliException $e) {
            // Mã lỗi 23000 = vi phạm UNIQUE KEY (đã đăng ký khóa này rồi)
            if ($e->getCode() == 23000) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Bạn đã đăng ký khóa học này rồi.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Có lỗi xảy ra, vui lòng thử lại.'];
            }
        }
    }

    header('Location: khoahoc.php');
    exit();
}

// Lấy thông báo flash (nếu có) rồi xóa khỏi session
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// ---------------------------------------------------------------
// 2. LẤY DANH SÁCH KHÓA HỌC TỪ CSDL, NHÓM THEO DANH MỤC
// ---------------------------------------------------------------
// Sử dụng chữ thường 'khoahoc' để đồng nhất
$stmt = $mysqli->query("SELECT * FROM khoahoc ORDER BY DanhMuc, ThuTu ASC");
$all_courses = $stmt->fetchAll();

$courses = [];
foreach ($all_courses as $c) {
    $courses[$c['DanhMuc']][] = $c;
}

// ---------------------------------------------------------------
// 3. LẤY DANH SÁCH KHÓA HỌC ĐÃ ĐĂNG KÝ CỦA NGƯỜI DÙNG HIỆN TẠI
// ---------------------------------------------------------------
$registered = [];
if ($is_logged_in) {
    // SỬA LỖI LOGIC: Đổi tên bảng từ 'KhoaHoc' viết hoa thành 'khoahoc' viết thường
    $stmt = $mysqli->prepare(
        "SELECT kh.*, dk.NgayDangKy
         FROM DangKyKhoaHoc dk
         JOIN khoahoc kh ON dk.ID_KhoaHoc = kh.MaKH
         WHERE dk.ID_NguoiDung = ?
         ORDER BY dk.NgayDangKy DESC"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $registered = $stmt->fetchAll();
}
// Tập hợp mã khóa học đã đăng ký để tô trạng thái "Đã đăng ký" trên các thẻ khóa học
$registered_ids = array_column($registered, 'MaKH');

// ... (Các đoạn mã cấu hình $category_titles và HTML bên dưới giữ nguyên)