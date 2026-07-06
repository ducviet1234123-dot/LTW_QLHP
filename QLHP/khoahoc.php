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
            // Sửa MySQLi: Kiểm tra xem khóa học này thực sự có tồn tại hay không
            $check_course = $mysqli->prepare("SELECT 1 FROM khoahoc WHERE MaKH = ?");
            $check_course->bind_param("s", $ma_kh);
            $check_course->execute();
            $check_course->store_result();
            
            if ($check_course->num_rows === 0) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Khóa học không tồn tại.'];
            } else {
                // Sửa MySQLi: Chuẩn hóa câu lệnh INSERT
                $stmt = $mysqli->prepare(
                    "INSERT INTO DangKyKhoaHoc (ID_NguoiDung, ID_KhoaHoc) VALUES (?, ?)"
                );
                $stmt->bind_param("is", $_SESSION['user_id'], $ma_kh);
                $stmt->execute();
                
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký khóa học thành công!'];
            }
            $check_course->close();
            if (isset($stmt)) $stmt->close();

        } catch (mysqli_sql_exception $e) { // Sửa tên Exception chuẩn của MySQLi
            // Mã lỗi 1062 trong MySQL = vi phạm UNIQUE KEY (đã đăng ký khóa này rồi)
            if ($e->getCode() == 1062) {
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
$result = $mysqli->query("SELECT * FROM khoahoc ORDER BY DanhMuc, ThuTu ASC");
// Thay thế fetchAll() bằng cú pháp fetch_all của MySQLi
$all_courses = $result->fetch_all(MYSQLI_ASSOC);

$courses = [];
foreach ($all_courses as $c) {
    $courses[$c['DanhMuc']][] = $c;
}

// ---------------------------------------------------------------
// 3. LẤY DANH SÁCH KHÓA HỌC ĐÃ ĐĂNG KÝ CỦA NGƯỜI DÙNG HIỆN TẠI
// ---------------------------------------------------------------
$registered = [];
if ($is_logged_in) {
    $stmt = $mysqli->prepare(
        "SELECT kh.*, dk.NgayDangKy
         FROM DangKyKhoaHoc dk
         JOIN khoahoc kh ON dk.ID_KhoaHoc = kh.MaKH
         WHERE dk.ID_NguoiDung = ?
         ORDER BY dk.NgayDangKy DESC"
    );
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    
    // Thay thế fetchAll() bằng cách lấy result rồi fetch_all
    $res = $stmt->get_result();
    $registered = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
// Tập hợp mã khóa học đã đăng ký để tô trạng thái "Đã đăng ký" trên các thẻ khóa học
$registered_ids = array_column($registered, 'MaKH');

// ... (Các đoạn mã cấu hình $category_titles và HTML bên dưới giữ nguyên)