<?php
include 'config.php';
session_start();

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Lỗi kết nối CSDL: " . mysqli_connect_error());
}

// Kiểm tra nếu sinh viên chưa đăng nhập
$MaSV = isset($_SESSION['MaSV']) ? $_SESSION['MaSV'] : 'SV001';

// Truy vấn danh sách học phần đã đăng ký
$sql = "SELECT hocphan.MaHP, hocphan.TenHP, hocphan.SoTinChi 
        FROM chitietdangky 
        JOIN hocphan ON chitietdangky.MaHP = hocphan.MaHP 
        JOIN dangky ON chitietdangky.MaDK = dangky.MaDK
        WHERE dangky.MaSV = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase text-center">Đăng Ký Học Phần</h2>

        <!-- Kiểm tra nếu có học phần đã đăng ký -->
        <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalTinChi = 0;
                while ($row = $result->fetch_assoc()):
                    $totalTinChi += $row['SoTinChi'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['MaHP']) ?></td>
                    <td><?= htmlspecialchars($row['TenHP']) ?></td>
                    <td><?= htmlspecialchars($row['SoTinChi']) ?></td>
                    <td>
                        <a href="xoa.php?MaHP=<?= urlencode($row['MaHP']) ?>" class="btn btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này không?');">
                           Xóa
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p class="text-danger fw-bold">Số học phần: <?= $result->num_rows; ?></p>
        <p class="text-danger fw-bold">Tổng số tín chỉ: <?= $totalTinChi; ?></p>

        <a href="xoa_toan_bo.php" class="btn btn-warning"
           onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ đăng ký không?');">
           Xóa Đăng Ký
        </a>
        <a href="hocphan.php" class="btn btn-primary">Đăng ký thêm</a>

        <?php else: ?>
            <p class="text-center text-danger">Bạn chưa đăng ký học phần nào!</p>
            <a href="hocphan.php" class="btn btn-primary">Đăng ký học phần</a>
        <?php endif; ?>

    </div>
</body>
</html>
