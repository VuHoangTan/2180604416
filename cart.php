<?php
session_start();
include 'config.php';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xóa một học phần khỏi giỏ hàng
if (isset($_GET['remove'])) {
    if ($_GET['remove'] === 'all') {
        $_SESSION['cart'] = [];
    } else {
        $removeID = $_GET['remove'];
        if (isset($_SESSION['cart'][$removeID])) {
            unset($_SESSION['cart'][$removeID]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Lưu đăng ký học phần
if (isset($_GET['save'])) {
    if (!isset($_SESSION['MaSV'])) {
        echo "<script>alert('Bạn cần đăng nhập!'); window.location='login.php';</script>";
        exit();
    }

    $MaSV = $_SESSION['MaSV'];

    if (!empty($_SESSION['cart'])) {
        $conn->begin_transaction(); // Bắt đầu transaction để tránh lỗi giữa chừng
        $success = true;

        $stmt = $conn->prepare("INSERT INTO dangkyhocphan (MaSV, MaHP) VALUES (?, ?)");

        foreach ($_SESSION['cart'] as $MaHP => $hocphan) {
            $stmt->bind_param("ss", $MaSV, $MaHP);
            if (!$stmt->execute()) {
                $success = false;
                break;
            }
        }

        if ($success) {
            $conn->commit(); // Xác nhận lưu dữ liệu
            $_SESSION['cart'] = [];
            echo "<script>alert('Lưu đăng ký thành công!'); window.location='cart.php';</script>";
        } else {
            $conn->rollback(); // Hoàn tác nếu có lỗi
            echo "<script>alert('Lỗi khi lưu đăng ký, vui lòng thử lại!'); window.location='cart.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Không có học phần nào trong giỏ hàng!'); window.location='cart.php';</script>";
    }
}

$totalCredits = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase text-center">Đăng Ký Học Phần</h2>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $MaHP => $hocphan): ?>
                        <tr>
                            <td><?= htmlspecialchars($MaHP) ?></td>
                            <td><?= htmlspecialchars($hocphan['TenHP']) ?></td>
                            <td><?= htmlspecialchars($hocphan['SoTinChi']) ?></td>
                            <td><a href="cart.php?remove=<?= urlencode($MaHP) ?>" class="btn btn-danger btn-sm">Xóa</a></td>
                        </tr>
                        <?php $totalCredits += $hocphan['SoTinChi']; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-danger">Bạn chưa đăng ký học phần nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p><strong>Tổng số học phần:</strong> <?= count($_SESSION['cart']) ?></p>
        <p><strong>Tổng số tín chỉ:</strong> <?= $totalCredits ?></p>

        <a href="cart.php?save=true" class="btn btn-success <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>">Lưu đăng ký</a>
        <a href="cart.php?remove=all" class="btn btn-danger <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>">Xóa tất cả</a>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>
</body>
</html>
