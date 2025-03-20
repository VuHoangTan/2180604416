<?php
include 'config.php';
session_start();

// Kiểm tra nếu sinh viên chưa đăng nhập
if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

// Kiểm tra lỗi khi truy vấn
$sql = "SELECT * FROM hocphan";
$result = $conn->query($sql);
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
        <h2 class="text-uppercase text-center">Danh Sách Học Phần</h2>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert <?= $_GET['status'] == 'success' ? 'alert-success' : 'alert-danger' ?> text-center">
                <?= $_GET['message'] ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Đăng Ký</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['MaHP']) ?></td>
                    <td><?= htmlspecialchars($row['TenHP']) ?></td>
                    <td><?= htmlspecialchars($row['SoTinChi']) ?></td>
                    <td>
                        <form method="POST" action="dangky.php">
                            <input type="hidden" name="MaHP" value="<?= htmlspecialchars($row['MaHP']) ?>">
                            <input type="hidden" name="MaSV" value="<?= htmlspecialchars($MaSV) ?>">
                            <button type="submit" class="btn btn-success">Đăng Ký</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>
</body>
</html>
