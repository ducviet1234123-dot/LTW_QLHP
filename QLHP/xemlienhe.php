<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("mysqlConnect.php");

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $stmt = $mysqli->prepare("DELETE FROM `lienhe` WHERE `id` = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>
        alert('Xoá liên hệ thành công!');
        window.location.href = 'xemlienhe.php';
        </script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra, không thể xoá.');</script>";
    }
    $stmt->close();
}

$query = "SELECT * FROM `lienhe` ORDER BY `created_at` ASC";
$result = $mysqli->query($query);
?>
<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Xem Liên H</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<main>
    <div class="khoahoc-container">
        
        <section class="category">
            <div class="category-head">
                <h2>Danh sách ý kiến liên hệ</h2>
            </div>
            
            <div class="course-grid" style="grid-template-columns: 1fr;">
                <div class="course-card enrolled">
                    <div class="card-body detail-panel" style="max-height: none; opacity: 1; border-width: 0; padding: 20px; overflow: visible;">
                        
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Họ tên</th>
                                    <th style="width: 220px;">Email</th>
                                    <th style="width: 200px;">Chủ đề</th>
                                    <th>Nội dung tin nhắn</th>
                                    <th style="width: 180px;">Thời gian gửi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($result && $result->num_rows > 0): 
                                    while ($row = $result->fetch_assoc()): 
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['subject']); ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                                        <td><?= date('d/m/Y - H:i:s', strtotime($row['created_at'])); ?></td>
                                        <td style="text-align: center;">
                                            <a href="xemlienhe.php?delete_id=<?= $row['id']; ?>" 
                                               style="color: #ff3b30; font-weight: bold; font-size: 16px;" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xoá bỏ liên hệ này không?');">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile; 
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                                            Chưa có dữ liệu liên hệ nào được gửi đến hệ thống.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include("footer.html"); ?>
</body>
</html>