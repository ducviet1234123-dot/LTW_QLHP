<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('mysqlConnect.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['name'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: quantri.php");
    exit();
}

$makh = intval($_GET['id']);
$stmt = $mysqli->prepare("SELECT * FROM khoahoc WHERE makh = ?");
$stmt->bind_param("i", $makh);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (isset($_POST['capnhat'])) {
    $tenkh = $_POST['tenkh'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $giangvien = $_POST['giangvien'];
    $danhmuc = $_POST['danhmuc'];
    
    // BƯỚC 1: Lấy lại ảnh cũ làm mặc định
    $hinhanh = $course['hinhanh'];

    // BƯỚC 2: Kiểm tra nếu có tải file ảnh mới lên thì xử lý
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $thumuc_luu = "img/"; 
        // Tạo thư mục nếu chưa có
        if (!is_dir($thumuc_luu)) {
            mkdir($thumuc_luu, 0777, true);
        }
        $ten_file = time() . "_" . basename($_FILES["hinhanh"]["name"]);
        $duong_dan_file = $thumuc_luu . $ten_file;
        
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $duong_dan_file)) {
            $hinhanh = $duong_dan_file; // Ghi đè đường dẫn ảnh mới
        }
    }

    $update = $mysqli->prepare("UPDATE khoahoc SET tenkh=?, mota=?, gia=?, giangvien=?, danhmuc=?, hinhanh=? WHERE makh=?");
    $update->bind_param("ssdsssi", $tenkh, $mota, $gia, $giangvien, $danhmuc, $hinhanh, $makh);
    $update->execute();
    
    header("Location: quantri.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật Khóa Học - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS dùng chung y hệt trang quantri.php */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }
        .sidebar { width: 250px; background-color: #2c3e50; color: #fff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; background-color: #1a252f; text-align: center; font-size: 24px; font-weight: bold; color: #5edbca; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: #ecf0f1; text-decoration: none; font-size: 16px; }
        .sidebar-menu li a.active { background-color: #34495e; border-left: 4px solid #5edbca; }
        .sidebar-menu li a i { margin-right: 10px; width: 20px; text-align: center; }
        
        .main-content { flex: 1; overflow-y: auto; }
        .topbar { background: #fff; padding: 15px 30px; display: flex; justify-content: flex-end; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .topbar a { color: #e74c3c; text-decoration: none; font-weight: bold; margin-left: 15px; }
        
        .content { padding: 30px; }
        .card { background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 800px; margin: 0 auto; }
        .card-header { font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; outline: none; }
        .btn-submit { background: #3498db; color: #fff; border: none; padding: 12px; width: 100%; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; margin-top: 15px; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">ADMIN PANEL</div>
        <ul class="sidebar-menu">
            <li><a href="quantri.php" class="active"><i class="fa-solid fa-book"></i> Quản lý khóa học</a></li>
            <li><a href="xemlienhe.php"><i class="fa-solid fa-envelope"></i> Hộp thư liên hệ</a></li>
            <li><a href="index.php"><i class="fa-solid fa-globe"></i> Xem Website</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <span>Đang sửa khóa học: #<?php echo $makh; ?></span>
            <a href="Dangxuat.php">Đăng xuất</a>
        </header>

        <section class="content">
            <div class="card">
                <div class="card-header"><i class="fa-solid fa-pen"></i> Cập nhật Khóa học</div>
                
                <!-- BƯỚC 3: Đã thêm enctype="multipart/form-data" -->
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên khóa học</label>
                        <input type="text" name="tenkh" value="<?php echo htmlspecialchars($course['tenkh']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select name="danhmuc" required>
                            <option value="ielts" <?php if($course['danhmuc']=='ielts') echo 'selected'; ?>>IELTS</option>
                            <option value="toeic" <?php if($course['danhmuc']=='toeic') echo 'selected'; ?>>TOEIC</option>
                            <option value="vstep" <?php if($course['danhmuc']=='vstep') echo 'selected'; ?>>VSTEP</option>
                            <option value="cambridge" <?php if($course['danhmuc']=='cambridge') echo 'selected'; ?>>Cambridge English</option>
                            <option value="giaotiep" <?php if($course['danhmuc']=='giaotiep') echo 'selected'; ?>>Giao Tiếp</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá (VNĐ)</label>
                        <input type="text" name="gia" value="<?php echo floatval($course['gia']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Giảng viên</label>
                        <input type="text" name="giangvien" value="<?php echo htmlspecialchars($course['giangvien']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Đổi hình ảnh mới (Bỏ trống nếu muốn giữ nguyên ảnh cũ)</label>
                        <!-- BƯỚC 4: Đã gỡ bỏ required ở input file -->
                        <input type="file" name="hinhanh" accept="image/*">
                        
                        <?php if(!empty($course['hinhanh'])): ?>
                            <p style="margin-top: 10px; font-size: 13px; color: #666;">
                                Ảnh hiện tại: <br>
                                <img src="<?php echo htmlspecialchars($course['hinhanh']); ?>" style="width: 150px; height: auto; border-radius: 4px; margin-top: 5px; border: 1px solid #ddd;">
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mota" rows="4" required><?php echo htmlspecialchars($course['mota']); ?></textarea>
                    </div>
                    
                    <button type="submit" name="capnhat" class="btn-submit">Lưu Thay Đổi</button>
                    <a href="quantri.php" class="btn-cancel">Trở về danh sách</a>
                </form>
            </div>
        </section>
    </main>

</body>
</html>