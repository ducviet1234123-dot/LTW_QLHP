<?php include 'header.php'; ?>
<?php
/**
 * khoahoc.php — Trang danh sách khóa học + đăng ký khóa học
 * Toàn bộ dữ liệu lấy từ MySQL qua PDO (prepared statements), KHÔNG còn mảng cứng.
 *
 * Hợp đồng session dùng chung với dangkydangnhap.php (xem HUONGDAN.md):
 *   $_SESSION['user_id']  = ID của NguoiDung sau khi đăng nhập
 *   $_SESSION['username'] = TenDangNhap để hiển thị lời chào
 */
require_once 'mysqlConnect.php';

$is_logged_in = isset($_SESSION['user_id']);

// ---------------------------------------------------------------
// 1. XỬ LÝ ĐĂNG KÝ KHÓA HỌC (POST) — dùng mẫu Post/Redirect/Get
//    để tránh việc F5 lại gửi POST lần nữa.
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'enroll') {

    if (!$is_logged_in) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Vui lòng đăng nhập trước khi đăng ký khóa học.'];
    } else {
        $ma_kh = $_POST['course_id'] ?? '';

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO DangKyKhoaHoc (ID_NguoiDung, ID_KhoaHoc) VALUES (?, ?)"
            );
            $stmt->execute([$_SESSION['user_id'], $ma_kh]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký khóa học thành công!'];
        } catch (PDOException $e) {
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
$stmt = $pdo->query("SELECT * FROM KhoaHoc ORDER BY DanhMuc, ThuTu ASC");
$all_courses = $stmt->fetchAll();

$courses = [];
foreach ($all_courses as $c) {
    $courses[$c['DanhMuc']][] = $c;
}

// ---------------------------------------------------------------
// 3. LẤY DANH SÁCH KHÓA HỌC ĐÃ ĐĂNG KÝ CỦA NGƯỜI DÙNG HIỆN TẠI
//    (JOIN DangKyKhoaHoc với KhoaHoc theo ID_NguoiDung đang đăng nhập)
// ---------------------------------------------------------------
$registered = [];
if ($is_logged_in) {
    $stmt = $pdo->prepare(
        "SELECT kh.*, dk.NgayDangKy
         FROM DangKyKhoaHoc dk
         JOIN KhoaHoc kh ON dk.ID_KhoaHoc = kh.MaKH
         WHERE dk.ID_NguoiDung = ?
         ORDER BY dk.NgayDangKy DESC"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $registered = $stmt->fetchAll();
}
// Tập hợp mã khóa học đã đăng ký để tô trạng thái "Đã đăng ký" trên các thẻ khóa học
$registered_ids = array_column($registered, 'MaKH');

// Tiêu đề + mô tả hiển thị cho từng khối danh mục
$category_titles = [
    'toeic'    => ['Lộ trình TOEIC', 'Dành cho người cần thi chứng chỉ 2 kỹ năng nghe đọc truyền thống.', 'grad-toeic'],
    'toeic4kn' => ['TOEIC 4 Kỹ Năng', 'Rèn luyện kỹ năng toàn diện Nghe - Nói - Đọc - Viết ứng dụng.', 'grad-toeic4kn'],
    'ielts'    => ['Lộ trình IELTS', 'Chuẩn học thuật Academic quốc tế phục vụ du học, xét tuyển.', 'grad-ielts'],
    'vstep'    => ['Lộ trình VSTEP', 'Định dạng đề thi đánh giá năng lực ngoại ngữ theo khung chuẩn VN.', 'grad-vstep'],
    'giaotiep' => ['Tiếng Anh Giao Tiếp', 'Phát triển phản xạ nghe nói tự nhiên cho đời sống và công việc.', 'grad-giaotiep'],
];

// Dữ liệu đẩy sang JS để render bảng chi tiết khóa học (không lộ mật khẩu/ session)
$js_courses = $all_courses;
foreach ($js_courses as &$c) {
    $c['KetQua']  = json_decode($c['KetQua'], true)  ?: [];
    $c['NoiDung'] = json_decode($c['NoiDung'], true) ?: [];
}
unset($c);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khóa học · EngLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body data-flash-type="<?php echo $flash ? htmlspecialchars($flash['type']) : ''; ?>"
      data-flash-msg="<?php echo $flash ? htmlspecialchars($flash['msg']) : ''; ?>">

  <section class="hero">
    <div class="hero-inner">
      <span class="hero-eyebrow">CHỦ ĐỀ 5 · PHP &amp; MYSQL</span>
      <h1 class="hero-title">Chọn lộ trình, đi đúng nhịp</h1>
      <p class="hero-sub">Mỗi khối kỹ năng là một lộ trình gồm nhiều mốc, xếp từ nền tảng đến nâng cao — bạn đăng ký mốc nào phù hợp trình độ hiện tại là được.</p>
    </div>
  </section>

  <main class="page-container">

    <?php if ($is_logged_in && !empty($registered)): ?>
    <section class="category my-courses">
      <div class="category-head">
        <h2>Khóa học của tôi</h2>
        <p>Danh sách khóa học bạn đã đăng ký, lấy trực tiếp từ CSDL.</p>
      </div>
      <div class="course-grid">
        <?php foreach ($registered as $c): ?>
            <div class="course-card enrolled">
              <div class="card-media">
                <span class="enrolled-badge">✓ Đã đăng ký</span>
                <?php echo htmlspecialchars($c['TenKH']); ?>
              </div>
              <div class="card-body">
                <h3><?php echo htmlspecialchars($c['TenKH']); ?></h3>
                <div class="card-meta">
                  <span class="card-teacher">GV: <?php echo htmlspecialchars($c['GiangVien']); ?></span>
                  <span class="card-date"><?php echo date('d/m/Y', strtotime($c['NgayDangKy'])); ?></span>
                </div>
              </div>
            </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <?php foreach ($courses as $key => $course_list):
        [$title, $desc, $gradClass] = $category_titles[$key] ?? [$key, '', 'grad-toeic'];
    ?>
    <section class="category" data-category="<?php echo htmlspecialchars($key); ?>">
      <div class="category-head">
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <p><?php echo htmlspecialchars($desc); ?></p>
      </div>

      <div class="roadmap">
        <?php foreach ($course_list as $i => $course):
            $is_registered = in_array($course['MaKH'], $registered_ids);
        ?>
          <div class="roadmap-step">
            <div class="step-node <?php echo $gradClass; ?>"><?php echo $i + 1; ?></div>
            <div class="course-card <?php echo $is_registered ? 'is-registered' : ''; ?>"
                 data-name="<?php echo htmlspecialchars(mb_strtolower($course['TenKH'])); ?>"
                 onclick="toggleDetail('<?php echo htmlspecialchars($key); ?>', '<?php echo htmlspecialchars($course['MaKH']); ?>')">
              <div class="card-media <?php echo $gradClass; ?>"><?php echo htmlspecialchars($course['TenKH']); ?></div>
              <div class="card-body">
                <h3><?php echo htmlspecialchars($course['TenKH']); ?></h3>
                <div class="card-meta">
                  <span class="card-students">👥 <?php echo number_format($course['SoHocVien']); ?> HV</span>
                  <span class="card-price"><?php echo number_format($course['Gia']); ?>đ</span>
                </div>
                <?php if ($is_registered): ?>
                  <span class="tag-registered">Đã đăng ký</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="detail-panel" id="detail-<?php echo htmlspecialchars($key); ?>" style="display: none;"></div>
    </section>
    <?php endforeach; ?>

  </main>

  <div id="toast" class="toast" role="status" aria-live="polite"></div>

  <script>
    const ALL_COURSES = <?php echo json_encode($js_courses, JSON_UNESCAPED_UNICODE); ?>;
    const IS_LOGGED_IN = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
    const REGISTERED_IDS = <?php echo json_encode($registered_ids, JSON_UNESCAPED_UNICODE); ?>;
  </script>
  <script src="script.js"></script>
</body>
</html>

<?php include 'footer.html'; ?>