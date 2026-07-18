<link rel="stylesheet" href="css/style.css">
<?php include 'header.php'; ?>

<?php
require_once('mysqlConnect.php');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$is_logged_in     = isset($_SESSION['user']);
$current_user_id  = $_SESSION['user']['ID'] ?? null;

// 1. XỬ LÝ ĐĂNG KÝ KHÓA HỌC (POST) — dùng mẫu Post/Redirect/Get
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'enroll') {

  if (!$is_logged_in) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Vui lòng đăng nhập trước khi đăng ký khóa học.'];
    header('Location: khoahoc.php');
    exit();
  }

  $ma_kh        = $_POST['course_id']     ?? '';
  $ma_giao_dich = trim($_POST['ma_giao_dich'] ?? '');
  $so_tien      = $_POST['so_tien'] ?? 0;

  $stmt = $mysqli->prepare("INSERT INTO dangkykhoahoc (id_nguoidung, id_khoahoc, ma_giao_dich, so_tien)
           VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iiss", $current_user_id, $ma_kh, $ma_giao_dich, $so_tien);

  if ($stmt->execute()) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký thành công! Chúng tôi sẽ xác nhận thanh toán trong thời gian sớm nhất.'];
  } elseif ($mysqli->errno === 1062) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Bạn đã đăng ký khóa học này rồi.'];
  } else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Có lỗi xảy ra, vui lòng thử lại.'];
  }
  $stmt->close();

  header('Location: khoahoc.php');
  exit();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// 2. LẤY DANH SÁCH KHÓA HỌC TỪ CSDL, NHÓM THEO DANH MỤC
$all_courses = [];
$result = $mysqli->query("SELECT * FROM khoahoc ORDER BY danhmuc, thutu ASC");
while ($row = $result->fetch_assoc()) {
  $all_courses[] = $row;
}

$courses = [];
foreach ($all_courses as $c) {
  $courses[$c['danhmuc']][] = $c;
}

// 3. LẤY DANH SÁCH KHÓA HỌC ĐÃ ĐĂNG KÝ CỦA NGƯỜI DÙNG HIỆN TẠI
$registered = [];
if ($is_logged_in) {
  $stmt = $mysqli->prepare(
    "SELECT kh.*, dk.ngaydangky, dk.trang_thai
         FROM dangkykhoahoc dk
         JOIN khoahoc kh ON dk.id_khoahoc = kh.makh
         WHERE dk.id_nguoidung = ?
         ORDER BY dk.ngaydangky DESC"
  );
  $stmt->bind_param("i", $current_user_id);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $registered[] = $row;
  }
  $stmt->close();
}
$registered_ids = array_column($registered, 'makh');

$category_titles = [
  'toeic'    => ['Lộ trình TOEIC', 'Dành cho người cần thi chứng chỉ 2 kỹ năng nghe đọc truyền thống.', 'grad-toeic'],
  'ielts'    => ['Lộ trình IELTS', 'Chuẩn học thuật Academic quốc tế phục vụ du học, xét tuyển.', 'grad-ielts'],
  'vstep'    => ['Lộ trình VSTEP', 'Định dạng đề thi đánh giá năng lực ngoại ngữ theo khung chuẩn VN.', 'grad-vstep'],
  'cambridge' => ['Lộ trình Cambridge English', 'Chuẩn quốc tế với các chứng chỉ KET, PET, FCE, CAE, CPE.', 'grad-ielts'],
  'giaotiep' => ['Tiếng Anh Giao Tiếp', 'Phát triển phản xạ nghe nói tự nhiên cho đời sống và công việc.', 'grad-giaotiep'],
];

$js_courses = $all_courses;
foreach ($js_courses as &$c) {
  $c['ketqua']  = json_decode($c['ketqua'], true)  ?: [];
  $c['noidung'] = json_decode($c['noidung'], true) ?: [];
}
unset($c);
?>

<div class="course-toolbar">
  <input type="text" id="courseSearchInput" placeholder="Tìm khóa học TOEIC, IELTS, VSTEP...">
</div>

<div class="course-page"
  data-flash-type="<?php echo $flash ? htmlspecialchars($flash['type']) : ''; ?>"
  data-flash-msg="<?php echo $flash ? htmlspecialchars($flash['msg']) : ''; ?>">

  <?php if ($is_logged_in && !empty($registered)): ?>
    <section class="course-section my-courses">
      <div class="course-section-head">
        <h2>Khóa học của tôi</h2>
      </div>
      <div class="course-grid">
        <?php foreach ($registered as $c):
          $trangThaiText = match ($c['trang_thai']) {
            'da_xac_nhan' => ['✓ Đã xác nhận', 'badge-confirmed'],
            'huy'         => ['✕ Đã huỷ', 'badge-cancelled'],
            default       => ['⏳ Chờ xác nhận', 'badge-pending'],
          };
        ?>
          <div class="course-card enrolled">
            <div class="card-media card-media-photo">
              <img src="<?php echo htmlspecialchars($c['hinhanh']); ?>" alt="<?php echo htmlspecialchars($c['tenkh']); ?>">
            </div>
            <div class="card-body">
              <h3><?php echo htmlspecialchars($c['tenkh']); ?></h3>
              <div class="card-meta">
                <span class="card-teacher">GV: <?php echo htmlspecialchars($c['giangvien']); ?></span>
                <span class="card-date"><?php echo date('d/m/Y', strtotime($c['ngaydangky'])); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php foreach ($courses as $key => $course_list):
    [$title, $desc] = $category_titles[$key] ?? [$key, ''];
  ?>
    <section class="course-section" id="<?php echo htmlspecialchars($key); ?>" data-category="<?php echo htmlspecialchars($key); ?>">
      <div class="course-section-head">
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <p><?php echo htmlspecialchars($desc); ?></p>
      </div>
      <div class="roadmap">
        <?php foreach ($course_list as $i => $course):
          $is_registered = in_array($course['makh'], $registered_ids);
        ?>
          <div class="course-card <?php echo $is_registered ? 'is-registered' : ''; ?>"
            data-name="<?php echo htmlspecialchars(mb_strtolower($course['tenkh'])); ?>"
            data-makh="<?php echo $course['makh']; ?>"
            onclick="toggleDetail(this, '<?php echo $course['makh']; ?>')">

            <div class="card-media card-media-photo">
              <img src="<?php echo htmlspecialchars($course['hinhanh']); ?>" alt="<?php echo htmlspecialchars($course['tenkh']); ?>">
            </div>

            <div class="card-body">
              <h3><?php echo htmlspecialchars($course['tenkh']); ?></h3>
              <div class="card-meta">
                <span class="card-students">👥 <?php echo number_format($course['sohocvien']); ?></span>
                <span class="card-price"><?php echo number_format($course['gia']); ?>đ</span>
              </div>
              <?php if ($is_registered): ?>
                <span class="tag-registered">Đã đăng ký</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="course-detail-panel"></div>
      </div>
    </section>
  <?php endforeach; ?>

</div>

<div id="siteToast" class="site-toast" role="status" aria-live="polite"></div>

<script>
  const ALL_COURSES = <?php echo json_encode($js_courses, JSON_UNESCAPED_UNICODE); ?>;
  const IS_LOGGED_IN = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
  const REGISTERED_IDS = <?php echo json_encode($registered_ids, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="./js/script.js"></script>

<?php include 'footer.html'; ?>