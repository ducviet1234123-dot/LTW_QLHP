<!DOCTYPE html>

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

?>
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

</body>

</html> 
<?php include 'footer.html'; ?> 

