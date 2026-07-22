<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('mysqlConnect.php');

// 1. KIỂM TRA QUYỀN ADMIN (Bắt buộc phải là admin mới được vào)
if (!isset($_SESSION['user']) || $_SESSION['user']['name'] !== 'admin') {
    echo "<script>alert('Vùng cấm! Bạn không phải là quản trị viên.'); window.location.href = 'index.php';</script>";
    exit();
}

// 2. XỬ LÝ THÊM KHÓA HỌC (Có Upload Ảnh)
if (isset($_POST['them_khoahoc'])) {
    $tenkh = $_POST['tenkh'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $giangvien = $_POST['giangvien'];
    $danhmuc = $_POST['danhmuc'];
    
    // Xử lý upload file ảnh
    $hinhanh = ""; // Giá trị mặc định nếu không có ảnh
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $thumuc_luu = "img/"; // Thư mục lưu ảnh
        
        // Thêm time() vào tên file để tránh việc trùng tên ảnh cũ
        $ten_file = time() . "_" . basename($_FILES["hinhanh"]["name"]);
        $duong_dan_file = $thumuc_luu . $ten_file;
        
        // Di chuyển file từ bộ nhớ tạm vào thư mục dự án
        if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $duong_dan_file)) {
            $hinhanh = $duong_dan_file; // Lưu đường dẫn này vào database
        }
    }

    $stmt = $mysqli->prepare("INSERT INTO khoahoc (tenkh, mota, gia, giangvien, danhmuc, hinhanh) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsss", $tenkh, $mota, $gia, $giangvien, $danhmuc, $hinhanh);
    
    if ($stmt->execute()) {
        header("Location: quantri.php");
        exit();
    }
    $stmt->close();
}

// 3. XỬ LÝ XÓA KHÓA HỌC
if (isset($_GET['xoa_id'])) {
    $makh_xoa = intval($_GET['xoa_id']);
    
    // (Tùy chọn: Bạn có thể viết thêm code xóa file ảnh vật lý trong thư mục img/ ở đây nếu muốn)

    // Xóa liên kết khóa ngoại trước
    $stmt_del_dk = $mysqli->prepare("DELETE FROM dangkykhoahoc WHERE id_khoahoc = ?");
    $stmt_del_dk->bind_param("i", $makh_xoa);
    $stmt_del_dk->execute();
    
    // Xóa khóa học
    $stmt_del_kh = $mysqli->prepare("DELETE FROM khoahoc WHERE makh = ?");
    $stmt_del_kh->bind_param("i", $makh_xoa);
    $stmt_del_kh->execute();
    
    header("Location: quantri.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - THE FORUM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }
        
        /* Sidebar Menu bên trái */
        .sidebar { width: 250px; background-color: #2c3e50; color: #fff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; background-color: #1a252f; text-align: center; font-size: 24px; font-weight: bold; color: #5edbca; }
        .sidebar-menu { list-style: none; padding: 20px 0; flex: 1; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: #ecf0f1; text-decoration: none; font-size: 16px; transition: 0.3s; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #34495e; border-left: 4px solid #5edbca; }
        .sidebar-menu li a i { margin-right: 10px; width: 20px; text-align: center; }
        
        /* Nội dung chính bên phải */
        .main-content { flex: 1; overflow-y: auto; }
        .topbar { background: #fff; padding: 15px 30px; display: flex; justify-content: flex-end; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .topbar span { font-weight: bold; margin-right: 15px; }
        .topbar a { color: #e74c3c; text-decoration: none; font-weight: bold; }
        
        .content { padding: 30px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .card-header { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        /* Form và Table */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full { grid-column: span 2; }
        input[type="text"], input[type="number"], select, textarea, input[type="file"] { padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-top: 5px; outline: none; }
        button.btn-primary { background: #5edbca; color: #0b4a45; border: none; padding: 12px 20px; font-weight: bold; cursor: pointer; border-radius: 5px; font-size: 16px; margin-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        table th { background-color: #f8f9fa; color: #333; }
        .btn-action { text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; margin-right: 5px; display: inline-block; }
        .btn-edit { background: #3498db; color: #fff; }
        .btn-del { background: #e74c3c; color: #fff; }
        .img-thumbnail { width: 60px; height: auto; border-radius: 4px; box-shadow: 0 0 5px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">ADMIN PANEL</div>
        <ul class="sidebar-menu">
            <li><a href="quantri.php" class="active"><i class="fa-solid fa-book"></i> Quản lý khóa học</a></li>
            <li><a href="xemlienhe.php"><i class="fa-solid fa-envelope"></i> Hộp thư liên hệ</a></li>
            <li><a href="index.php"><i class="fa-solid fa-globe"></i> Xem Website</a></li>
        </ul>
    </aside>

    <!-- KHOẢNG NỘI DUNG -->
    <main class="main-content">
        <header class="topbar">
            <span>Xin chào, Admin</span>
            <a href="Dangxuat.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
        </header>

        <section class="content">
            <!-- Khu vực Thêm khóa học -->
            <div class="card">
                <div class="card-header">Thêm Khóa Học Mới</div>
                <!-- Bắt buộc phải có enctype="multipart/form-data" để up file -->
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Tên khóa học</label>
                            <input type="text" name="tenkh" required>
                        </div>
                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="danhmuc" required>
                                <option value="ielts">IELTS</option>
                                <option value="toeic">TOEIC</option>
                                <option value="vstep">VSTEP</option>
                                <option value="cambridge">Cambridge English</option>
                                <option value="giaotiep">Giao Tiếp</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Giá tiền (VNĐ)</label>
                            <input type="number" name="gia" required>
                        </div>
                        <div class="form-group">
                            <label>Giảng viên</label>
                            <input type="text" name="giangvien" required>
                        </div>
                        <div class="form-group full">
                            <label>Tải ảnh lên (Từ máy tính)</label>
                            <!-- Chuyển type="text" thành type="file" -->
                            <input type="file" name="hinhanh" accept="image/*" required>
                        </div>
                        <div class="form-group full">
                            <label>Mô tả ngắn</label>
                            <textarea name="mota" rows="3" required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="them_khoahoc" class="btn-primary"><i class="fa-solid fa-plus"></i> Thêm vào kho</button>
                </form>
            </div>

            <!-- Khu vực Danh sách -->
            <div class="card">
                <div class="card-header">Kho Khóa Học Hiện Tại</div>
                <table>
                    <thead>
                        <tr>
                            <th>Mã KH</th>
                            <th>Ảnh</th>
                            <th>Khóa học</th>
                            <th>Danh mục</th>
                            <th>Giảng viên</th>
                            <th>Giá</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $mysqli->query("SELECT * FROM khoahoc ORDER BY makh DESC");
                        while ($row = $result->fetch_assoc()) {
                            $gia_format = number_format($row['gia'], 0, ',', '.') . 'đ';
                            
                            // Kiểm tra nếu có ảnh thì hiển thị, không thì để trống
                            $hien_thi_anh = !empty($row['hinhanh']) ? "<img src='{$row['hinhanh']}' class='img-thumbnail' alt='IMG'>" : "Không có";

                            echo "<tr>
                                    <td>{$row['makh']}</td>
                                    <td>{$hien_thi_anh}</td>
                                    <td><strong>{$row['tenkh']}</strong></td>
                                    <td style='text-transform:uppercase;'>{$row['danhmuc']}</td>
                                    <td>{$row['giangvien']}</td>
                                    <td style='color:#e74c3c; font-weight:bold;'>{$gia_format}</td>
                                    <td>
                                        <a href='capnhat_khoahoc.php?id={$row['makh']}' class='btn-action btn-edit'>Sửa</a>
                                        <a href='?xoa_id={$row['makh']}' class='btn-action btn-del' onclick=\"return confirm('Bạn có chắc muốn xóa?');\">Xóa</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>