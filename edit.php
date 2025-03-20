<?php
include 'config.php'; // Đảm bảo file này có kết nối $conn

// Kiểm tra nếu không có id trên URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: Không tìm thấy sinh viên!");
}

$id = $_GET['id'];

// Lấy thông tin sinh viên từ database
$stmt = $conn->prepare("SELECT * FROM sinhvien WHERE MaSV = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Kiểm tra nếu sinh viên không tồn tại
if (!$row) {
    die("Lỗi: Không tìm thấy sinh viên có ID = $id");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    // Xử lý upload ảnh
    if (!empty($_FILES['Hinh']['name'])) {
        $Hinh = $_FILES['Hinh']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($Hinh);

        // Kiểm tra định dạng file ảnh hợp lệ
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        if (!in_array($imageFileType, $allowed_types)) {
            die("Lỗi: Chỉ chấp nhận file ảnh JPG, JPEG, PNG.");
        }

        // Di chuyển file ảnh vào thư mục
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file);
    } else {
        $Hinh = $row['Hinh']; // Nếu không upload ảnh mới, giữ ảnh cũ
    }

    // Cập nhật thông tin sinh viên
    $stmt = $conn->prepare("UPDATE sinhvien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?");
    $stmt->bind_param("ssssss", $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location='index.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase">Hiệu chỉnh thông tin sinh viên</h2>
        <form action="edit.php?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($row['HoTen']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select name="GioiTinh" class="form-control" required>
                    <option value="Nam" <?= ($row['GioiTinh'] == 'Nam') ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= ($row['GioiTinh'] == 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" name="NgaySinh" class="form-control" value="<?= htmlspecialchars($row['NgaySinh']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình</label>
                <input type="file" name="Hinh" class="form-control">
                <br>
                <img src="images/<?= htmlspecialchars($row['Hinh']) ?>" alt="Hình sinh viên" width="120">
            </div>
            <div class="mb-3">
                <label class="form-label">Mã Ngành</label>
                <input type="text" name="MaNganh" class="form-control" value="<?= htmlspecialchars($row['MaNganh']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
        <a href="index.php" class="mt-3 d-block">Quay lại danh sách</a>
    </div>
</body>
</html>
